<?php

require '../conn.php';

$getLine = $_GET['line_no'] ?? '';
$getDate = $_GET['date'] ?? '';

$delimiter = ",";
$datenow = date('Y-m-d');
$filename = "Kanban-Computation " . $datenow . ".csv";

// Create a file pointer
$f = fopen('php://memory', 'w');

// Output the UTF-8 BOM for Excel compatibility
fputs($f, "\xEF\xBB\xBF");

$fields = array('Line No', 'Part Code', 'Part Name', 'Min. Lot', 'Max Usage/Harness', 'Max Plan/Day(pcs)', 'No. of Teams', 'Takt Time (secs)', 'Conveyor Speed (secs)', 'Usage/Hour', '5 hrs Lead Time', '1 Safety Inventory', '6 Req. Kanban Qty.', 'Issued to PD', '(+ Add / - Reduce Kanban)', 'Delete Kanban No.');
fputcsv($f, $fields, $delimiter);

$sql = "SELECT * FROM m_master ";

$conditions = [];
if (!empty($getLine)) {
    $conditions[] = "line_no = :line_no";
}
if (!empty($getDate)) {
    $conditions[] = "CAST(created_at AS DATE) = :search_date";
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

if (!empty($getLine)) {
    $stmt->bindParam(':line_no', $getLine);
}
if (!empty($getDate)) {
    $stmt->bindParam(':search_date', $getDate);
}

$stmt->execute();

while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

    foreach ($row as $key => $value) {
        $row[$key] = str_replace(["\r", "\n"], " ", $value);
    }

    $line_no = $row['line_no'];
    $partcode = $row['partcode'];
    $partname = $row['partname'];
    $min_lot = $row['min_lot'];
    $max_usage = $row['max_usage'];
    $max_plan = $row['max_plan'];
    $no_teams = $row['no_teams'];
    $issued_to_pd = $row['issued_to_pd'];

    $takt_time = floor(510 / ($max_plan / $no_teams) * 60);

    $conveyor_speed = $takt_time * 0.96;
    $decimal_conveyor = $conveyor_speed - floor($conveyor_speed);

    if ($decimal_conveyor <= 0.5) {
        // Round down if decimal part is .36 or below
        $conveyor_speed = floor($conveyor_speed);
    } else {
        // Round up if decimal part is .52 or above
        $conveyor_speed = ceil($conveyor_speed);
    }

    $usage_hour = (3600 / $conveyor_speed) * $max_usage;
    $decimal_usage = $usage_hour - floor($usage_hour);

    if ($decimal_usage <= .51) {
        // Round down if decimal part is .51 or below
        $usage_hour = floor($usage_hour);
    } else {
        // Round up if decimal part is .52 or above
        $usage_hour = ceil($usage_hour);
    }

    $lead_time = $usage_hour * 5;
    $safety_inv = $usage_hour * 1;
    $kanban_qty = ceil(($lead_time + $safety_inv) / $min_lot);
    $add_reduce_kanban = $kanban_qty -  $issued_to_pd;

    $lineData = array(
        $line_no,
        $partcode,
        $partname,
        $min_lot,
        $max_usage,
        $max_plan,
        $no_teams,
        $takt_time,
        $conveyor_speed,
        $usage_hour,
        $lead_time,
        $safety_inv,
        $kanban_qty,
        $issued_to_pd,
        $add_reduce_kanban
    );
    fputcsv($f, $lineData, $delimiter);
}

fseek($f, 0);

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $filename . '";');
header('Pragma: no-cache');
header('Expires: 0');

// Output all remaining data on a file pointer
fpassthru($f);

// Close the connection
$conn = null;
exit;
?>