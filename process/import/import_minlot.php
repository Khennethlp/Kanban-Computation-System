<?php
require '../conn.php';

ini_set('memory_limit', '4096M');
ini_set('post_max_size', '2000M');
ini_set('upload_max_filesize', '2000M');
set_time_limit(0); // Unlimited time to process large files

function readCsvData($filename)
{
    if (!file_exists($filename)) {
        return false;
    }

    $data = [];
    $file = fopen($filename, 'r');
    fgetcsv($file); // Skip the header row

    while (($line = fgetcsv($file)) !== false) {
        if (array_filter($line)) { // Ensure the row is not empty
            $data[] = $line;
        }
    }

    fclose($file);
    return $data;
}

if (isset($_FILES['csvFile_minlot'])) {
    $userName = $_POST['userName'];
    $minlot = $_FILES['csvFile_minlot'];

    if ($minlot['error'] === UPLOAD_ERR_OK) {
        $minlot_data = readCsvData($minlot['tmp_name']);
        if (!$minlot_data) {
            echo "Error: Could not read Min Lot file.";
            exit;
        }

        try {
            $stmt = $conn->prepare("INSERT INTO m_min_lot (partcode, partname, min_lot, parts_group, created_by) VALUES (?, ?, ?, ?, ?)");

            $conn->beginTransaction();

            foreach ($minlot_data as $row) {
                $parts_group = $row[0];
                $partname = $row[1];
                $partcode = $row[2];
                $min_lot_qty = $row[3];

                if (!empty($partname) && !empty($partcode) && !empty($min_lot_qty)) {
                    $stmt->execute([$partname, $partcode, $min_lot_qty, $parts_group, $userName]);
                }
            }

            $conn->commit();
            // echo "Data successfully inserted into m_max_plan.";
            echo 'success';
        } catch (Exception $e) {
            $conn->rollBack();
            // echo "Error: " . $e->getMessage();
            echo 'error';
        }
    } else {
        echo "Error: File upload failed.";
    }
} else {
    echo "Please select a file to upload.";
}
