<?php
require '../conn.php';

$userName = isset($_POST['userName']) ? $_POST['userName'] : '';

try {
    // Query to retrieve matching records
    $query = "SELECT 
                  a.product_no, 
                  b.line_no AS line_no, 
                  a.partcode, 
                  a.partname, 
                  c.min_lot AS min_lot, 
                  a.need_qty AS max_usage, 
                  b.max_plan AS maxplan_total, 
                  e.line_no AS no_teams_line,
				  e.no_teams AS no_teams, 
                  c.parts_group AS parts_group,
                  a.maker_code
              FROM 
                  m_combine a
              LEFT JOIN 
                  m_max_plan b ON a.product_no = b.product_no
              LEFT JOIN 
                  m_min_lot c ON c.partcode = a.partcode AND c.partname = a.partname
              LEFT JOIN 
                  m_no_teams e ON b.line_no = e.line_no
              WHERE 
                  b.product_no IS NOT NULL 
                  AND b.line_no != '0'
                  AND c.parts_group NOT LIKE 'b%' 
                  AND c.parts_group NOT LIKE 'q%'";

    $stmt = $conn->query($query);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo "No matching records found.";
        exit;
    }

    // Check for existing records
    $existingCheckQuery = "SELECT COUNT(*) FROM m_master WHERE product_no = ? AND line_no = ? AND partcode = ? AND partname = ?";
    $existingCheckStmt = $conn->prepare($existingCheckQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    // Prepare for batch inserts
    $placeholdersPerRow = 12;
    $maxParamsPerBatch = 2000;
    $maxRowsPerBatch = floor($maxParamsPerBatch / $placeholdersPerRow);

    $insertSql = "INSERT INTO m_master 
                  (product_no, line_no, partcode, partname, min_lot, max_usage, max_plan, no_teams, issued_pd, parts_group, created_by, maker_code) 
                  VALUES ";

    $values = [];
    $placeholders = [];
    $currentBatch = 0;

    $newRecordsInserted = false;

    foreach ($rows as $index => $row) {

        // Check if the record already exists
        $existingCheckStmt->execute([
            $row['product_no'],
            $row['line_no'],
            $row['partcode'],
            $row['partname']
        ]);

        $existingCount = $existingCheckStmt->fetchColumn();

        if ($existingCount > 0) {
            // Skip insertion if the record already exists
            continue;
        }

        $newRecordsInserted = true;
        $values[] = $row['product_no'];
        $values[] = $row['line_no'];
        $values[] = $row['partcode'];
        $values[] = $row['partname'];
        $values[] = $row['min_lot'];
        $values[] = $row['max_usage'];
        $values[] = $row['maxplan_total'];
        $values[] = $row['no_teams'];
        $values[] = null;               // issued to PD    
        $values[] = $row['parts_group'];
        $values[] = $userName;
        $values[] = $row['maker_code'];

        $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        // Insert batch if batch size is reached or it's the last row
        if ((count($placeholders) >= $maxRowsPerBatch) || ($index === count($rows) - 1)) {
            $batchQuery = $insertSql . implode(", ", $placeholders);
            $insertStmt = $conn->prepare($batchQuery);
            $insertStmt->execute($values);

            // Reset placeholders and values for the next batch
            $placeholders = [];
            $values = [];
            $currentBatch++;
        }
    }

    // echo "All data inserted successfully.";
    if ($newRecordsInserted) {
        echo 'success'; // Some new records were inserted
    } else {
        echo 'Records already generated.'; // No new records were inserted
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
