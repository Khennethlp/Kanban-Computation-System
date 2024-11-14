<?php
session_start();
require '../conn.php';

ini_set('memory_limit', '4096M');
ini_set('post_max_size', '2000M');
ini_set('upload_max_filesize', '2000M');
set_time_limit(0); // Unlimited time to process large files

if (isset($_FILES['csvFile_bomAid'])) {
    $csvMimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');

    if (!empty($_FILES['csvFile_bomAid']['name']) && in_array($_FILES['csvFile_bomAid']['type'], $csvMimes)) {
        if (is_uploaded_file($_FILES['csvFile_bomAid']['tmp_name'])) {
            $csvFile = fopen($_FILES['csvFile_bomAid']['tmp_name'], 'r');
            if (!$csvFile) {
                die("Error opening file");
            }

            fgetcsv($csvFile); // Skip header row

            $batch = [];
            $rowsInserted = 0;
            $batchSize = 1000; // Number of rows per batch

            while (($line = fgetcsv($csvFile)) !== false) {
                if (empty(array_filter($line))) {
                    continue;
                }

                $batch[] = [
                    'maker_code' => $line[0],
                    'product_no' => $line[1],
                    'parts_code' => $line[2],
                    'parts_name' => $line[3],
                    'need_qty' => $line[4]
                ];

                // Once batch size is reached, perform the insert
                if (count($batch) >= $batchSize) {
                    insertBatch($conn, $batch);
                    $rowsInserted += count($batch);
                    $batch = []; // Clear batch
                }
            }

            // Insert remaining records
            if (count($batch) > 0) {
                insertBatch($conn, $batch);
                $rowsInserted += count($batch);
            }

            fclose($csvFile);

            // Return success with the number of rows inserted
            echo json_encode(['status' => 'success', 'rowsInserted' => $rowsInserted]);
        }
    }
}

/**
 * Function to insert batch into the database
 */
function insertBatch($conn, $batch) {
    $sql = "INSERT INTO m_bomAid (maker_code, product_no, partcode, partname, need_qty) VALUES ";
    $values = [];
    $params = [];

    // Build placeholders and parameters for batch insert
    foreach ($batch as $index => $row) {
        $values[] = "(:maker_code{$index}, :product_no{$index}, :parts_code{$index}, :parts_name{$index}, :need_qty{$index})";
        $params["maker_code{$index}"] = $row['maker_code'];
        $params["product_no{$index}"] = $row['product_no'];
        $params["parts_code{$index}"] = $row['parts_code'];
        $params["parts_name{$index}"] = $row['parts_name'];
        $params["need_qty{$index}"] = $row['need_qty'];
    }

    $sql .= implode(", ", $values);
    $stmt = $conn->prepare($sql);

    // Bind parameters and execute batch insert
    foreach ($params as $param => $value) {
        $stmt->bindValue(":$param", $value);
    }
    $stmt->execute();
}
?>
