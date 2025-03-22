<?php
require '../conn.php';

$method = $_POST['method'];

if ($method == 'load_dashboard') {
    $line_no = $_POST['line_no'];
    $search_key = $_POST['search_key'];
    $month = $_POST['search_by_month'];
    $current_year = isset($_POST['search_by_year']) && !empty(trim($_POST['search_by_year'])) ? trim($_POST['search_by_year']) : date('Y');

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT *, COUNT(*) OVER() AS total_count FROM m_master";
    $conditions = [];

    if (!empty($month)) {
        $start_date = $current_year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));
        $conditions[] = "created_at BETWEEN :start_date AND :end_date";
    } else {
        // Default to current month
        $current_month = date('n');
        $start_date = $current_year . '-' . str_pad($current_month, 2, '0', STR_PAD_LEFT) . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));
        $conditions[] = "created_at BETWEEN :start_date AND :end_date";
    }

    if (!empty($line_no)) {
        $conditions[] = "line_no = :line_no";
    }
    if (!empty($search_key)) {
        $conditions[] = "(partcode = :partcode OR partname = :partname)";
    }

    $conditions[] = "max_plan != '0'";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY product_no, id DESC OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);

    if (!empty($line_no)) {
        $stmt->bindParam(':line_no', $line_no);
    }
   
    if (!empty($search_key)) {
        $stmt->bindParam(':partcode', $search_key);
        $stmt->bindParam(':partname', $search_key);
    }

    $stmt->execute();
    $master = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($master) > $rowsPerPage;
    if ($has_more) {
        array_pop($master);
    }

    $totalCount = $master[0]['total_count'] ?? 0;

    $data = '';
    $division_by_zero = '';
    $c = ($page - 1) * $rowsPerPage;

    foreach ($master as $row) {
        $c++;

        $line_no = $row['line_no'];
        $partcode = $row['partcode'];
        $partname = $row['partname'];
        $min_lot = $row['min_lot'];
        $max_usage = $row['max_usage'];
        $max_plan = $row['max_plan'];
        $no_teams = $row['no_teams'];
        $issued_pd = $row['issued_pd'];

        if (empty($no_teams)) {
            $division_by_zero = " Line No: $line_no";
            continue; // Skip this iteration
        }

        // Perform calculations
        $takt_time = floor(510 / ($max_plan / $no_teams) * 60);
        $conveyor_speed = $takt_time * 0.96;
        $decimal_conveyor = $conveyor_speed - floor($conveyor_speed);
        $conveyor_speed = $decimal_conveyor <= 0.5 ? floor($conveyor_speed) : ceil($conveyor_speed);
        $usage_hour = (3600 / $conveyor_speed) * $max_usage;
        $decimal_usage = $usage_hour - floor($usage_hour);
        $usage_hour = $decimal_usage <= .51 ? floor($usage_hour) : ceil($usage_hour);

        $lead_time = $usage_hour * 5;
        $safety_inv = $usage_hour * 1;
        $kanban_qty = ceil(($lead_time + $safety_inv) / $min_lot);
        $add_reduce_kanban = $kanban_qty -  $issued_pd;
        $fill_color = ($add_reduce_kanban < 0) ? ' red-highlight' : '';

        $data .= '<tr>';
        $data .= '<td>' . $c . '</td>';
        $data .= '<td>' . $line_no . '</td>';
        $data .= '<td>' . $partcode . '</td>';
        $data .= '<td>' . $partname . '</td>';
        $data .= '<td>' . $min_lot . '</td>';
        $data .= '<td>' . $max_usage . '</td>';
        $data .= '<td>' . $max_plan . '</td>';
        $data .= '<td>' . $no_teams . '</td>';
        $data .= '<td>' . $takt_time . '</td>';
        $data .= '<td>' . $conveyor_speed . '</td>';
        $data .= '<td>' . $usage_hour . '</td>';
        $data .= '<td>' . $lead_time . '</td>';
        $data .= '<td>' . $safety_inv . '</td>';
        $data .= '<td>' . $kanban_qty . '</td>';
        $data .= '<td>' . $issued_pd . '</td>';
        $data .= '<td class="' . $fill_color . '">' . $add_reduce_kanban . '</td>';
        $data .= '<td> </td>';
        $data .= '</tr>';
    }

    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }

    echo json_encode([
        'html' => $data,
        'has_more' => $has_more,
        'total' => $totalCount,
        'err_msg' => $division_by_zero
    ]);
}
