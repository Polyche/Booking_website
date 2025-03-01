<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['current_password'];
    $newPassword = $_POST['new_password'];
    $confirmNewPassword = $_POST['confirm_new_password'];

    // Fetch the user's current password from the database
    $sql = "SELECT parola FROM Utilizatori WHERE ID_utilizator = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();

    if (!$user || !password_verify($currentPassword, $user['parola'])) {
        echo "<script>alert('Incorrect current password.'); window.location.href = 'user_dashboard.php';</script>";
        exit;
    }

    if ($newPassword !== $confirmNewPassword) {
        echo "<script>alert('New passwords do not match.'); window.location.href = 'user_dashboard.php';</script>";
        exit;
    }

    // Update the user's password in the database
    $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);
    $sql = "UPDATE Utilizatori SET parola = ? WHERE ID_utilizator = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newPasswordHash, $userId);

    if ($stmt->execute()) {
        echo "<script>alert('Password changed successfully!'); window.location.href = 'user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'user_dashboard.php';</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>
