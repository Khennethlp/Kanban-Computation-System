<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_master') {
    $user = $_POST['user_name'];
    $search_key = $_POST['search_key'];
    $search_date = $_POST['search_date'];

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

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

    $sql .= " ORDER BY id DESC OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

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
    $has_more = count($master) > $rowsPerPage;
    if ($has_more) {
        array_pop($master); // Remove the extra row used for the check
    }

    $data = '';
    $c = ($page - 1) * $rowsPerPage + 1;

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

        $data .= '<tr>';
        $data .= '<td>' . $c . '</td>';
        $data .= '<td>' . $row['line_no'] . '</td>';
        $data .= '<td>' . $row['partcode'] . '</td>';
        $data .= '<td>' . $row['partname'] . '</td>';
        $data .= '<td>' . $row['min_lot'] . '</td>';
        $data .= '<td>' . $row['max_usage'] . '</td>';
        $data .= '<td>' . $row['max_plan'] . '</td>';
        $data .= '<td>' . $row['no_teams'] . '</td>';
        $data .= '<td>' . $row['issued_to_pd'] . '</td>';
        $data .= '<td>
            <button class="btn actionBtn" data-toggle="modal" data-target="#edit_masterlist" onclick="getMaster(\'' . htmlspecialchars($id) . '~!~' . htmlspecialchars($line_no) . '~!~' . htmlspecialchars($partcode) . '~!~' . htmlspecialchars($partname) . '~!~' . htmlspecialchars($min_lot) . '~!~' . htmlspecialchars($max_usage) . '~!~' . htmlspecialchars($max_plan) . '~!~' . htmlspecialchars($no_teams) . '~!~' . htmlspecialchars($issued_to_pd) . '~!~' . htmlspecialchars($added_by) . '\');">Edit</button>
            </td>';
        $data .= '</tr>';
        $c++;
    }
    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}

if ($method == 'count_master') {
    $search_key = $_POST['search_key'];
    $search_date = $_POST['search_date'];

    $sql = "SELECT count(id) as total FROM m_master";

    $conditions = [];
    if (!empty($search_key)) {
        $conditions[] = "line_no = :line_no";
    }
    if (!empty($search_date)) {
        $conditions[] = "CAST(created_at AS DATE) = :search_date";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    if (!empty($search_key)) {
        $stmt->bindParam(':line_no', $search_key);
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


