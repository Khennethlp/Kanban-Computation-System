<?php

require '../conn.php';

$search_key = $_GET['search_key'] ?? '';
$getMonth = $_GET['month'] ?? '';
$current_year = $_GET['year'] ?? date('Y');

$delimiter = ",";
$datenow = date('Y-m-d');
$filename = "Kanban-Computation_Masterlist " . $datenow . ".csv";

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');
header('Pragma: no-cache');
header('Expires: 0');

// Create a file pointer
$f = fopen('php://memory', 'w');

// Output the UTF-8 BOM for Excel compatibility
fputs($f, "\xEF\xBB\xBF");

$fields = array('Line No', 'Product No', 'Part Code', 'Part Name', 'Min. Lot', 'Max Usage/Harness', 'Max Plan/Day(pcs)', 'No. of Teams', 'Issued to PD', 'Parts Group', 'Date');
fputcsv($f, $fields, $delimiter);

$sql = "SELECT * FROM m_master WHERE 1=1 "; // Base query

$conditions = [];
$params = [];

if (!empty($search_key)) {
    $conditions[] = "(line_no LIKE :search_line_no OR product_no LIKE :search_product_no)";
    $params[':search_line_no'] = '%' . $search_key . '%';
    $params[':search_product_no'] = '%' . $search_key . '%';
}

// Apply Month and Year filter
if (!empty($getMonth)) {
    $conditions[] = " MONTH(created_at) = :month";
    $params[':month'] = $getMonth;
}

if (!empty($current_year)) {
    $conditions[] = " YEAR(created_at) = :year";
    $params[':year'] = $current_year;
}

$conditions[] = "max_plan != '0'";
if (!empty($conditions)) {
    $sql .= " AND " . implode(" AND ", $conditions); // Apply conditions if present
}

$sql .= " ORDER BY product_no, id DESC";

// Prepare the statement
$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

foreach ($params as $key => $value) {
    $stmt->bindParam($key, $value);
}

$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    // Sanitize data to avoid line breaks or extra spaces in CSV
    foreach ($row as $key => $value) {
        $row[$key] = str_replace(["\r", "\n"], " ", $value);
    }

    // Prepare the data for CSV output
    $lineData = array(
        $row['line_no'],
        $row['product_no'],
        $row['partcode'],
        $row['partname'],
        $row['min_lot'],
        $row['max_usage'],
        $row['max_plan'],
        $row['no_teams'],
        $row['issued_pd'],
        $row['parts_group'],
        $row['created_at']
    );

    fputcsv($f, $lineData, $delimiter);
}

fseek($f, 0);

// Output the file contents
fpassthru($f);

// Close the connection
$conn = null;
exit;
