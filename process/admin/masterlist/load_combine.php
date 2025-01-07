<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_combine') {
    $user = $_POST['user_name'];
    $search_key = $_POST['search_key'];
    $car_model = $_POST['car_model'];
    $month = $_POST['search_by_month'];
    $current_year = isset($_POST['search_by_year']) && !empty(trim($_POST['search_by_year'])) ? trim($_POST['search_by_year']) : date('Y');

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 100;
    $offset = ($page - 1) * $rowsPerPage;
    
    $sql = "SELECT *, COUNT(*) OVER() AS total_count FROM m_combine";
    
    $conditions = [];
    if (!empty($search_key)) {
        $conditions[] = "(product_no LIKE :search_key_productNo OR partcode LIKE :search_key_partcode OR partname LIKE :search_key_partname)";
    }
    if (!empty($car_model)) {
        $conditions[] = "maker_code LIKE :search_key_model";
    }
    if (!empty($month)) {
        $start_date = $current_year . '-' . str_pad($month, 2, '0', STR_PAD_LEFT) . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));
        $conditions[] = "created_at BETWEEN :start_date AND :end_date";
    } else {
        // Default to current month
        $current_month = date('n');
        $start_date = $current_year . '-' . str_pad($current_month, 2, '0', STR_PAD_LEFT) . '-01';
        $end_date = date("Y-m-t", strtotime($start_date));
        $conditions[] = "created_at BETWEEN :start_date AND :end_date";
    }
    
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }
    
    $sql .= " ORDER BY id ASC OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";
    
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    
    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);

    if (!empty($search_key)) {
        $search_keys = "%$search_key%";
        $stmt->bindParam(':search_key_productNo', $search_keys);
        $stmt->bindParam(':search_key_partcode', $search_keys);
        $stmt->bindParam(':search_key_partname', $search_keys);
    }
    
    if (!empty($car_model)) {
        $stmt->bindParam(':search_key_model', $car_model);
    }
    
    $stmt->execute();
    
    // Fetch car maker codes
    $sql_maker_code = "SELECT car_maker, maker_code FROM m_maker_code";
    $stmt_maker_code = $conn->prepare($sql_maker_code, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt_maker_code->execute();
    $car_codes = $stmt_maker_code->fetchAll(PDO::FETCH_ASSOC);
    
    $car_code_map = [];
    foreach ($car_codes as $code) {
        $car_code_map[$code['maker_code']] = $code['car_maker'];
    }
    
    // Fetch results from the main query
    $kanban = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $has_more = count($kanban) > $rowsPerPage;
    if ($has_more) {
        array_pop($kanban);
    }
    
    $totalCount = $kanban[0]['total_count'] ?? 0;
    $data = '';
    $c = ($page - 1) * $rowsPerPage + 1;
    
    foreach ($kanban as $row) {
        $maker_code = $row['maker_code'];
        $car_maker = $car_code_map[$maker_code] ?? 'Unknown';
        
        $data .= '<tr>';
        $data .= '<td> ' . $c . ' </td>';
        $data .= '<td> ' . $car_maker . ' </td>';
        $data .= '<td>' . $row['product_no'] . '</td>';
        $data .= '<td>' . $row['partcode'] . '</td>';
        $data .= '<td>' . $row['partname'] . '</td>';
        $data .= '<td>' . $row['need_qty'] . '</td>';
        $data .= '</tr>';
        $c++;
    }
    
    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }
    
    echo json_encode(['html' => $data, 'has_more' => $has_more, 'total' => $totalCount]);
}

