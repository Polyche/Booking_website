<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $reservationId = $_GET['id'];

    $sql = "DELETE FROM Rezervari WHERE ID_rezervare = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservationId);

    if ($stmt->execute()) {
        echo "Reservation deleted successfully!";
    } else {
        echo "Error deleting reservation: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
