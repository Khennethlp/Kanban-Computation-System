<?php
require '../conn.php';

// ini_set('post_max_size', '100M');
// ini_set('upload_max_filesize', '100M');
// ini_set('memory_limit', '256M');
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
        if (array_filter($line)) {
            $data[] = $line;
        }
    }

    fclose($file);
    return $data;
}

if (isset($_FILES['csvFile_bom']) && isset($_FILES['csvFile_bomAid'])) {
    $userName = $_POST['userName'];
    $bom = $_FILES['csvFile_bom'];
    $bomAid = $_FILES['csvFile_bomAid'];

    if ($bom['error'] === UPLOAD_ERR_OK && $bomAid['error'] === UPLOAD_ERR_OK) {
        // Handle BOM file
        $bomData = readCsvData($bom['tmp_name']);
        if (!$bomData) {
            echo "Error: Could not read BOM file." . "</br>";
            echo "file1 error";
            exit;
        }

        // Handle BOM Aid file
        $bomAidData = readCsvData($bomAid['tmp_name']);
        if (!$bomAidData) {
            echo "Error: Could not read BOM Aid file." . "</br>";
            echo "file2 error";
            exit;
        }

        try {
            $conn->beginTransaction();

            $conn->exec("TRUNCATE TABLE m_combine");

            // Create a single temporary table for combined data
            $conn->exec("CREATE TABLE #temp_combined (
                maker_code NVARCHAR(255),
                product_no NVARCHAR(255),
                partcode NVARCHAR(255),
                partname NVARCHAR(255),
                need_qty DECIMAL(10,3),
                tube_len DECIMAL(10,3),
                wire_size NVARCHAR(255),
                wire_base_color NVARCHAR(255),
                wire_stripe_color NVARCHAR(255),
                shield_wire_code NVARCHAR(255),
                created_by NVARCHAR(255)
            )");

            // Bulk insert BOM data
            $stmt = $conn->prepare("INSERT INTO #temp_combined (maker_code, product_no, partcode, partname, need_qty, tube_len, wire_size, wire_base_color, wire_stripe_color, shield_wire_code, created_by) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($bomData as $row) {
                $stmt->execute([$row[0], $row[1], $row[2], $row[4], $row[9], $row[3], $row[5], $row[6], $row[7], $row[8], $userName]);
            }

            // Bulk insert BOM Aid data
            foreach ($bomAidData as $row) {
                $stmt->execute([$row[0], $row[1], $row[2], $row[4], $row[9], $row[3], $row[5], $row[6], $row[7], $row[8], $userName]);
            }

            // query to insert into m_combine table
            $sql = "
                INSERT INTO m_combine (maker_code, product_no, partcode, partname, need_qty, created_by)
                SELECT 
                    maker_code,
                    product_no,
                    partcode,
                    partname,
                    MAX(need_qty) AS max_need_qty,
                    created_by
                FROM #temp_combined
                WHERE 
                    (tube_len = 0 OR tube_len = 0.00) 
                    AND (wire_size IS NULL OR wire_size = '') 
                    AND (wire_base_color IS NULL OR wire_base_color = '') 
                    AND (wire_stripe_color IS NULL OR wire_stripe_color = '') 
                    AND (shield_wire_code IS NULL OR shield_wire_code = '')
                    AND partname != 'NAME'
                GROUP BY 
                    maker_code, product_no, partcode, partname, created_by;
            ";
            $conn->exec($sql);

            // Drop temporary table
            $conn->exec("DROP TABLE #temp_combined");

            $conn->commit();
            echo "success";
        } catch (Exception $e) {
            $conn->rollBack();
            echo "error";
            echo "Error: " . $e->getMessage();
        }
    } else {
        echo "Error: File upload failed for one or both files.";
        echo 'file upload';
    }
} else {
    echo "Please select files to upload.";
}
