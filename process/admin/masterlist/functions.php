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

    try {
        $conn->beginTransaction();

        // Update `m_combine`
        $sql_bom = "UPDATE m_combine SET need_qty = :max_usage WHERE id = :id AND product_no = :product_no";
        $stmt_bom = $conn->prepare($sql_bom);
        $stmt_bom->bindParam(':max_usage', $max_usage, PDO::PARAM_INT);
        $stmt_bom->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt_bom->bindParam(':product_no', $product_no);
        $stmt_bom->execute();

        // Update `m_min_lot`
        $sql_min = "UPDATE m_min_lot SET min_lot = :min_lot WHERE partcode = :partcode AND partname = :partname";
        $stmt_min = $conn->prepare($sql_min);
        $stmt_min->bindParam(':min_lot', $min_lot, PDO::PARAM_INT);
        $stmt_min->bindParam(':partcode', $partcode);
        $stmt_min->bindParam(':partname', $partname);
        $stmt_min->execute();

        // Update `m_max_plan`
        $sql_max = "UPDATE m_max_plan SET max_plan = :max_plan WHERE product_no = :product_no AND line_no = :line_no";
        $stmt_max = $conn->prepare($sql_max);
        $stmt_max->bindParam(':max_plan', $max_plan, PDO::PARAM_INT);
        $stmt_max->bindParam(':product_no', $product_no);
        $stmt_max->bindParam(':line_no', $line_no);
        $stmt_max->execute();

        // Update `m_no_teams`
        $sql_teams = "UPDATE m_no_teams SET no_teams = :no_teams WHERE line_no = :line_no";
        $stmt_teams = $conn->prepare($sql_teams);
        $stmt_teams->bindParam(':no_teams', $no_teams, PDO::PARAM_INT);
        $stmt_teams->bindParam(':line_no', $line_no);
        $stmt_teams->execute();

        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'failed';
        echo "Error: " . $e->getMessage();
    }
}
