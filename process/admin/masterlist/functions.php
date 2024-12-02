<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method === 'update_master') {
    $id = $_POST['id'];
    $line_no = $_POST['line_no'];
    $partname = $_POST['partname'];
    $partcode = $_POST['partcode'];
    $min_lot = $_POST['minLot'];        // m_min_lot
    $max_plan = $_POST['maxPlan'];      // m_max_plan
    $max_usage = $_POST['maxUsage'];    // need_qty m_combine
    $no_teams = $_POST['noTeams'];      // m_no_teams
    $product_no = $_POST['product_no']; // m_combine & m_max_plan
    $issued_pd = $_POST['issued_pd']; // m_combine & m_max_plan

    try {
        $conn->beginTransaction();

        $update_master = "UPDATE m_master SET min_lot = :min_lot, max_usage = :max_usage, no_teams = :no_teams, issued_pd = :issued_pd WHERE id = :id AND partcode = :partcode AND partname = :partname AND line_no = :line_no";
        $stmt_master = $conn->prepare($update_master);
        $stmt_master->bindParam(':id', $id);
        $stmt_master->bindParam(':line_no', $line_no);
        $stmt_master->bindParam(':partcode', $partcode);
        $stmt_master->bindParam(':partname', $partname);
        $stmt_master->bindParam(':min_lot', $min_lot);
        $stmt_master->bindParam(':max_usage', $max_usage);
        $stmt_master->bindParam(':no_teams', $no_teams);
        $stmt_master->bindParam(':issued_pd', $issued_pd);
        $stmt_master->execute();

        $update_maxPlan = "UPDATE m_master SET max_plan = :max_plan WHERE product_no = :product_no AND line_no = :line_no";
        $stmt_maxPlan = $conn->prepare($update_maxPlan);
        $stmt_maxPlan->bindParam(':product_no', $product_no);
        $stmt_maxPlan->bindParam(':line_no', $line_no);
        $stmt_maxPlan->bindParam(':max_plan', $max_plan);
        $stmt_maxPlan->execute();

        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'failed';
        echo "Error: " . $e->getMessage();
    }
}
