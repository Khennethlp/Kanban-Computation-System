<?php
session_name("Kanban_Computation_System");
session_start();

include 'conn.php';

$title = "Kanban Computation System";

if (isset($_POST['Login'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT emp_id, fullname, role FROM m_accounts WHERE username = '$username' AND password = '$password'";
    $stmt = $conn->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_SCROLL));
    $stmt->execute();
    if ($stmt->rowCount() > 0) {
        foreach($stmt->fetchALL() as $x){
            $name = $x['fullname'];
            $role = $x['role'];
            $_SESSION['username'] = $username;
            $_SESSION['name'] = $name;
            $_SESSION['role'] = $role;

            if ($_SESSION['username'] == $username && $role == 'admin') {
                header('location: page/admin/index.php'); // admin/index.php
                exit;
            }
        }
    } else {
        // echo '<script>alert("Sign In Failed. Maybe an incorrect credential or account not found")</script>';
        $_SESSION['status'] = 'error';
        $_SESSION['msg'] = 'Sign In Failed. Please try again.';

    }
}

if (isset($_POST['Logout'])) {
    session_unset();
    session_destroy();
    header('Location: /kcs/index.php');
    exit;
}
