<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_master') {
    $user = $_POST['user_name'];
    $search_key = $_POST['search_key'];
    // $search_date = $_POST['search_date'];
    $month = $_POST['search_by_month'];

    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1;
    $rowsPerPage = isset($_POST['rows_per_page']) ? (int)$_POST['rows_per_page'] : 10;
    $offset = ($page - 1) * $rowsPerPage;

    // Main query with count as a subquery
    //     $sql = "
    //    SELECT 
    //     a.id, 
    //     a.partcode, 
    //     a.partname, 
    //     a.need_qty, 
    //     a.created_by, 
    //     b.partcode AS b_partcode, 
    //     b.partname AS b_partname, 
    //     b.min_lot, 
    //     b.parts_group, 
    //     c.product_no, 
    //     c.max_plan, 
    //     c.maxPlan_total AS maxplan_total, 
    //     c.line_no, 
    //     d.no_teams, 
    //     COUNT(*) OVER() AS total_count,
    //     e.masterlist_count
    // FROM 
    //     m_combine a
    // LEFT JOIN 
    //     m_min_lot b 
    //     ON a.partcode = b.partcode AND a.partname = b.partname
    // LEFT JOIN (
    //     SELECT 
    //         line_no, 
    //         max_plan, 
    //         SUM(max_plan) OVER (PARTITION BY line_no) AS maxPlan_total, 
    //         product_no 
    //     FROM 
    //         m_max_plan
    // ) c 
    //     ON a.product_no = c.product_no
    // LEFT JOIN 
    //     m_no_teams d 
    //     ON c.line_no = d.line_no
    // LEFT JOIN (
    //     SELECT 
    //         partscode, 
    //         partsname, 
    //         LEFT(line_number, PATINDEX('%[^0-9]%', line_number + 'X') - 1) AS numeric_line_number,
    //         COUNT(*) AS masterlist_count
    //     FROM [new_ekanban].[dbo].[mm_masterlist]
    //     GROUP BY partscode, partsname, LEFT(line_number, PATINDEX('%[^0-9]%', line_number + 'X') - 1)
    // ) e 
    //     ON a.partcode = e.partscode 
    //     AND a.partname = e.partsname 
    //     AND e.numeric_line_number = CAST(c.line_no AS VARCHAR)
    //         ";

    $sql = "SELECT *, COUNT(*) OVER() AS total_count FROM m_master";
    $conditions = [];
    $current_year = date('Y');

    if (!empty($search_key)) {
        $conditions[] = "(line_no = :line_no OR partcode = :partcode OR partname = :partname OR product_no = :product_no)";
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

    // if (!empty($search_date)) {
    //     $conditions[] = "CAST(created_at AS DATE) = :search_date";
    // }

    // $conditions[] = "MONTH(created_at) = :current_month";
    // $conditions[] = "b.parts_group NOT LIKE 'B%' AND b.parts_group NOT LIKE 'Q%' AND c.line_no IS NOT NULL AND c.product_no IS NOT NULL AND d.no_teams IS NOT NULL AND c.max_plan != '0' AND b.partcode IS NOT NULL";

    $conditions[] = "max_plan != '0'";
    // Applying WHERE conditions if any
    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    // Apply DISTINCT on the product_no column
    $sql .= "
             ORDER BY 
                product_no, id DESC
             OFFSET :offset ROWS FETCH NEXT :limit_plus_one ROWS ONLY";

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    $limit_plus_one = $rowsPerPage + 1;
    $stmt->bindParam(':limit_plus_one', $limit_plus_one, PDO::PARAM_INT);
    $stmt->bindParam(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindParam(':start_date', $start_date);
    $stmt->bindParam(':end_date', $end_date);

    if (!empty($search_key)) {
        $stmt->bindParam(':line_no', $search_key);
        $stmt->bindParam(':partcode', $search_key);
        $stmt->bindParam(':partname', $search_key);
        $stmt->bindParam(':product_no', $search_key);
    }

    // if (!empty($search_date)) {
    //     $stmt->bindParam(':search_date', $search_date);
    // }

    // $current_month = date('n');
    // $stmt->bindParam(':current_month', $current_month, PDO::PARAM_INT);

    $stmt->execute();

    $master = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $has_more = count($master) > $rowsPerPage;
    if ($has_more) {
        array_pop($master); // Remove the extra row used for the check
    }

    $totalCount = $master[0]['total_count'] ?? 0;

    $data = '';
    $c = ($page - 1) * $rowsPerPage + 1;

    foreach ($master as $row) {
        $id = $row['id'];
        $product_no = $row['product_no'];
        $added_by = $row['created_by'];
        $line_no = $row['line_no'];
        $partcode = $row['partcode'];
        $partname = $row['partname'];
        $min_lot = $row['min_lot'];
        $max_usage = $row['max_usage'];
        $max_plan = $row['max_plan'];
        $no_teams = $row['no_teams'];
        $issued_to_pd = $row['issued_pd'];
        // $issued_to_pd = 2;

        $data .= '<tr>';
        $data .= '<td>' . $c . '</td>';
        $data .= '<td>' . $row['line_no'] . '</td>';
        $data .= '<td>' . $row['product_no'] . '</td>';
        $data .= '<td>' . $row['partcode'] . '</td>';
        $data .= '<td>' . $row['partname'] . '</td>';
        $data .= '<td>' . $row['min_lot'] . '</td>';
        $data .= '<td>' . $row['max_usage'] . '</td>';
        $data .= '<td>' . $row['max_plan'] . '</td>';
        $data .= '<td>' . $row['no_teams'] . '</td>';
        $data .= '<td>' . $row['issued_pd'] . '</td>';
        $data .= '<td>' . $row['parts_group'] . '</td>';
        $data .= '<td>
            <button class="btn actionBtn" data-toggle="modal" data-target="#edit_masterlist" onclick="getMaster(\'' . htmlspecialchars($id) . '~!~' . htmlspecialchars($line_no) . '~!~' . htmlspecialchars($partcode) . '~!~' . htmlspecialchars($partname) . '~!~' . htmlspecialchars($min_lot) . '~!~' . htmlspecialchars($max_usage) . '~!~' . htmlspecialchars($max_plan) . '~!~' . htmlspecialchars($no_teams) . '~!~' . htmlspecialchars($issued_to_pd) . '~!~' . htmlspecialchars($product_no) . '~!~' . htmlspecialchars($user) . '\');">Edit</button>
            </td>';
        $data .= '</tr>';
        $c++;
    }

    if (empty($data)) {
        $data = '<tr><td colspan="10" style="text-align:center;">No results found.</td></tr>';
    }

    // Return both data and count
    echo json_encode([
        'html' => $data,
        'has_more' => $has_more,
        'total' => $totalCount
    ]);
}
