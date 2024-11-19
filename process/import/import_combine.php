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
        if (array_filter($line)) {
            $data[] = $line;
        }
    }

    fclose($file);
    return $data;
}

if (isset($_FILES['csvFile_bom']) && isset($_FILES['csvFile_bomAid'])) {
    $bom = $_FILES['csvFile_bom'];
    $bomAid = $_FILES['csvFile_bomAid'];

    if ($bom['error'] === UPLOAD_ERR_OK && $bomAid['error'] === UPLOAD_ERR_OK) {
        // Handle BOM file
        $bomData = readCsvData($bom['tmp_name']);
        if (!$bomData) {
            echo "Error: Could not read BOM file.";
            echo "file1 error";
            exit;
        }
        
        // Handle BOM Aid file
        $bomAidData = readCsvData($bomAid['tmp_name']);
        if (!$bomAidData) {
            echo "Error: Could not read BOM Aid file.";
            echo "file2 error";
            exit;
        }

        try {
            $conn->beginTransaction();

            // Create temporary tables
            $conn->exec("CREATE TABLE #temp_bom (
                maker_code NVARCHAR(255),
                product_no NVARCHAR(255),
                partcode NVARCHAR(255),
                tube_len DECIMAL(10, 2),
                partname NVARCHAR(255)
            )");

            $conn->exec("CREATE TABLE #temp_bom_aid (
                maker_code NVARCHAR(255),
                product_no NVARCHAR(255),
                partcode NVARCHAR(255),
                tube_len DECIMAL(10, 2),
                partname NVARCHAR(255),
                need_qty numeric(2,3)
            )");

            // Bulk insert BOM data
            $stmt = $conn->prepare("INSERT INTO #temp_bom (maker_code, product_no, partcode, tube_len, partname) VALUES (?, ?, ?, ?, ?)");
            foreach ($bomData as $row) {
                $stmt->execute([$row[0], $row[1], $row[2], $row[3], $row[4]]);
            }

            // Bulk insert BOM Aid data
            $stmt = $conn->prepare("INSERT INTO #temp_bom_aid (maker_code, product_no, partcode, tube_len, partname, need_qty) VALUES (?, ?, ?, ?, ?, ?)");
            foreach ($bomAidData as $row) {
                $stmt->execute([$row[0], $row[1], $row[2], $row[3], $row[4], $row[9]]);
            }

            // Perform matching in the database
            $sql = "
                INSERT INTO m_combine (maker_code, product_no, partcode, partname, need_qty)
                SELECT DISTINCT
                    bom.maker_code,
                    bom.product_no,
                    bom.partcode,
                    bom.partname,
                    aid.need_qty
                FROM #temp_bom bom
                INNER JOIN #temp_bom_aid aid
                ON bom.product_no = aid.product_no
                AND bom.partcode = aid.partcode
                AND bom.partname = aid.partname
                WHERE (bom.tube_len = 0 OR bom.tube_len = 0.00)
                AND (aid.tube_len = 0 OR aid.tube_len = 0.00);
            ";
            $conn->exec($sql);

            // Drop temporary tables
            $conn->exec("DROP TABLE #temp_bom");
            $conn->exec("DROP TABLE #temp_bom_aid");

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
?>
