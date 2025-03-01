<?php
include 'db_connection.php';

$sql = "SELECT Rezervari.ID_rezervare, Camere.tip_camera, Utilizatori.email, Rezervari.data_checkin, Rezervari.data_checkout, Rezervari.status_plata 
        FROM Rezervari 
        JOIN Camere ON Rezervari.ID_camera = Camere.ID_camera 
        JOIN Utilizatori ON Rezervari.ID_utilizator = Utilizatori.ID_utilizator";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $paymentStatus = $row['status_plata'] ? 'Paid' : 'Not Paid';
        echo "<tr>
                <td>{$row['ID_rezervare']}</td>
                <td>{$row['tip_camera']}</td>
                <td>{$row['email']}</td>
                <td>{$row['data_checkin']}</td>
                <td>{$row['data_checkout']}</td>
                <td>{$paymentStatus}</td>
                <td class='actions'>
                    <button class='edit-btn' onclick='editReservation({$row['ID_rezervare']})'>Edit</button>
                    <button class='delete-btn' onclick='deleteReservation({$row['ID_rezervare']})'>Delete</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No reservations found.</td></tr>";
}

$conn->close();
?>
