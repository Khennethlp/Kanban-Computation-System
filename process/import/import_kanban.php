<?php
require '../conn.php';
require '../../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

function readCsvData($filename)
{
    if (!file_exists($filename)) {
        return false;
    }

    $data = [];
    $file = fopen($filename, 'r');
    fgetcsv($file);

    while (($line = fgetcsv($file)) !== false) {
        if (array_filter($line)) {
            $data[] = $line;
        }
    }

    fclose($file);
    return $data;
}

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

if (isset($_FILES['csvFile_kanban'])) {
    $userName = $_POST['userName'];
    $file = $_FILES['csvFile_kanban'];

    if ($file['error'] === UPLOAD_ERR_OK) {
        $tempFile = tempnam(sys_get_temp_dir(), 'file_upload');
        move_uploaded_file($file['tmp_name'], $tempFile);

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Read the data based on file type
        if ($fileExtension === 'csv') {
            $data = readCsvData($tempFile);
        } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
            $data = readExcelData($tempFile);
        } else {
            echo "Error: Unsupported file type.";
            unlink($tempFile);
            exit;
        }

        if ($data) {
            $insertedRows = 0;
            $errorMessages = [];

            foreach ($data as $row) {
                $line_no = $row[4];
                $partcode = $row[5];
                $partname = $row[6];

                // $checkSql = "SELECT COUNT(*) FROM kanban_master WHERE partcode = ? AND partname = ?";
                // $checkStmt = $conn->prepare($checkSql);
                // $checkStmt->execute([$partcode, $partname]);
                // $exists = $checkStmt->fetchColumn();

                // if ($exists == 0) { // If not exists, insert
                    $sql = "INSERT INTO kanban_master (line_no, partcode, partname, added_by)
                            VALUES (?, ?, ?, ?)";
                    $stmt = $conn->prepare($sql);
                    $stmt->execute([$line_no, $partcode, $partname, $userName]);
                    $insertedRows++;
                // } else {
                //     echo 'exist '; //data with partcode and partname already exist in the database
                // }
            }

            if ($insertedRows > 0) {
                echo "success";
            }
            if (!empty($errorMessages)) {
                echo 'error';
            }
        } else {
            echo "Could not read the file.";
        }

        unlink($tempFile);
    } else {
        echo "Error: File upload failed.";
    }
} else {
    echo "Please select a file to upload.";
}