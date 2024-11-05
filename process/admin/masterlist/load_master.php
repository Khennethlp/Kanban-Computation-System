<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_master') {
    $user = $_POST['user_name'];
    $search_key = $_POST['search_key'];
    $search_date = $_POST['search_date'];

    $sql = "SELECT * FROM m_master";

    $conditions = [];
    if (!empty($search_key)) {
        $conditions[] = "(line_no LIKE :search_key OR partcode LIKE :search_key_partcode OR partname LIKE :search_key_partname)";
    }

    if (!empty($search_date)) {
        $conditions[] = "CAST(created_at AS DATE) = :search_date";
    }

    // $conditions[] = "added_by = :user";

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY id DESC";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    if (!empty($search_key)) {
        $search_keys = "%$search_key%";
        $stmt->bindParam(':search_key', $search_keys);
        $stmt->bindParam(':search_key_partcode', $search_keys);
        $stmt->bindParam(':search_key_partname', $search_keys);
    }

    if (!empty($search_date)) {
        $stmt->bindParam(':search_date', $search_date);
    }
    // $stmt->bindParam(':user', $user);
    $stmt->execute();
    $master = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // $c = 0;
    if ($master) {
        foreach ($master as $row) {
            // $c++;
            $id = $row['id'];
            $added_by = $row['added_by'];
            $line_no = $row['line_no'];
            $partcode = $row['partcode'];
            $partname = $row['partname'];
            $min_lot = $row['min_lot'];
            $max_usage = $row['max_usage'];
            $max_plan = $row['max_plan'];
            $no_teams = $row['no_teams'];
            $issued_to_pd = $row['issued_to_pd'];

            echo '<tr>';
            // echo '<td>' . $c . '</td>';
            echo '<td>' . $row['line_no'] . '</td>';
            echo '<td>' . $row['partcode'] . '</td>';
            echo '<td>' . $row['partname'] . '</td>';
            echo '<td>' . $row['min_lot'] . '</td>';
            echo '<td>' . $row['max_usage'] . '</td>';
            echo '<td>' . $row['max_plan'] . '</td>';
            echo '<td>' . $row['no_teams'] . '</td>';
            echo '<td>' . $row['issued_to_pd'] . '</td>';
            echo '<td>
            <button class="btn actionBtn" data-toggle="modal" data-target="#edit_masterlist" onclick="getMaster(\'' . htmlspecialchars($id) . '~!~' . htmlspecialchars($line_no) . '~!~' . htmlspecialchars($partcode) . '~!~' . htmlspecialchars($partname) . '~!~' . htmlspecialchars($min_lot) . '~!~' . htmlspecialchars($max_usage) . '~!~' . htmlspecialchars($max_plan) . '~!~' . htmlspecialchars($no_teams) . '~!~' . htmlspecialchars($issued_to_pd) . '~!~' . htmlspecialchars($added_by) . '\');">Edit</button>
            </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }
}
