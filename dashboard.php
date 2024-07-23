// dashboard.php
<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit();
}

include 'php/db.php';
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

// Mengambil tugas dari database
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
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <h1>Welcome to the Dashboard</h1>
    <?php if ($role == 'student'): ?>
        <form id="uploadForm" action="php/upload.php" method="post" enctype="multipart/form-data">
            <label for="file">Upload Tugas:</label>
            <input type="file" name="file" id="file" required>
            <button type="submit">Upload</button>
        </form>
    <?php endif; ?>
    <h2>Tugas yang Di-upload:</h2>
    <ul>
        <?php foreach ($tasks as $task): ?>
            <li>
                <?php if ($role == 'teacher'): ?>
                    <?= $task['username'] ?> - 
                <?php endif; ?>
                <a href="php/download.php?id=<?= $task['id'] ?>"><?= $task['filename'] ?></a> - <?= $task['uploaded_at'] ?>
                <form action="php/delete.php" method="post" style="display:inline;">
                    <input type="hidden" name="task_id" value="<?= $task['id'] ?>">
                    <button type="submit">Delete</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
    <div id="uploadStatus"></div>

    <script src="js/upload.js"></script>
    <script src="js/fetchTasks.js"></script>
</body>
</html>
