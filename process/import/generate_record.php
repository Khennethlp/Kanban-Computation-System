<?php
require '../conn.php';

$userName = isset($_POST['userName']) ? $_POST['userName'] : '';

try {
    $conn->beginTransaction();

    $conn->exec("
        CREATE TABLE #temp_master (
            product_no NVARCHAR(255),
            line_no NVARCHAR(255),
            partcode NVARCHAR(255),
            partname NVARCHAR(255),
            min_lot DECIMAL(10,3),
            max_usage DECIMAL(10,3),
            max_plan DECIMAL(10,3),
            no_teams INT,
            issued_pd INT,
            parts_group NVARCHAR(255),
            created_by NVARCHAR(255),
            maker_code NVARCHAR(255)
        )
    ");

    $insertTempSql = "
        INSERT INTO #temp_master (product_no, line_no, partcode, partname, min_lot, max_usage, max_plan, no_teams, issued_pd, parts_group, created_by, maker_code)
        SELECT 
            a.product_no, 
            b.line_no, 
            a.partcode, 
            a.partname, 
            c.min_lot, 
            a.need_qty AS max_usage, 
            b.max_plan AS maxplan_total, 
            e.no_teams AS no_teams, 
            COUNT(d.partcode) AS issued_pd,
            c.parts_group,
            ? AS created_by, 
            a.maker_code
        FROM 
            m_combine a
        LEFT JOIN 
            m_max_plan b ON a.product_no = b.product_no
        LEFT JOIN 
            m_min_lot c ON c.partcode = a.partcode AND c.partname = a.partname
        LEFT JOIN 
            m_no_teams e ON b.line_no = e.line_no
        LEFT JOIN 
            kanban_master d ON c.partcode = d.partcode
        WHERE 
            b.product_no IS NOT NULL 
            AND b.line_no != '0'
            AND c.parts_group NOT LIKE 'b%' 
            AND c.parts_group NOT LIKE 'q%'
        GROUP BY 
            b.line_no, a.product_no, a.partcode, a.partname, c.min_lot, a.need_qty, b.max_plan, 
            e.no_teams, c.parts_group, a.maker_code;
    ";

    $stmt = $conn->prepare($insertTempSql);
    $stmt->execute([$userName]);

    $insertMainSql = "
        INSERT INTO m_master (product_no, line_no, partcode, partname, min_lot, max_usage, max_plan, no_teams, issued_pd, parts_group, created_by, maker_code)
        SELECT 
            t.product_no, 
            t.line_no, 
            t.partcode, 
            t.partname, 
            t.min_lot, 
            t.max_usage, 
            t.max_plan, 
            t.no_teams, 
            t.issued_pd, 
            t.parts_group, 
            t.created_by, 
            t.maker_code
        FROM #temp_master t
        LEFT JOIN m_master m
        ON t.product_no = m.product_no 
        AND t.line_no = m.line_no 
        AND t.partcode = m.partcode 
        AND t.partname = m.partname
        WHERE m.product_no IS NULL;
    ";

    $conn->exec($insertMainSql);

    $conn->exec("DROP TABLE #temp_master");

    $conn->commit();

    echo 'success'; 

} catch (PDOException $e) {
    $conn->rollBack();
    echo "Error: " . $e->getMessage();
}
?>
