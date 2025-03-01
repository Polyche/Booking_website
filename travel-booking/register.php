<?php
// Include database connection
include 'db_connection.php';

$response = array(); // Initialize response array

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get user details from form
    $nume = $_POST["nume"];
    $prenume = $_POST["prenume"];
    $email = $_POST["email"];
    $parola = $_POST["parola"];

    // Check if email already exists
    $check_email_query = "SELECT * FROM Utilizatori WHERE email = '$email'";
    $check_email_result = $conn->query($check_email_query);

    if ($check_email_result->num_rows > 0) {
        // Email already exists, set error message in response
        $response["success"] = false;
        $response["message"] = "Error: This email is already registered. Please use a different email.";
    } else {
        // Hash password
        $hashed_password = password_hash($parola, PASSWORD_DEFAULT);

        // Insert user into database
        $insert_query = "INSERT INTO Utilizatori (nume, prenume, email, parola, tip_utilizator)
                         VALUES ('$nume', '$prenume', '$email', '$hashed_password', 'user')";

        if ($conn->query($insert_query) === TRUE) {
            // Registration successful
            $response["success"] = true;
            $response["message"] = "Registration successful!";
        } else {
            // Error inserting user into database
            $response["success"] = false;
            $response["message"] = "Error: Registration failed. Please try again later.";
        }
    }
}

// Send JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
