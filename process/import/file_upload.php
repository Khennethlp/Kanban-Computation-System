<?php

require '../../vendor/autoload.php'; // Ensure the PhpSpreadsheet library is loaded

use PhpOffice\PhpSpreadsheet\IOFactory;

// Function to read and parse CSV file
function readCsvFile($filename) {
    if (!file_exists($filename)) {
        return false;
    }

    $data = array();
    $file = fopen($filename, 'r');

    while (($line = fgetcsv($file)) !== false) {
        $data[] = $line;
    }

    fclose($file);
    return $data;
}

function readExcelFile($filename) {
    if (!file_exists($filename)) {
        return false;
    }

    // Load the spreadsheet
    $spreadsheet = IOFactory::load($filename);
    $data = [];

    foreach ($spreadsheet->getActiveSheet()->getRowIterator() as $row) {
        $rowData = [];
        foreach ($row->getCellIterator() as $cell) {
            try {
                // Try to get the formatted value first
                $formattedValue = $cell->getFormattedValue();

                // Check if the cell has a formula
                if ($cell->isFormula()) {
                    try {
                        // Try to get the calculated value if it's a formula
                        $calculatedValue = $cell->getCalculatedValue();

                        // If PhpSpreadsheet can't calculate, return the formula instead
                        if ($calculatedValue === null || $calculatedValue === '') {
                            // Fallback to show the formula as a string
                            $rowData[] = 'Formula: ' . $cell->getValue();
                        } else {
                            $rowData[] = $calculatedValue;
                        }
                    } catch (Exception $e) {
                        // Handle any errors in calculation and show formula instead
                        $rowData[] = 'Formula Error: ' . $cell->getValue();
                    }
                } elseif ($formattedValue) {
                    // If there's a formatted value, use it
                    $rowData[] = $formattedValue;
                } else {
                    // Fallback to the raw value if no formatted or calculated value is available
                    $rowData[] = $cell->getValue();
                }
            } catch (Exception $e) {
                // Handle any errors that occur
                $rowData[] = 'Error: ' . $e->getMessage();
            }
        }
        $data[] = $rowData;
    }

    return $data;
}



// Handle file upload and display as a table
if (isset($_FILES['csvFile'])) {
    $file = $_FILES['csvFile'];

    // Check if the file was uploaded
    if ($file['error'] === UPLOAD_ERR_OK) {
        // Move the uploaded file to a temporary location
        $tempFile = tempnam(sys_get_temp_dir(), 'file_upload');
        move_uploaded_file($file['tmp_name'], $tempFile);

        // Determine the file extension
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));

        // Read the data based on the file type
        if ($fileExtension === 'csv') {
            $data = readCsvFile($tempFile);
        } elseif (in_array($fileExtension, ['xls', 'xlsx'])) {
            $data = readExcelFile($tempFile);
        } else {
            echo "Error: Unsupported file type.";
            unlink($tempFile);
            exit;
        }

        // Display the data as a table
        if ($data) {
            // echo '<table class="table table-striped table-bordered">';
            
            // Generate table headers from the first row (if available)
            if (!empty($data[0])) {
                echo '<thead class="thead-dark mt-3" style="position: sticky; top: 0; z-index: 1000; background-color: #343a40;"><tr>';
                foreach ($data[0] as $header) {
                    echo '<th style="width: 100%;">' . htmlspecialchars($header) . '</th>';
                }
                echo '</tr></thead>';
            }

            echo '<tbody>';
            foreach (array_slice($data, 1) as $row) {
                echo '<tr>';
                foreach ($row as $cell) {
                    echo '<td>' . htmlspecialchars($cell) . '</td>';
                }
                echo '</tr>';
            }
            echo '</tbody></table>';
        } else {
            echo "Error: Could not read the file.";
        }

        // Delete the temporary file
        unlink($tempFile);
    } else {
        echo "Error: File upload failed.";
    }
} else {
    echo "Please select a file to upload.";
}
?>
