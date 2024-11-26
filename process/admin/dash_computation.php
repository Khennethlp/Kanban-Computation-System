<?php
require '../conn.php';

$method = $_POST['method'];

if ($method == 'load_dashboard') {
    $line_no = $_POST['line_no'];
    $search_date = $_POST['search_date'];

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT a.*, b.partcode AS partcode, b.partname AS partname, b.min_lot AS min_lot, c.product_no AS product_no, c.max_plan AS max_plan, c.line_no AS line_no, d.no_teams AS no_teams
            FROM m_combine a
            LEFT JOIN m_min_lot b ON a.partcode = b.partcode AND a.partname = b.partname
            LEFT JOIN m_max_plan c ON a.product_no = c.product_no
            LEFT JOIN m_no_teams d ON c.line_no = d.line_no
         
            ";

    $conditions = [];
    if (!empty($line_no)) {
        $conditions[] = "d.line_no = :line_no";
    }
    if (!empty($search_date)) {
        $conditions[] = "CAST(a.created_at AS DATE) = :search_date";
    }
    $conditions[] = "MONTH(a.created_at) = :current_month";

    $conditions[] = "c.line_no IS NOT NULL AND c.product_no IS NOT NULL AND d.no_teams IS NOT NULL AND c.max_plan != '0' AND b.partcode IS NOT NULL";
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY id DESC OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    if (!empty($line_no)) {
        $stmt->bindParam(':line_no', $line_no);
    }
    if (!empty($search_date)) {
        $stmt->bindParam(':search_date', $search_date);
    }

    //$current_month = '11';
    $current_month = date('n');
    $stmt->bindParam(':current_month', $current_month, PDO::PARAM_INT);

    $stmt->execute();

    $master = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($master) > $rowsPerPage;
    if ($has_more) {
        array_pop($master);
    }

    $data = '';
    // $c = $offset + 1;
    $c = ($page - 1) * $rowsPerPage + 1;


    foreach ($master as $row) {
        $line_no = $row['line_no'];
        $partcode = $row['partcode'];
        $partname = $row['partname'];
        $min_lot = $row['min_lot'];
        // $max_usage = $row['max_usage'];
        $max_usage = 1;
        $max_plan = $row['max_plan'];
        $no_teams = $row['no_teams'];
        // $issued_to_pd = $row['issued_to_pd'];
        $issued_to_pd = 2;

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
        // $kanban_qty = 0;
        $add_reduce_kanban = $kanban_qty -  $issued_to_pd;

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
        $data .= '<td>' . $takt_time . '</td>'; // takt time
        $data .= '<td>' . $conveyor_speed . '</td>'; // conveyor speed
        $data .= '<td>' . $usage_hour . '</td>'; // Usage / hour
        $data .= '<td>' . $lead_time . '</td>'; // 5 hrs lead time
        $data .= '<td>' . $safety_inv . '</td>'; // 1 safety inventory
        $data .= '<td>' . $kanban_qty . '</td>'; // 6 Req. Kanban Qty.
        $data .= '<td>' . $issued_to_pd . '</td>';
        $data .= '<td class="' . $fill_color . '">' . $add_reduce_kanban . '</td>'; // (+add / -Reduce Kanban)
        $data .= '<td> </td>'; // Delete Kanban No.
        $data .= '</tr>';
        $c++;
    }

    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}

if ($method == 'count_dash') {
    $line_no = $_POST['line_no'];
    $search_date = $_POST['search_date'];

    $sql = "SELECT count(*) as total 
     FROM m_combine a
            LEFT JOIN m_min_lot b ON a.partcode = b.partcode AND a.partname = b.partname
            LEFT JOIN m_max_plan c ON a.product_no = c.product_no
            LEFT JOIN m_no_teams d ON c.line_no = d.line_no
            
            ";

    $conditions = [];
    if (!empty($line_no)) {
        $conditions[] = "c.line_no = :line_no";
    }
    if (!empty($search_date)) {
        $conditions[] = "CAST(created_at AS DATE) = :search_date";
    }

    $conditions[] = "c.line_no IS NOT NULL AND c.product_no IS NOT NULL AND d.no_teams IS NOT NULL AND c.max_plan != '0' AND b.partcode IS NOT NULL";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    if (!empty($line_no)) {
        $stmt->bindParam(':line_no', $line_no);
    }
    if (!empty($search_date)) {
        $stmt->bindParam(':search_date', $search_date);
    }

    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $c) {
            $count = $c['total'];

            echo 'Results: ' . $count;
        }
    } else {
        echo 'Results: 0';
    }
}
