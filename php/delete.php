// php/delete.php
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

include 'db.php';
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $taskId = $_POST['task_id'];

    if ($role == 'student') {
        $stmt = $connect_db->prepare("SELECT filepath FROM tasks WHERE id = ? AND user_id = ?");
        $stmt->bind_param("ii", $taskId, $userId);
    } else if ($role == 'teacher') {
        $stmt = $connect_db->prepare("SELECT filepath FROM tasks WHERE id = ?");
        $stmt->bind_param("i", $taskId);
    }

    $stmt->execute();
    $stmt->bind_result($filepath);
    $stmt->fetch();

    if ($filepath && unlink($filepath)) {
        $stmt->close();
        if ($role == 'student') {
            $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
            $stmt->bind_param("ii", $taskId, $userId);
        } else if ($role == 'teacher') {
            $stmt = $connect_db->prepare("DELETE FROM tasks WHERE id = ?");
            $stmt->bind_param("i", $taskId);
        }

        if ($stmt->execute()) {
            header('Location: ../dashboard.php');
        } else {
            echo "Error menghapus informasi file dari database.";
        }

        $stmt->close();
    } else {
        echo "Error menghapus file dari sistem file.";
    }
}

$connect_db->close();
?>
