<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_combine') {
    $user = $_POST['user_name'];
    $search_key = $_POST['search_key'];
    $search_date = $_POST['search_date'];
    $car_model = $_POST['car_model'];

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 100;
    $offset = ($page - 1) * $rowsPerPage;

    $sql = "SELECT * FROM m_combine ";

    $conditions = [];
    if (!empty($search_key)) {
        $conditions[] = "(product_no LIKE :search_key_productNo OR partcode LIKE :search_key_partcode OR partname LIKE :search_key_partname)";
    }
    if (!empty($car_model)) {
        $conditions[] = "maker_code LIKE :search_key_model";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $sql .= " ORDER BY id DESC OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);

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

    $kanban = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $has_more = count($kanban) > $rowsPerPage;
    if ($has_more) {
        array_pop($kanban); // Remove the extra row used for the check
    }

    $data = '';
    $c = ($page - 1) * $rowsPerPage + 1;

    foreach ($kanban as $row) {
        $maker_code = $row['maker_code'];
        $data .= '<tr>';
        $data .= '<td> ' . $c . ' </td>';
        if ($maker_code == 'A') {
            $data .= '<td> Mazda </td>';
        } else if ($maker_code == 'B') {
            $data .= '<td> Daihatsu </td>';
        } else if ($maker_code == 'C') {
            $data .= '<td> Honda </td>';
        } else if ($maker_code == 'D') {
            $data .= '<td> Toyota </td>';
        }else if ($maker_code == 'E') {
            $data .= '<td> Suzuki </td>';
        }else if ($maker_code == 'F') {
            $data .= '<td> Subaru </td>';
        }
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

    echo json_encode(['html' => $data, 'has_more' => $has_more]);
}

if ($method == 'count_combine') {
    $search_key = $_POST['search_key'];
    $search_date = $_POST['search_date'];
    $car_model = $_POST['car_model'];

    $sqls = "SELECT count(*) as TOTAL FROM m_combine";

    $conditions = [];

    if (!empty($search_key)) {
        $conditions[] = "(product_no LIKE :search_key OR partcode LIKE :search_key OR partname LIKE :search_key)";
    }
    
    if (!empty($car_model)) {
        $conditions[] = "maker_code LIKE :search_key_model";
    }

    if (!empty($conditions)) {
        $sqls .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($sqls, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    if (!empty($search_key)) {
        $search_keys = "%$search_key%";
        $stmt->bindParam(':search_key', $search_keys);
    }
    if (!empty($car_model)) {
        $stmt->bindParam(':search_key_model', $car_model);
    }
 
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        echo $row['TOTAL']; 
    } else {
        echo '0';
    }
}


?>
