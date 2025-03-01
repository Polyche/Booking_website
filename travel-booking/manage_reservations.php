<?php
session_start();
include 'db_connection.php';

// Handle adding new reservation
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['userId'])) {
    $roomId = $_POST['roomId'];
    $userId = $_POST['userId'];
    $checkinDate = $_POST['checkinDate'];
    $checkoutDate = $_POST['checkoutDate'];
    $paymentStatus = $_POST['paymentStatus'];

    $sql = "INSERT INTO Rezervari (ID_camera, ID_utilizator, data_checkin, data_checkout, status_plata) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissi", $roomId, $userId, $checkinDate, $checkoutDate, $paymentStatus);

    if ($stmt->execute()) {
        echo "<script>alert('New reservation added successfully!'); window.location.href = 'manage_reservations.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'manage_reservations.php';</script>";
    }
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Reservations</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('background.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 80vh;
            padding: 40px 0;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            width: 100%;
            margin-bottom: 20px;
        }

        form input[type="text"],
        form input[type="date"],
        form select,
        form button[type="submit"] {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form button[type="submit"] {
            background-color: #008080;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        form button[type="submit"]:hover {
            background-color: #064949;
            transform: scale(1.05);
        }

        p {
            text-align: center;
            margin-top: 20px;
            color: #555;
        }

        header {
            background-color: #008080;
            color: white;
            padding: 20px 0;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        header nav ul {
            display: flex;
            list-style: none;
        }

        header nav ul li {
            margin-left: 20px;
        }

        header nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header nav ul li a:hover {
            background-color: #064949;
        }

        footer {
            background-color: #008080;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
        }

        .table-container {
            width: 100%;
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .actions {
            display: flex;
            gap: 10px;
        }

        .actions button {
            padding: 5px 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
        }

        .actions .edit-btn {
            background-color: #007bff;
            color: white;
        }

        .actions .delete-btn {
            background-color: #dc3545;
            color: white;
        }

        .actions .edit-btn:hover {
            background-color: #0056b3;
        }

        .actions .delete-btn:hover {
            background-color: #c82333;
        }

        .header-container img {
            width: 20%;
            height: auto;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
        <h1><a href="admin_dashboard.html">
            <img src="logo.png" alt="Logo">
            </a></h1>
            <nav>
                <ul>
                <li><a href="admin_dashboard.html">Back to Admin Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content container">
        <h1>Manage Reservations</h1>
        <div class="form-container">
            <h2>Add New Reservation</h2>
            <form id="addReservationForm" method="post" action="manage_reservations.php">
                <select name="roomId" required>
                    <option value="">Select Room</option>
                    <?php
                    include 'db_connection.php';
                    $sql = "SELECT ID_camera, tip_camera FROM Camere";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['ID_camera']}'>{$row['tip_camera']}</option>";
                        }
                    }
                    ?>
                </select>
                <select name="userId" required>
                    <option value="">Select User</option>
                    <?php
                    $sql = "SELECT ID_utilizator, email FROM Utilizatori";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<option value='{$row['ID_utilizator']}'>{$row['email']}</option>";
                        }
                    }
                    ?>
                </select>
                <input type="date" name="checkinDate" placeholder="Check-in Date" required>
                <input type="date" name="checkoutDate" placeholder="Check-out Date" required>
                <select name="paymentStatus" required>
                    <option value="1">Paid</option>
                    <option value="0">Not Paid</option>
                </select>
                <button type="submit">Add Reservation</button>
            </form>
        </div>
        <div class="table-container">
            <h2>Existing Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Room</th>
                        <th>User</th>
                        <th>Check-in Date</th>
                        <th>Check-out Date</th>
                        <th>Payment Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include 'fetch_reservations.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
    <script>
        function editReservation(id) {
            window.location.href = `edit_reservations.php?id=${id}`;
        }

        function deleteReservation(id) {
            if (confirm('Are you sure you want to delete this reservation?')) {
                fetch(`delete_reservations.php?id=${id}`, {
                    method: 'GET'
                }).then(response => {
                    if (response.ok) {
                        alert('Reservation deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting reservation.');
                    }
                });
            }
        }
    </script>
</body>
</html>
