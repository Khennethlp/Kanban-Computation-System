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

if (isset($_FILES['csvFile_maxplan'])) {
    $maxplan = $_FILES['csvFile_maxplan'];

    if ($maxplan['error'] === UPLOAD_ERR_OK) {
        $maxplan_data = readCsvData($maxplan['tmp_name']);
        if (!$maxplan_data) {
            echo "Error: Could not read Max Plan file.";
            exit;
        }

        try {
            $stmt = $conn->prepare("INSERT INTO m_max_plan (maker_code, max_plan) VALUES (?, ?)");

            $conn->beginTransaction();

            foreach ($maxplan_data as $row) {
                $maker_code = $row[0];
                $max_plan = $row[1];

                if (is_numeric($max_plan)) {
                    $stmt->execute([$maker_code, $max_plan]);
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