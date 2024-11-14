<?php
require '../conn.php';

ini_set('memory_limit', '4096M');
ini_set('post_max_size', '2000M');
ini_set('upload_max_filesize', '2000M');
set_time_limit(0); // Unlimited time to process large files

// Function to read CSV data
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

// Check if files are uploaded
if (isset($_FILES['csvFile_bom']) && isset($_FILES['csvFile_bomAid'])) {
    $userName = $_POST['userName'];
    $bom = $_FILES['csvFile_bom'];
    $bomAid = $_FILES['csvFile_bomAid'];

    // Check if both files were uploaded successfully
    if ($bom['error'] === UPLOAD_ERR_OK && $bomAid['error'] === UPLOAD_ERR_OK) {
        // Handle BOM file
        $bomTempFile = tempnam(sys_get_temp_dir(), 'bom_upload');
        move_uploaded_file($bom['tmp_name'], $bomTempFile);

        // Handle BOM Aid file
        $bomAidTempFile = tempnam(sys_get_temp_dir(), 'bomAid_upload');
        move_uploaded_file($bomAid['tmp_name'], $bomAidTempFile);

        // Read BOM file data
        $bomData = readCsvData($bomTempFile);
        if (!$bomData) {
            echo "Error: Could not read BOM file.";
            unlink($bomTempFile);
            unlink($bomAidTempFile);
            exit;
        }

        // Read BOM Aid file data
        $bomAidData = readCsvData($bomAidTempFile);
        if (!$bomAidData) {
            echo "Error: Could not read BOM Aid file.";
            unlink($bomTempFile);
            unlink($bomAidTempFile);
            exit;
        }

        // Perform matching and insert data into the database
        $matches = [];
        foreach ($bomData as $bomRow) {
            foreach ($bomAidData as $bomAidRow) {
                // Match on Product No (B), Parts Code (C), Parts Name (E)
                if (
                    $bomRow[1] === $bomAidRow[1] &&  // Product No
                    $bomRow[2] === $bomAidRow[2] &&  // Parts Code
                    $bomRow[4] === $bomAidRow[4]     // Parts Name
                ) {
                    // Check if Column D (index 3) in BOM is 0
                    if ($bomRow[3] == 0) {
                        // Insert matched row where Column D is 0
                        $maker_code = $bomRow[0];
                        $product_no = $bomRow[1];
                        $parts_code = $bomRow[2];
                        $parts_name = $bomRow[4];
                        $need_qty = $bomAidRow[9]; // Assuming Need QTY is in the 10th column (index 9)

                        // Insert into the database
                        $sql = "INSERT INTO m_combine (maker_code, product_no, partcode, partname, need_qty) 
                        VALUES (:maker_code, :product_no, :parts_code, :parts_name, :need_qty)";
                        $stmt = $conn->prepare($sql);
                        $stmt->bindParam(':maker_code', $maker_code);
                        $stmt->bindParam(':product_no', $product_no);
                        $stmt->bindParam(':parts_code', $parts_code);
                        $stmt->bindParam(':parts_name', $parts_name);
                        $stmt->bindParam(':need_qty', $need_qty);

                        if ($stmt->execute()) {
                            $matches[] = $bomRow; // Add to matches array if inserted successfully
                            break; // Break after inserting the first match to avoid duplicates
                        }
                    }
                }
            }
        }

        if (!empty($matches)) {
            echo 'success'; // Return success if matches are found and inserted
        } else {
            echo 'no_matches'; // Return if no matches were found
        }

        // Clean up temporary files
        unlink($bomTempFile);
        unlink($bomAidTempFile);
    } else {
        echo "Error: File upload failed for one or both files.";
    }
} else {
    echo "Please select files to upload.";
}
?>
