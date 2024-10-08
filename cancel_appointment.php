<?php
session_start();
include 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $app_id = intval($_POST['app_id']);
    $user_id = $_SESSION['user_id'];

    $stmt = $conn->prepare("DELETE FROM appointments WHERE app_id = ? AND user_id = ?");
    if ($stmt === false) {
        echo "Error: " . htmlspecialchars($conn->error) . "<br>";
    } else {
        $stmt->bind_param("ii", $app_id, $user_id);

        if ($stmt->execute()) {
            header("Location: dashboard.php");
        } else {
            echo "Error: " . htmlspecialchars($stmt->error) . "<br>";
        }

        $stmt->close();
    }

    $conn->close();
} else {
    header("Location: dashboard.php");
    exit();
}
?>
