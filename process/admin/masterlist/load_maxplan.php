<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_maxplan') {

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT * FROM m_max_plan ";

    $sql .= "WHERE max_plan != 0 ORDER BY id OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

    $stmt->execute();

    $maxplan = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($maxplan) > $rowsPerPage;
    if ($has_more) {
        array_pop($maxplan);
    }

    $data = '';
    $c = ($page - 1) * $rowsPerPage + 1;

        foreach ($maxplan as $row) {
      
            $data .= '<tr>';
            $data .= '<td>' . $c . '</td>';
            $data .= '<td>' . $row['line_no'] . '</td>';
            $data .= '<td>' . $row['max_plan'] . '</td>';
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

if ($method == 'maxplan_counts') {

    $sql = "SELECT count(*) as total FROM m_max_plan WHERE max_plan != 0";
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
