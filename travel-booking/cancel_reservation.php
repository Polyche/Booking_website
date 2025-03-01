<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if (isset($_GET['id'])) {
    $reservationId = $_GET['id'];

    // Fetch the reservation details to get the room ID and dates
    $sql = "SELECT ID_camera, data_checkin, data_checkout FROM Rezervari WHERE ID_rezervare = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $stmt->bind_result($roomId, $checkinDate, $checkoutDate);
    $stmt->fetch();
    $stmt->close();

    // Fetch current unavailable dates
    $stmt = $conn->prepare("SELECT unavailable_dates FROM Camere WHERE ID_camera = ?");
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    $stmt->close();

    $unavailableDates = json_decode($room['unavailable_dates'], true) ?: [];

    // Remove the dates related to this reservation from unavailable dates
    $period = new DatePeriod(
        new DateTime($checkinDate),
        new DateInterval('P1D'),
        (new DateTime($checkoutDate))->modify('+1 day')
    );

    foreach ($period as $date) {
        $formattedDate = $date->format('Y-m-d');
        if (($key = array_search($formattedDate, $unavailableDates)) !== false) {
            unset($unavailableDates[$key]);
        }
    }

    $unavailableDates = array_values($unavailableDates); // Re-index array
    $unavailableDatesJson = json_encode($unavailableDates);

    // Update room's unavailable dates
    $stmt = $conn->prepare("UPDATE Camere SET unavailable_dates = ? WHERE ID_camera = ?");
    $stmt->bind_param("si", $unavailableDatesJson, $roomId);
    $stmt->execute();
    $stmt->close();

    // Delete the reservation
    $sql = "DELETE FROM Rezervari WHERE ID_rezervare = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $stmt->close();
}

$conn->close();
header('Location: user_dashboard.php');
exit;
?>
