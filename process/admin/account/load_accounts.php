<?php
require '../../conn.php';

$method = $_POST['method'];

if ($method == 'load_accounts') {

    $search_key = $_POST['search'];

    $sql = "SELECT * FROM m_accounts";

    $conditions = [];
    if (!empty($search_key)) {
        $conditions[] = "(emp_id LIKE :search_key_empid OR fullname LIKE :search_key_fullname OR username LIKE :search_key_username OR dept LIKE :search_like_dept)";
    }

    if (!empty($conditions)) {
        $sql .= " WHERE " . implode(" AND ", $conditions);
    }

    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));

    if (!empty($search_key)) {
        $search_keys = "%$search_key%";
        $stmt->bindParam(':search_key_empid', $search_keys);
        $stmt->bindParam(':search_key_fullname', $search_keys);
        $stmt->bindParam(':search_key_username', $search_keys);
        $stmt->bindParam(':search_like_dept', $search_keys);
    }
    $stmt->execute();

    $accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $c = 0;

    if ($accounts) {
        foreach ($accounts as $acc) {

            $id = $acc['id'];
            $emp_id = $acc['emp_id'];
            $fullname = $acc['fullname'];
            $username = $acc['username'];
            $password = $acc['password'];
            $dept = $acc['dept'];
            $role = $acc['role'];
            $c++;

            echo '<tr>';
            echo '<td>' . $c . '</td>';
            echo '<td>' . $acc['emp_id'] . '</td>';
            echo '<td>' . $acc['fullname'] . '</td>';
            echo '<td>' . $acc['username'] . '</td>';
            echo '<td>' . $acc['dept'] . '</td>';
            echo '<td>' . $acc['role'] . '</td>';
            echo '<td>
            <button class="btn actionBtn" data-toggle="modal" data-target="#edit_account" onclick="editUser(\'' . htmlspecialchars($id) . '~!~' . htmlspecialchars($emp_id) . '~!~' . htmlspecialchars($fullname) . '~!~' . htmlspecialchars($username) . '~!~' . htmlspecialchars($password) . '~!~' . htmlspecialchars($dept) . '~!~' . htmlspecialchars($role) . '\');">Edit</button>
            <button class="btn delBtn" onclick="delUser(\'' . htmlspecialchars($id) . '~!~' . htmlspecialchars($emp_id) . '\');">Del</button>
            </td>';
            echo '</tr>';
        }
    }else {
        echo '<tr><td colspan="10" style="text-align:center;">No user found.</td></tr>';
    }
}

if ($method == 'add_account') {
    $emp_id = $_POST['emp_id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $section = $_POST['section'];
    $role = $_POST['role'];

    $sql = "INSERT INTO m_accounts (emp_id, fullname, username, password, dept, role) VALUES ('$emp_id', '$fullname', '$username', '$password', '$section', '$role')";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt) {
        echo 'success';
    } else {
        echo 'failed';
    }
}

if ($method == 'edit_user') {
    $id = $_POST['id'];
    $emp_id = $_POST['emp_id'];
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $section = $_POST['section'];
    $role = $_POST['role'];

    $sql = "UPDATE m_accounts SET emp_id = '$emp_id', fullname = '$fullname', username = '$username', password = '$password', dept = '$section', role = '$role' WHERE id = '$id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt) {
        echo 'success';
    } else {
        echo 'failed';
    }
}

if ($method == 'remove_user') {

    $id = $_POST['id'];
    $emp_id = $_POST['emp_id'];

    $sql = "DELETE FROM m_accounts WHERE id = '$id' AND emp_id = '$emp_id'";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    if ($stmt) {
        echo 'success';
    } else {
        echo 'failed';
    }
}
