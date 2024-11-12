<?php
require '../conn.php';

$method = $_POST['method'];

if ($method == 'load_dashboard') {
    $line_no = $_POST['line_no'];
    $search_date = $_POST['search_date'];

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT * FROM m_master ";

    $conditions = [];
    if (!empty($line_no)) {
        $conditions[] = "line_no = :line_no";
    }
    if (!empty($search_date)) {
        $conditions[] = "CAST(created_at AS DATE) = :search_date";
    }

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

    $stmt->execute();

    $master = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($master) > $rowsPerPage;
    if ($has_more) {
        array_pop($master); // Remove the extra row used for the check
    }

    $data = '';
    // $c = $offset + 1;
    $c = ($page - 1) * $rowsPerPage + 1;


    foreach ($master as $row) {
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

    $sql = "SELECT count(id) as total FROM m_master";

    $conditions = [];
    if (!empty($line_no)) {
        $conditions[] = "line_no = :line_no";
    }
    if (!empty($search_date)) {
        $conditions[] = "CAST(created_at AS DATE) = :search_date";
    }

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

            echo 'Total: ' . $count;
        }
    } else {
        echo 'Total: 0';
    }
}
