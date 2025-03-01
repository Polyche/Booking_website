<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
include 'db_connection.php';

$sql = "SELECT * FROM Destinatii";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>{$row['ID_destinatie']}</td>
                <td>{$row['nume_destinatie']}</td>
                <td>{$row['tara']}</td>
                <td>{$row['oras']}</td>
                <td>{$row['descriere']}</td>
                <td class='actions'>
                    <button class='edit-btn' onclick='editDestination({$row['ID_destinatie']})'>Edit</button>
                    <button class='delete-btn' onclick='deleteDestination({$row['ID_destinatie']})'>Delete</button>
                </td>
              </tr>";
    }
} else {
    echo "<tr><td colspan='6'>No destinations found.</td></tr>";
}

$conn->close();
?>
