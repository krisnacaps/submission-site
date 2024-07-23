// php/fetchTasks.php

<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

include 'db.php';
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

$tasks = [];
if ($role == 'student') {
    $stmt = $connect_db->prepare("SELECT id, filename, uploaded_at FROM tasks WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
} else if ($role == 'teacher') {
    $stmt = $connect_db->prepare("SELECT tasks.id, tasks.filename, tasks.uploaded_at, users.username FROM tasks INNER JOIN users ON tasks.user_id = users.id");
}

$stmt->execute();
if ($role == 'student') {
    $stmt->bind_result($taskId, $filename, $uploadedAt);
    while ($stmt->fetch()) {
        $tasks[] = ['id' => $taskId, 'filename' => $filename, 'uploaded_at' => $uploadedAt];
    }
} else if ($role == 'teacher') {
    $stmt->bind_result($taskId, $filename, $uploadedAt, $username);
    while ($stmt->fetch()) {
        $tasks[] = ['id' => $taskId, 'filename' => $filename, 'uploaded_at' => $uploadedAt, 'username' => $username];
    }
}

$stmt->close();
$connect_db->close();

header('Content-Type: application/json');
echo json_encode($tasks);
?>
