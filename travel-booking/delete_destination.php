<?php
// Include the database connection file
include 'db_connection.php';

// Get the destination ID from the query string
if (isset($_GET['id'])) {
    $destinationId = $_GET['id'];

    // Delete the destination from the database
    $sql = "DELETE FROM Destinatii WHERE ID_destinatie = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $destinationId);

    if ($stmt->execute()) {
        echo "Destination deleted successfully!";
    } else {
        echo "Error deleting destination: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
