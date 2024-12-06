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
                GROUP BY 
                    maker_code, product_no, partcode, partname, created_by;
            ";
            $conn->exec($sql);

            // query to get the data from joined table and insert into database
            $query = "SELECT a.id, a.maker_code, a.partcode, a.partname, a.need_qty, a.created_by,
                b.partcode AS b_partcode, b.partname AS b_partname, b.min_lot, b.parts_group,
                c.product_no, c.max_plan, c.maxPlan_total, c.line_no, d.no_teams, 
                COUNT(*) OVER() AS total_count, e.masterlist_count
            FROM 
                m_combine a
            LEFT JOIN 
                m_min_lot b ON a.partcode = b.partcode AND a.partname = b.partname
            LEFT JOIN (
                SELECT line_no, max_plan, 
                       SUM(max_plan) OVER (PARTITION BY line_no) AS maxPlan_total, 
                       product_no 
                FROM m_max_plan
            ) c ON a.product_no = c.product_no
            LEFT JOIN 
                m_no_teams d ON c.line_no = d.line_no
            LEFT JOIN (
                SELECT partscode, partsname, 
                       LEFT(line_number, PATINDEX('%[^0-9]%', line_number + 'X') - 1) AS numeric_line_number,
                       COUNT(*) AS masterlist_count
                FROM [new_ekanban].[dbo].[mm_masterlist]
                GROUP BY partscode, partsname, LEFT(line_number, PATINDEX('%[^0-9]%', line_number + 'X') - 1)
            ) e ON a.partcode = e.partscode 
                 AND a.partname = e.partsname 
                 AND e.numeric_line_number = CAST(c.line_no AS VARCHAR)
            WHERE 
                b.parts_group NOT LIKE 'B%' AND b.parts_group NOT LIKE 'Q%' 
                AND c.line_no IS NOT NULL AND c.product_no IS NOT NULL 
                AND d.no_teams IS NOT NULL AND c.max_plan != '0' 
                AND b.partcode IS NOT NULL
            GROUP BY 
                a.id, a.maker_code, a.partcode, a.partname, a.need_qty, a.created_by,
                b.partcode, b.partname, b.parts_group, b.min_lot, 
                c.product_no, c.max_plan, c.maxPlan_total, 
                c.line_no, d.no_teams, e.masterlist_count
            ORDER BY 
                c.product_no, a.id DESC
            ";

            $stmt = $conn->query($query);
            $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Insert processed rows into m_master
            $insertSql = "INSERT INTO m_master (product_no, line_no, partcode, partname, min_lot, max_usage, max_plan, no_teams, issued_pd, parts_group, created_by, maker_code)
            VALUES (:product_no, :line_no, :partcode, :partname, :min_lot, :max_usage, :max_plan, :no_teams, :issued_pd, :partsgroup, :created_by, :maker_code)";
            $insertStmt = $conn->prepare($insertSql);

            foreach ($rows as $row) {
                $checkSql = "SELECT COUNT(*) FROM m_master WHERE partcode = :partcode AND partname = :partname AND line_no = :line_no";
                $checkStmt = $conn->prepare($checkSql);
                $checkStmt->bindParam(':partcode', $row['partcode']);
                $checkStmt->bindParam(':partname', $row['partname']);
                $checkStmt->bindParam(':line_no', $row['line_no']);
                $checkStmt->execute();

                $existingRow = $checkStmt->fetchColumn();

                // Only insert if the row does not already exist
                if ($existingRow == 0) {
                    $insertStmt->bindParam(':product_no', $row['product_no']);
                    $insertStmt->bindParam(':line_no', $row['line_no']);
                    $insertStmt->bindParam(':partcode', $row['partcode']);
                    $insertStmt->bindParam(':partname', $row['partname']);
                    $insertStmt->bindParam(':min_lot', $row['min_lot']);
                    $insertStmt->bindParam(':max_usage', $row['need_qty']);
                    $insertStmt->bindParam(':max_plan', $row['maxplan_total']);
                    $insertStmt->bindParam(':no_teams', $row['no_teams']);
                    $insertStmt->bindParam(':issued_pd', $row['masterlist_count']);
                    $insertStmt->bindParam(':partsgroup', $row['parts_group']);
                    $insertStmt->bindParam(':created_by', $userName);
                    $insertStmt->bindParam(':maker_code', $row['maker_code']);
                    $insertStmt->execute();
                }
            }

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
