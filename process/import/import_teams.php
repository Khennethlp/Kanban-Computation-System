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

if (isset($_FILES['csvFile_teams'])) {
    $teams = $_FILES['csvFile_teams'];

    if ($teams['error'] === UPLOAD_ERR_OK) {
        $teams_data = readCsvData($teams['tmp_name']);
        if (!$teams_data) {
            echo "Error: Could not read No. of Teams file.";
            exit;
        }

        try {
            $stmt = $conn->prepare("INSERT INTO m_no_teams (line_no, no_teams) VALUES (?, ?)");

            $conn->beginTransaction();

            foreach ($teams_data as $row) {
                $line_no = $row[0];
                $no_teams = trim($row[1]);

                if ($no_teams === "-") {
                    $no_teams = 0;
                }

                if (!empty($line_no) && is_numeric($no_teams)) {
                    $stmt->execute([$line_no, $no_teams]);
                }
            }

            $conn->commit();
            // echo "Data successfully inserted into m_max_plan.";
            echo 'success';
        } catch (Exception $e) {
            $conn->rollBack();
            echo "Error: " . $e->getMessage();
            echo 'error';
        }
    } else {
        echo "Error: File upload failed.";
    }
} else {
    echo "Please select a file to upload.";
}
