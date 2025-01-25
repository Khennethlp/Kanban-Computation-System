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

        $update_master = "UPDATE m_master SET max_plan = :max_plan, min_lot = :min_lot, max_usage = :max_usage, no_teams = :no_teams, issued_pd = :issued_pd WHERE product_no = :product_no AND line_no = :line_no";
        $stmt_master = $conn->prepare($update_master);
        $stmt_master->bindParam(':line_no', $line_no);
        $stmt_master->bindParam(':product_no', $product_no);
        $stmt_master->bindParam(':max_plan', $max_plan);
        $stmt_master->bindParam(':min_lot', $min_lot);
        $stmt_master->bindParam(':max_usage', $max_usage);
        $stmt_master->bindParam(':no_teams', $no_teams);
        $stmt_master->bindParam(':issued_pd', $issued_pd);
        $stmt_master->execute();

        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'failed';
        echo "Error: " . $e->getMessage();
    }
}

if ($method == "add_car_maker_code") {
    $maker_code = strtoupper(trim($_POST['maker_code'] ?? ''));
    $car_maker = $_POST['car_maker'];

    $car_maker = ucfirst(strtolower($car_maker));

    try {
        $code_exist = "SELECT car_maker, maker_code FROM m_maker_code WHERE maker_code = :maker_code OR car_maker = :car_maker";
        $stmt_exist = $conn->prepare($code_exist, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
        $stmt_exist->bindParam(':maker_code', $maker_code);
        $stmt_exist->bindParam(':car_maker', $car_maker);
        $stmt_exist->execute();

        if ($stmt_exist->rowCount() > 0) {
            echo 'exist';
        } else {
            $sql = "INSERT INTO m_maker_code (maker_code, car_maker) VALUES (:maker_code, :car_maker)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':maker_code', $maker_code);
            $stmt->bindParam(':car_maker', $car_maker);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo 'success';
            } else {
                echo 'error';
            }
        }
    } catch (PDOException $e) {
        echo 'error';
    }
}

if ($method == 'delete_master') {
    $search_key = $_POST['search_key'] ?? '';

    try {
        $conn->beginTransaction();
    
        $sql = "DELETE FROM m_master WHERE line_no = :line_no OR partcode = :partcode OR partname = :partname OR product_no = :product_no";
        $stmt_master = $conn->prepare($sql);
        $stmt_master->bindParam(':line_no', $search_key);
        $stmt_master->bindParam(':partcode', $search_key);
        $stmt_master->bindParam(':partname', $search_key);
        $stmt_master->bindParam(':product_no', $search_key);
        
        // Execute the statement
        $stmt_master->execute();
        $conn->commit();
        echo 'success';
    } catch (Exception $e) {
        $conn->rollBack();
        echo 'failed';
        echo "Error: " . $e->getMessage();
    }
}
