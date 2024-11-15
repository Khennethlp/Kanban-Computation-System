<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method = 'load_combine') {

    $sql = "SELECT * FROM m_combine where maker_code = 'C'";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();

    $kanban = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $c = 0;
    if ($kanban) {
        foreach ($kanban as $row) {
            $c++;
            $maker_code = $row['maker_code'];
            echo '<tr>';
            echo '<td> '. $c .' </td>';
            if ($maker_code == 'A') {
                echo '<td> Mazda </td>';
            } else if ($maker_code == 'B') {
                echo '<td> Daihatsu </td>';
            } else if ($maker_code == 'C') {
                echo '<td> Honda </td>';
            } else if ($maker_code == 'D') {
                echo '<td> Toyota </td>';
            }
            echo '<td>' . $row['product_no'] . '</td>';
            echo '<td>' . $row['partcode'] . '</td>';
            echo '<td>' . $row['partname'] . '</td>';
            echo '<td>' . $row['need_qty'] . '</td>';

            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }
}
