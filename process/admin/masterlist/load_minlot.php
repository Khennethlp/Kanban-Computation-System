<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_minlot') {

    $sql = "SELECT * FROM m_min_lot";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();

    $maxplan = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $c = 0;
    if ($maxplan) {
        foreach ($maxplan as $row) {
            $c++;
            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $row['partcode'] . '</td>';
            echo '<td>' . $row['partname'] . '</td>';
            echo '<td>' . $row['min_lot'] . '</td>';
            echo '<td>
            <button class="btn actionBtn" data-toggle="modal" data-target="#" onclick="">Edit</button>
            </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }
}
