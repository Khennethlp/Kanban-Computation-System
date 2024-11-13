<?php
require '../conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

function readDataInChunks($filename, $chunkSize, $limit, $fileExtension)
{
    // Select the appropriate reader based on file extension
    if ($fileExtension === 'csv') {
        $reader = new Csv();
        $reader->setDelimiter(',');
        $reader->setEnclosure('"');
        $reader->setSheetIndex(0);
    } elseif ($fileExtension === 'xlsx') {
        $reader = new Xlsx();
    } else {
        throw new Exception("Unsupported file type");
    }

    $spreadsheet = $reader->load($filename);
    $worksheet = $spreadsheet->getActiveSheet();
    
    $data = [];
    $rowCount = 0;

    // Skip the first row (header)
    $headerSkipped = false;

    foreach ($worksheet->getRowIterator() as $row) {
        // Skip the header row
        if (!$headerSkipped) {
            $headerSkipped = true;
            continue;
        }

        if ($rowCount >= $limit) {
            break;
        }

        $rowData = [];
        foreach ($row->getCellIterator() as $cell) {
            $rowData[] = $cell->getFormattedValue();
        }

        // Skip empty rows
        if (array_filter($rowData)) {
            $data[] = $rowData;
            $rowCount++;
        }

        if (count($data) >= $chunkSize) {
            yield $data;
            $data = [];
        }
    }

    if (!empty($data)) {
        yield $data;
    }
}

function insertDataIntoDatabase($conn, $data)
{
    $sql = "INSERT INTO m_bomAid (maker_code, product_no, partcode, partname, need_qty) 
            VALUES (:maker_code, :product_no, :partcode, :partname, :need_qty)";
    $stmt = $conn->prepare($sql);

    foreach ($data as $row) {
        $maker_code = $row[0];
        $product_no = $row[1];
        $partcode = $row[2];
        $partname = $row[4];
        $need_qty = $row[9];

        var_dump($row); 
        
        $stmt->bindParam(':maker_code', $maker_code);
        $stmt->bindParam(':product_no', $product_no);
        $stmt->bindParam(':partcode', $partcode);
        $stmt->bindParam(':partname', $partname);
        $stmt->bindParam(':need_qty', $need_qty);

        try {
            if ($stmt->execute()) {
                echo "Inserted: " . $maker_code . "\n"; // Log the insertion
            } else {
                echo "Error inserting data\n";
            }
        } catch (Exception $e) {
            echo 'Insert failed: ' . $e->getMessage();
        }
    }
}

if (isset($_FILES['csvFile_bomAid'])) {
    $file = $_FILES['csvFile_bomAid'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $tempFile = tempnam(sys_get_temp_dir(), 'file_upload');
        move_uploaded_file($file['tmp_name'], $tempFile);

        // Check file extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, ['csv', 'xlsx'])) {
            echo "Error: The uploaded file must be a CSV or XLSX file.";
            unlink($tempFile);
            exit;
        }

        $limit = 2000;
        $chunkSize = 100; // Process 100 rows at a time
        $totalRowsInserted = 0;

        echo "Data is being processed..."; 
        ob_flush();
        flush();

        $conn->beginTransaction();

        foreach (readDataInChunks($tempFile, $chunkSize, $limit, $fileExtension) as $dataChunk) {
            insertDataIntoDatabase($conn, $dataChunk);
            $totalRowsInserted += count($dataChunk);

            $progress = ($totalRowsInserted / $limit) * 100;
            file_put_contents("progress.json", json_encode(["progress" => $progress]));
    
            if ($totalRowsInserted >= $limit) break;
        }

        $conn->commit();
        unlink($tempFile);
        echo "success";
    } else {
        echo "error";
    }
} else {
    echo "Please select a file to upload.";
}
?>
