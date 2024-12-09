<?php
require '../conn.php';


$userName = isset($_POST['userName']) ? $_POST['userName'] : '';
try {

    // query to get the data from joined table and insert into database
    $query = "SELECT a.id, a.maker_code, a.partcode, a.partname, a.need_qty, a.created_by, a.created_at AS combine_date,
  b.partcode AS b_partcode, b.partname AS b_partname, b.min_lot, b.parts_group, b.created_at AS minlot_date,
  c.product_no, c.max_plan, c.maxPlan_total, c.line_no, c.created_at AS maxplan_date, d.no_teams, d.created_at AS team_date, e.masterlist_count
FROM 
  m_combine a
LEFT JOIN 
  m_min_lot b ON a.partcode = b.partcode AND a.partname = b.partname
LEFT JOIN (
  SELECT line_no, max_plan, 
         SUM(max_plan) OVER (PARTITION BY line_no) AS maxPlan_total, 
         product_no, created_at 
  FROM m_max_plan
) c ON a.product_no = c.product_no
LEFT JOIN 
  m_no_teams d ON c.line_no = d.line_no
LEFT JOIN (
  SELECT partscode, partsname, 
         LEFT(line_number, PATINDEX('%[^0-9]%', line_number + 'X') - 1) AS numeric_line_number,
         COUNT(*) AS masterlist_count
  FROM [new_ekanban].[dbo].[mm_masterlist]
  GROUP BY partscode, partsname, LEFT(line_number, PATINDEX('%[^0-9]%', line_number + 'X') - 1)
) e ON a.partcode = e.partscode 
   AND a.partname = e.partsname 
   AND e.numeric_line_number = CAST(c.line_no AS VARCHAR)
WHERE 
  b.parts_group NOT LIKE 'B%' AND b.parts_group NOT LIKE 'Q%' 
  AND c.line_no IS NOT NULL AND c.product_no IS NOT NULL 
  AND d.no_teams IS NOT NULL AND c.max_plan != '0' 
  AND b.partcode IS NOT NULL
GROUP BY 
  a.id, a.maker_code, a.partcode, a.partname, a.need_qty, a.created_by, a.created_at,
  b.partcode, b.partname, b.parts_group, b.min_lot, b.created_at,
  c.product_no, c.max_plan, c.maxPlan_total, c.created_at,
  c.line_no, d.no_teams, d.created_at, e.masterlist_count
ORDER BY 
  c.product_no, a.id DESC
";

    $stmt = $conn->query($query);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($rows)) {
        echo "No matching records found.";
        exit;
    }

    $values = [];
    $placeholders = [];
    foreach ($rows as $row) {
        $values[] = $row['product_no'];
        $values[] = $row['line_no'];
        $values[] = $row['partcode'];
        $values[] = $row['partname'];
        $values[] = $row['min_lot'];
        $values[] = $row['need_qty'];
        $values[] = $row['maxplan_total'];
        $values[] = $row['no_teams'];
        $values[] = $row['masterlist_count'];
        $values[] = $row['parts_group'];
        $values[] = $userName; // Assuming $userName is defined elsewhere
        $values[] = $row['maker_code'];

        $placeholders[] = "(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    }

    $insertSql = "INSERT INTO m_master (product_no, line_no, partcode, partname, min_lot, max_usage, max_plan, no_teams, issued_pd, parts_group, created_by, maker_code)
        VALUES " . implode(", ", $placeholders);

    $insertStmt = $conn->prepare($insertSql);
    $result = $insertStmt->execute($values);

    if ($result) {
        echo "success.";
    } else {
        echo "failed.";
    }
} catch (PDOException $e) {
    echo "Error" . $e->getMessage();
}
