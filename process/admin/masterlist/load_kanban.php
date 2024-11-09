<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_kanban') {

    $sql = "SELECT * FROM kanban_master";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();

    $kanban = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if($kanban){
        foreach($kanban as $row){
            $line = $row['line_no'];
            $new_line = substr($line,0, 4);
            echo '<tr>';
            echo '<td>' . $new_line . '</td>';
            echo '<td>' . $row['partcode'] . '</td>';
            echo '<td>' . $row['partname'] . '</td>';
          
            echo '</tr>';

        }

    }else {
        echo '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }

}