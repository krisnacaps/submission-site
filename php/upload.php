// php/upload.php
<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

include 'db.php';
$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['file'])) {
    $file = $_FILES['file'];
    $uploadDir = '../uploads/';
    $uploadFile = $uploadDir . basename($file['name']);

    if (move_uploaded_file($file['tmp_name'], $uploadFile)) {
        $stmt = $connect_db->prepare("INSERT INTO tasks (user_id, filename, filepath, uploaded_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("iss", $userId, $file['name'], $uploadFile);

        if ($stmt->execute()) {
            header('Location: ../dashboard.php');
        } else {
            echo "Error menyimpan informasi file ke database.";
        }

        $stmt->close();
    } else {
        echo "Error meng-upload file.";
    }
}

$connect_db->close();
?>
