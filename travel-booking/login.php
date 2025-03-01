<?php
session_start();

// Include database connection
include 'db_connection.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get email and password from form
    $email = $_POST["email"];
    $parola = $_POST["parola"];

    // Prepare the statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM Utilizatori WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        // User found, verify password
        $row = $result->fetch_assoc();
        if (password_verify($parola, $row["parola"])) {
            // Start session and set session variables
            $_SESSION["user_id"] = $row["ID_utilizator"];
            $_SESSION["tip_utilizator"] = $row["tip_utilizator"];

            // Redirect based on user type
            if ($row["tip_utilizator"] == 'admin') {
                header("Location: admin_dashboard.html");
            } else {
                header("Location: front_page.php");
            }
            exit();
        } else {
            // Incorrect password
            $_SESSION["error"] = "Incorrect password";
            header("Location: index.php");
            exit();
        }
    } else {
        // User not found
        $_SESSION["error"] = "User not found";
        header("Location: index.php");
        exit();
    }

    $stmt->close();
}
?>
