<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $accommodationId = $_GET['id'];

    $sql = "DELETE FROM Unitati_Cazare WHERE ID_unitate = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $accommodationId);

    if ($stmt->execute()) {
        echo "Accommodation deleted successfully!";
    } else {
        echo "Error deleting accommodation: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
