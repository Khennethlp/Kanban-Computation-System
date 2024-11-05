<?php
require '../../conn.php';

$method = $_POST['method'];

if($method == 'update_master'){
    $id = $_POST['id'];
    $partname = $_POST['partname'];
    $partcode = $_POST['partcode'];
    $minLot = $_POST['minLot'];
    $maxPlan = $_POST['maxPlan'];
    $noTeams = $_POST['noTeams'];

    $sql = "UPDATE m_master SET min_lot = '$minLot', max_plan = '$maxPlan', no_teams = '$noTeams' WHERE id = '$id'";

    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if($stmt){
        echo 'success';
    }else{
        echo 'failed';
    }
}