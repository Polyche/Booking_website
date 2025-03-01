<?php
include 'db_connection.php';

$sql = "SELECT Unitati_Cazare.ID_unitate, Destinatii.nume_destinatie, Unitati_Cazare.nume_unitate, Unitati_Cazare.tip_unitate, Unitati_Cazare.adresa, Unitati_Cazare.rating, Unitati_Cazare.descriere 
        FROM Unitati_Cazare 
        JOIN Destinatii ON Unitati_Cazare.ID_destinatie = Destinatii.ID_destinatie";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['ID_unitate']}</td>
                <td>{$row['nume_destinatie']}</td>
                <td>{$row['nume_unitate']}</td>
                <td>{$row['tip_unitate']}</td>
                <td>{$row['adresa']}</td>
                <td>{$row['rating']}</td>
                <td>{$row['descriere']}</td>
                <td class='actions'>
                    <button class='edit-btn' onclick='editAccommodation({$row['ID_unitate']})'>Edit</button>
                    <button class='delete-btn' onclick='deleteAccommodation({$row['ID_unitate']})'>Delete</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='8'>No accommodations found.</td></tr>";
}

$conn->close();
?>
