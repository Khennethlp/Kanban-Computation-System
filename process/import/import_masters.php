<?php
require '../conn.php';

ini_set('memory_limit', '4096M');
ini_set('post_max_size', '2000M');
ini_set('upload_max_filesize', '2000M');
set_time_limit(0); // Unlimited time to process large files
ini_set('display_errors', 0);

header('Content-Type: text/plain'); // Output as plain text

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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userName = htmlspecialchars(trim($_POST['userName']));
    $maxplan = $_FILES['csvFile_maxplan'];
    $minlot = $_FILES['csvFile_minlot'];
    $teams = $_FILES['csvFile_teams'];
    $kanban = $_FILES['csvFile_kanban'];

    $allowedMimeType = 'text/plain';
    if (
        mime_content_type($maxplan['tmp_name']) !== $allowedMimeType ||
        mime_content_type($minlot['tmp_name']) !== $allowedMimeType ||
        mime_content_type($teams['tmp_name']) !== $allowedMimeType ||
        mime_content_type($kanban['tmp_name']) !== $allowedMimeType
    ) {
        echo "error:Invalid file type. Only CSV files are allowed.";
        exit;
    }

    $maxplan_data = readCsvData($maxplan['tmp_name']);
    $minlot_data = readCsvData($minlot['tmp_name']);
    $teams_data = readCsvData($teams['tmp_name']);
    $kanban_data = readCsvData($kanban['tmp_name']);

    if (!$maxplan_data || !$minlot_data || !$teams_data || !$kanban_data) {
        echo "error:Error reading uploaded files.";
        exit;
    }

    try {
        $conn->beginTransaction();

        if (!empty($maxplan_data)) {
            $conn->exec("TRUNCATE TABLE m_max_plan");
        } else if (!empty($minlot_data)) {
            $conn->exec("TRUNCATE TABLE m_min_lot");
        } else if (!empty($teams_data)) {
            $conn->exec("TRUNCATE TABLE m_no_teams");
        } else if (!empty($kanban_data)) {
            $conn->exec("TRUNCATE TABLE kanban_master");
        } else {
            echo "error:No data found in uploaded files.";
            exit;
        }

        // Insert max plan
        $stmt_maxplan = $conn->prepare("INSERT INTO m_max_plan (product_no, line_no, max_plan, created_by) VALUES (?, ?, ?, ?)");
        foreach ($maxplan_data as $row) {
            $product_no = $row[0];
            $line_no = $row[1];
            $max_plan = $row[2];

            if (is_numeric($max_plan)) {
                $stmt_maxplan->execute([$product_no, $line_no, $max_plan, $userName]);
            }
        }

        // Insert min lot
        $stmt_minlot = $conn->prepare("INSERT INTO m_min_lot (partcode, partname, min_lot, parts_group, created_by) VALUES (?, ?, ?, ?, ?)");
        foreach ($minlot_data as $row) {
            $parts_group = $row[0];
            $partname = $row[1];
            $partcode = $row[2];
            $min_lot_qty = $row[3];

            if (!empty($partname) && !empty($partcode) && !empty($min_lot_qty)) {
                $stmt_minlot->execute([$partname, $partcode, $min_lot_qty, $parts_group, $userName]);
            }
        }

        // Insert teams
        $stmt_teams = $conn->prepare("INSERT INTO m_no_teams (line_no, no_teams, created_by) VALUES (?, ?, ?)");
        foreach ($teams_data as $row) {
            $line_no = $row[0];
            $no_teams = trim($row[1]) === '-' ? 0 : trim($row[1]);

            if (!empty($line_no) && is_numeric($no_teams)) {
                $stmt_teams->execute([$line_no, $no_teams, $userName]);
            }
        }

        // Insert kanban
        $stmt_kanban = $conn->prepare("INSERT INTO kanban_master (line_no, partcode, partname, added_by) VALUES (?, ?, ?, ?)");
        foreach ($kanban_data as $row) {
            $line_no = substr($row[4], 0, 4);
            $partcode = $row[5];
            $partname = $row[6];

            $stmt_kanban->execute([$line_no, $partcode, $partname, $userName]);
        }

        $conn->commit();
        echo "success:File uploaded and data inserted successfully.";
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error during CSV upload: " . $e->getMessage());
        echo "error:An error occurred during processing.";
    }
} else {
    echo "error:Invalid request method.";
}
