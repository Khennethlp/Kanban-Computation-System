<?php
require '../conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to read CSV data
function readCsvData($filename)
{
    if (!file_exists($filename)) {
        return false;
    }

    $data = [];
    $file = fopen($filename, 'r');
    fgetcsv($file); // Skip the header row

    while (($line = fgetcsv($file)) !== false) {
        if (array_filter($line)) {
            $data[] = $line;
        }
    }

    fclose($file);
    return $data;
}

// Function to read Excel data
function readExcelData($filename)
{
    if (!file_exists($filename)) {
        return false;
    }

    $spreadsheet = IOFactory::load($filename);
    $data = [];

    foreach ($spreadsheet->getActiveSheet()->getRowIterator(2) as $row) {
        $rowData = [];
        foreach ($row->getCellIterator() as $cell) {
            $rowData[] = $cell->getFormattedValue();
        }
        if (array_filter($rowData)) {
            $data[] = $rowData;
        }
    }

    return $data;
}

// Check if files are uploaded
if (isset($_FILES['csvFile_bom']) && isset($_FILES['csvFile_bomAid'])) {
    $userName = $_POST['userName'];
    $bom = $_FILES['csvFile_bom'];
    $bomAid = $_FILES['csvFile_bomAid'];

    // Check if both files were uploaded successfully
    if ($bom['error'] === UPLOAD_ERR_OK && $bomAid['error'] === UPLOAD_ERR_OK) {
        // Handle BOM file
        $bomTempFile = tempnam(sys_get_temp_dir(), 'bom_upload');
        move_uploaded_file($bom['tmp_name'], $bomTempFile);

        // Handle BOM Aid file
        $bomAidTempFile = tempnam(sys_get_temp_dir(), 'bomAid_upload');
        move_uploaded_file($bomAid['tmp_name'], $bomAidTempFile);

        // Determine file types and read data accordingly
        $bomExtension = strtolower(pathinfo($bom['name'], PATHINFO_EXTENSION));
        $bomAidExtension = strtolower(pathinfo($bomAid['name'], PATHINFO_EXTENSION));

        // Read BOM file data
        if ($bomExtension === 'csv') {
            $bomData = readCsvData($bomTempFile);
        } elseif (in_array($bomExtension, ['xls', 'xlsx'])) {
            $bomData = readExcelData($bomTempFile);
        } else {
            echo "Error: Unsupported BOM file type.";
            unlink($bomTempFile);
            unlink($bomAidTempFile);
            exit;
        }

        // Read BOM Aid file data
        if ($bomAidExtension === 'csv') {
            $bomAidData = readCsvData($bomAidTempFile);
        } elseif (in_array($bomAidExtension, ['xls', 'xlsx'])) {
            $bomAidData = readExcelData($bomAidTempFile);
        } else {
            echo "Error: Unsupported BOM Aid file type.";
            unlink($bomTempFile);
            unlink($bomAidTempFile);
            exit;
        }

        if ($bomData && $bomAidData) {
            $matches = [];
            foreach ($bomData as $bomRow) {
                foreach ($bomAidData as $bomAidRow) {
                    // Match on Maker Code (A), Product No (B), Parts Code (C), Parts Name (E)
                    if (
                        $bomRow[0] === $bomAidRow[0] &&  // Maker Code
                        $bomRow[1] === $bomAidRow[1] &&  // Product No
                        $bomRow[2] === $bomAidRow[2] &&  // Parts Code
                        $bomRow[4] === $bomAidRow[4] &&    // Parts Name
                        $bomRow[10] === $bomAidRow[9]     // Need QTY
                    ) {
                        $matches[] = $bomRow;
                        break;
                    }
                }
            }

            if (!empty($matches)) {
                $insertedRows = 0;
                foreach ($matches as $match) {
                    $maker_code = $match[0];
                    $product_no = $match[1];
                    $parts_code = $match[2];
                    $parts_name = $match[4];
                    $need_qty = $match[9];

                    $sql = "INSERT INTO m_combine (maker_code, product_no, partcode, partname, needqty) 
                    VALUES (:maker_code, :product_no, :parts_code, :parts_name, :need_qty)";
                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':maker_code', $maker_code);
                    $stmt->bindParam(':product_no', $product_no);
                    $stmt->bindParam(':parts_code', $parts_code);
                    $stmt->bindParam(':parts_name', $parts_name);
                    $stmt->bindParam(':need_qty', $need_qty);

                    if ($stmt->execute()) {
                        $insertedRows++;
                    }
                }
                // echo "Success: $insertedRows rows inserted.";
                echo 'success';
            } else {
                // echo "No matching rows found.";
                echo 'error';
            }
        } else {
            echo "Could not read one or both of the files.";
        }

        // Clean up temporary files
        unlink($bomTempFile);
        unlink($bomAidTempFile);
    } else {
        echo "Error: File upload failed for one or both files.";
    }
} else {
    echo "Please select files to upload.";
}
