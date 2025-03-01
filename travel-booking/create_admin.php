<?php
// Include database connection
include 'db_connection.php';

// Admin details
$nume = "Admin";
$prenume = "Admin";
$email = "admin1@example.com";
$plain_password = "admin123"; // Plain password
$hashed_password = password_hash($plain_password, PASSWORD_DEFAULT); // Hash the password

// Check if admin already exists
$check_admin_query = "SELECT * FROM Utilizatori WHERE email = '$email'";
$check_admin_result = $conn->query($check_admin_query);

if ($check_admin_result->num_rows > 0) {
    // Admin already exists
    echo "Error: Admin already exists in the database.";
} else {
    // Insert admin into database
    $insert_admin_query = "INSERT INTO Utilizatori (nume, prenume, email, parola, tip_utilizator)
                           VALUES ('$nume', '$prenume', '$email', '$hashed_password', 'admin')";

    if ($conn->query($insert_admin_query) === TRUE) {
        echo "Admin creation successful!";
    } else {
        echo "Error: " . $insert_admin_query . "<br>" . $conn->error;
    }
}
?>
