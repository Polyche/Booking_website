<?php
include 'db_connection.php';

$sql = "SELECT Camere.ID_camera, Unitati_Cazare.nume_unitate, Camere.tip_camera, Camere.capacitate, Camere.pret_noapte, Camere.disponibilitate 
        FROM Camere 
        JOIN Unitati_Cazare ON Camere.ID_unitate = Unitati_Cazare.ID_unitate";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $availability = $row['disponibilitate'] ? 'Available' : 'Not Available';
        echo "<tr>
                <td>{$row['ID_camera']}</td>
                <td>{$row['nume_unitate']}</td>
                <td>{$row['tip_camera']}</td>
                <td>{$row['capacitate']}</td>
                <td>{$row['pret_noapte']}</td>
                <td>{$availability}</td>
                <td class='actions'>
                    <button class='edit-btn' onclick='editRoom({$row['ID_camera']})'>Edit</button>
                    <button class='delete-btn' onclick='deleteRoom({$row['ID_camera']})'>Delete</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='7'>No rooms found.</td></tr>";
}

$conn->close();
?>
