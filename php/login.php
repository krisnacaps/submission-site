<?php
 
 include 'db.php';

 if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);


    //mencari user di database
    $stmt = $connect_db->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->store_result();
    $stmt->bind_result($userId, $hashPassword, $role);
    $stmt->fetch();

    if ($stmt->num_rows > 0 ) {
        session_start();
        $_SESSION['user_id'] = $userId;
        $_SESSION['role'] = $role;
        header('location: ../dashboard.php');
    } else {
        echo 'Login Gagal. Username atau password salah!';
        echo $username;
        echo $password;
        echo $userId;
    }

    $stmt->close();
    } else {
        echo "Error: Form tidak lengkap.";
    }
}

$connect_db->close();
