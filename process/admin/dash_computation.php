<?php
require '../conn.php';

$method = $_POST['method'];

if ($method == 'load_dashboard') {
    $line_no = $_POST['line_no'];
    $search_date = $_POST['search_date'];

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
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    if (!empty($line_no)) {
        $stmt->bindParam(':line_no', $line_no);
    }
    if (!empty($search_date)) {
        $stmt->bindParam(':search_date', $search_date);
    }

    $stmt->execute();

    $master = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($master) {
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

            echo '<tr>';
            echo '<td>' . $line_no . '</td>';
            echo '<td>' . $partcode . '</td>';
            echo '<td>' . $partname . '</td>';
            echo '<td>' . $min_lot . '</td>';
            echo '<td>' . $max_usage . '</td>';
            echo '<td>' . $max_plan . '</td>';
            echo '<td>' . $no_teams . '</td>';
            echo '<td>' . $takt_time . '</td>'; // takt time
            echo '<td>' . $conveyor_speed . '</td>'; // conveyor speed
            echo '<td>' . $usage_hour . '</td>'; // Usage / hour
            echo '<td>' . $lead_time . '</td>'; // 5 hrs lead time
            echo '<td>' . $safety_inv . '</td>'; // 1 safety inventory
            echo '<td>' . $kanban_qty . '</td>'; // 6 Req. Kanban Qty.
            echo '<td>' . $issued_to_pd . '</td>';
            echo '<td class="' . $fill_color . '">' . $add_reduce_kanban . '</td>'; // (+add / -Reduce Kanban)
            echo '<td> </td>'; // Delete Kanban No.
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }
}
