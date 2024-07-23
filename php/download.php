// php/download.php
<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.html');
    exit();
}

include 'db.php';
if (isset($_GET['id'])) {
    $taskId = $_GET['id'];
    
    $stmt = $conn->prepare("SELECT filename, filepath FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $taskId);
    $stmt->execute();
    $stmt->bind_result($filename, $filepath);
    $stmt->fetch();

    if ($filepath) {
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        readfile($filepath);
    } else {
        echo "File tidak ditemukan.";
    }

    $stmt->close();
}

$connect_db->close();
?>
