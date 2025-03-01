<?php
session_start(); // Start the session
include 'db_connect.php'; // Your database connection file

// Check if the user is logged in and is an admin
if (!isset($_SESSION['user_id']) || $_SESSION['tip_utilizator'] != 'admin') {
    header('Location: login.php'); // Redirect to login page
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $description = $_POST['description'];

    // SQL to insert data
    $sql = "INSERT INTO Destinatii (nume_destinatie, tara, oras, descriere) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $name, $country, $city, $description);
    if ($stmt->execute()) {
        echo "<p>New destination added successfully!</p>";
    } else {
        echo "<p>Error: " . $stmt->error . "</p>";
    }
    $stmt->close();
    $conn->close();
}
?>
