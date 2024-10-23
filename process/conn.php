<?php
$server_month = date('Y');
date_default_timezone_set('Asia/Manila');
// $servername = 'localhost'; $username = 'root'; $password = '';
// // $servername = 'localhost'; $username = 'root'; $password = 'trspassword2022';

// try {
//     $conn = new PDO ("mysql:host=$servername;dbname=e-report",$username,$password);
//     $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// } catch (PDOException $e) {
//     echo 'NO CONNECTION'.$e->getMessage();
// }


$servername = '172.25.116.188';
$username = 'SA';
$password = 'SystemGroup@2022';
$Database = 'kanban_computation';

try {
    // Connection to the kanban_computation database
    $conn = new PDO("sqlsrv:Server=$servername;Database=$Database", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo 'NO CONNECTION to kanban_computation: ' . $e->getMessage();
}

?>