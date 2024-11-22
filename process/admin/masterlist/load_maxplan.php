<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_maxplan') {

    $sql = "SELECT * FROM m_max_plan";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();

    $maxplan = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $c = 0;
    if ($maxplan) {
        foreach ($maxplan as $row) {
            $c++;
            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>N/A</td>';
            echo '<td>' . $row['max_plan'] . '</td>';
            echo '<td>
            <button class="btn actionBtn" data-toggle="modal" data-target="#" onclick="">Edit</button>
            </td>';
            echo '</tr>';
        }
    } else {
        echo '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }
}
