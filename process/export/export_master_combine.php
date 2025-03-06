<?php

require '../conn.php';

// Retrieve filter values from GET request
$search_key = $_GET['search_key'] ?? '';
$carModel = $_GET['carModel'] ?? '';
$getMonth = $_GET['month'] ?? '';
$current_year = $_GET['year'] ?? date('Y'); // Default to current year if empty

$delimiter = ",";
$datenow = date('Y-m-d');
$filename = "Kanban-Computation-Combined-" . $carModel . "-" . $datenow . ".csv";

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');
header('Pragma: no-cache');
header('Expires: 0');

// Enable error reporting
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// Create a file pointer
$f = fopen('php://memory', 'w');

// Output the UTF-8 BOM for Excel compatibility
fputs($f, "\xEF\xBB\xBF");

$fields = ['Car Model', 'Product No', 'Part Code', 'Part Name', 'Need Qty', 'Date', 'By'];
fputcsv($f, $fields, $delimiter);

// Base SQL Query
$sql = "SELECT 
            b.car_maker, 
            a.product_no, 
            a.partcode AS part_code, 
            a.partname AS part_name, 
            a.need_qty, 
            a.created_at, 
            a.created_by
        FROM m_combine a
        LEFT JOIN m_maker_code b ON a.maker_code = b.maker_code";

$conditions = [];
$params = [];

// Apply search key filter
if (!empty($search_key)) {
    $conditions[] = "(a.line_no LIKE :search_key OR a.product_no LIKE :search_key)";
    $params[':search_key'] = "%$search_key%";
}

// Apply Car Model filter
if (!empty($carModel)) {
    $conditions[] = "b.maker_code = :car_model";
    $params[':car_model'] = "$carModel";
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

// If there are conditions, add WHERE clause
if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY a.product_no ASC, a.id DESC";

// Debugging: Log the generated query
file_put_contents('debug_log.txt', $sql . "\n" . print_r($params, true));

$stmt = $conn->prepare($sql);

// Bind parameters safely
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->execute();

// Check if data is available
if ($stmt->rowCount() == 0) {
    fputcsv($f, ["No records found"], $delimiter);
} else {
    // Fetch and write CSV data
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        foreach ($row as $key => $value) {
            $row[$key] = str_replace(["\r", "\n"], " ", $value);
        }

        $data = [
            $row['car_maker'],
            $row['product_no'],
            $row['part_code'],
            $row['part_name'],
            $row['need_qty'],
            $row['created_at'],
            $row['created_by']
        ];

        fputcsv($f, $data, $delimiter);
    }
}

fseek($f, 0);
fpassthru($f);

$conn = null;
exit;
