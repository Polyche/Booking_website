<?php
include 'db_connection.php';

if (isset($_GET['id'])) {
    $roomId = $_GET['id'];

    $sql = "DELETE FROM Camere WHERE ID_camera = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);

    if ($stmt->execute()) {
        echo "Room deleted successfully!";
    } else {
        echo "Error deleting room: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
