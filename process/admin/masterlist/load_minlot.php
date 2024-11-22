<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_minlot') {

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT * FROM m_min_lot";
    $sql .= " ORDER BY id OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    $minlot = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($minlot) > $rowsPerPage;
    if ($has_more) {
        array_pop($minlot);
    }

    $data = '';
    $c = ($page - 1) * $rowsPerPage + 1;

    foreach ($minlot as $row) {
        $c++;
        $data .= '<tr>';
        $data .= '<td>' . $c . '</td>';
        $data .= '<td>' . $row['partcode'] . '</td>';
        $data .= '<td>' . $row['partname'] . '</td>';
        $data .= '<td>' . $row['min_lot'] . '</td>';
        $data .= '<td>
            <button class="btn actionBtn" data-toggle="modal" data-target="#" onclick="">Edit</button>
            </td>';
        $data .= '</tr>';
        $c++;
    }

    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}

if ($method == 'minlot_counts') {

    $sql = "SELECT count(*) as total FROM m_min_lot ";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        foreach ($stmt as $c) {
            $count = $c['total'];

            echo $count;
        }
    } else {
        echo 'Results: 0';
    }

}