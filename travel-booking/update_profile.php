<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$userId = $_SESSION['user_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Check if the new password and confirm password match
    if ($newPassword !== $confirmPassword) {
        echo "<script>alert('New password and confirm password do not match.'); window.location.href = 'user_dashboard.php';</script>";
        exit;
    }

    // Fetch the current password from the database
    $sql = "SELECT parola FROM Utilizatori WHERE ID_utilizator = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $stmt->bind_result($hashedPassword);
    $stmt->fetch();
    $stmt->close();

    // Verify the current password
    if (!password_verify($currentPassword, $hashedPassword)) {
        echo "<script>alert('Current password is incorrect.'); window.location.href = 'user_dashboard.php';</script>";
        exit;
    }

    // Hash the new password
    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    // Update the password in the database
    $sql = "UPDATE Utilizatori SET parola = ? WHERE ID_utilizator = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newHashedPassword, $userId);

    if ($stmt->execute()) {
        echo "<script>alert('Password changed successfully!'); window.location.href = 'user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'user_dashboard.php';</script>";
    }

    $stmt->close();
    $conn->close();
}
?>
