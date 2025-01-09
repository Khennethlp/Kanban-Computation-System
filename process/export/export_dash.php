<?php
require '../conn.php';
ini_set('memory_limit', '512M');
ini_set('max_execution_time', 300); // 5 minutes

$search_key = $_GET['search_key'] ?? '';
$getLine = $_GET['line_no'] ?? '';
$month = $_GET['month'] ?? '';
$current_year = $_GET['year'] ?? date('Y');
// $current_year =  ;

$delimiter = ",";
$datenow = date('Y-m-d');
$filename = "Kanban-Computation " . $datenow . ".csv";

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');
header('Pragma: no-cache');
header('Expires: 0');

$f = fopen('php://output', 'w');

// Output the UTF-8 BOM for Excel compatibility
fputs($f, "\xEF\xBB\xBF");

// Write the CSV header
$fields = [
    'Line No',
    'Part Code',
    'Part Name',
    'Min. Lot',
    'Max Usage/Harness',
    'Max Plan/Day(pcs)',
    'No. of Teams',
    'Takt Time (secs)',
    'Conveyor Speed (secs)',
    'Usage/Hour',
    '5 hrs Lead Time',
    '1 Safety Inventory',
    '6 Req. Kanban Qty.',
    'Issued to PD',
    '(+ Add / - Reduce Kanban)',
    'Delete Kanban No.'
];
fputcsv($f, $fields, $delimiter);

// SQL Query and Conditions
$sql = "SELECT line_no, partcode, partname, min_lot, max_usage, max_plan, no_teams, issued_pd 
        FROM m_master";
$conditions = [];
$params = [];

if (!empty($search_key)) {
    $conditions[] = "(product_no LIKE :search_key_product OR partcode LIKE :search_key_partcode OR partname LIKE :search_key_partname)";
    $params[':search_key_product'] = '%' . $search_key . '%';
    $params[':search_key_partcode'] = '%' . $search_key . '%';
    $params[':search_key_partname'] = '%' . $search_key . '%';
}

if (!empty($getLine)) {
    $conditions[] = "line_no = :line_no";
    $params[':line_no'] = $getLine;
}

if (!empty($month)) {
    
    $start_date = $current_year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
    $end_date = date("Y-m-t", strtotime($start_date));
    $conditions[] = "created_at BETWEEN :start_date AND :end_date";
    $params[':start_date'] = $start_date;
    $params[':end_date'] = $end_date;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}

$stmt->execute();

// Process and write rows to the CSV
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $issued_pd = is_numeric($row['issued_pd']) && $row['issued_pd'] !== null ? $row['issued_pd'] : 0;
    $max_usage = $row['max_usage'];

    $takt_time = floor(510 / ($row['max_plan'] / $row['no_teams']) * 60);

    // $conveyor_speed = round($takt_time * 0.96);
    $conveyor_speed = $takt_time * 0.96;
    $decimal_conveyor = $conveyor_speed - floor($conveyor_speed);
    $conveyor_speed = $decimal_conveyor <= 0.5 ? floor($conveyor_speed) : ceil($conveyor_speed);

    //usage hour
    // $usage_hour = ceil((3600 / $conveyor_speed) * $row['max_usage']);
    $usage_hour = (3600 / $conveyor_speed) * $max_usage;
    $decimal_usage = $usage_hour - floor($usage_hour);
    $usage_hour = $decimal_usage <= .51 ? floor($usage_hour) : ceil($usage_hour);

    $lead_time = $usage_hour * 5;
    $safety_inv = $usage_hour * 1;
    $kanban_qty = ceil(($lead_time + $safety_inv) / $row['min_lot']);
    $add_reduce_kanban = $kanban_qty - $row['issued_pd'];

    $lineData = [
        $row['line_no'],
        $row['partcode'],
        $row['partname'],
        $row['min_lot'],
        $row['max_usage'],
        $row['max_plan'],
        $row['no_teams'],
        $takt_time,
        $conveyor_speed,
        $usage_hour,
        $lead_time,
        $safety_inv,
        $kanban_qty,
        $row['issued_pd'],
        $add_reduce_kanban
    ];

    fputcsv($f, $lineData, $delimiter);
}

// Close the output stream
fclose($f);

// Close the database connection
$conn = null;
exit;
