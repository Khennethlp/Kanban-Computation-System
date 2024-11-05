<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_master') {
    
    $sql = "SELECT * FROM m_master";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    $master = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if ($master) {
        foreach ($master as $row) {
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
    }
}
