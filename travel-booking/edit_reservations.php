<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $reservationId = $_GET['id'];
    $sql = "SELECT * FROM Rezervari WHERE ID_rezervare = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reservationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $reservation = $result->fetch_assoc();

    if (!$reservation) {
        echo "Reservation not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateReservation'])) {
    $roomId = $_POST['roomId'];
    $userId = $_POST['userId'];
    $checkinDate = $_POST['checkinDate'];
    $checkoutDate = $_POST['checkoutDate'];
    $paymentStatus = $_POST['paymentStatus'];

    $sql = "UPDATE Rezervari SET ID_camera = ?, ID_utilizator = ?, data_checkin = ?, data_checkout = ?, status_plata = ? WHERE ID_rezervare = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iissii", $roomId, $userId, $checkinDate, $checkoutDate, $paymentStatus, $reservationId);

    if ($stmt->execute()) {
        echo "<script>alert('Reservation updated successfully!'); window.location.href = 'manage_reservations.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'edit_reservations.php?id=$reservationId';</script>";
    }

    $stmt->close();
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Reservation</title>
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

        .header-container img {
            width: 20%;
            height: auto;
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
                <li><a href="manage_reservations.php">Back to Reservations</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content container">
        <h1>Edit Reservation</h1>
        <div class="form-container">
            <h2>Edit Reservation Details</h2>
            <form id="editReservationForm" method="post" action="edit_reservations.php?id=<?php echo $reservationId; ?>">
                <select name="roomId" required>
                    <option value="">Select Room</option>
                    <?php
                    $sql = "SELECT ID_camera, tip_camera FROM Camere";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $selected = $row['ID_camera'] == $reservation['ID_camera'] ? 'selected' : '';
                            echo "<option value='{$row['ID_camera']}' $selected>{$row['tip_camera']}</option>";
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
                            $selected = $row['ID_utilizator'] == $reservation['ID_utilizator'] ? 'selected' : '';
                            echo "<option value='{$row['ID_utilizator']}' $selected>{$row['email']}</option>";
                        }
                    }
                    ?>
                </select>
                <input type="date" name="checkinDate" value="<?php echo $reservation['data_checkin']; ?>" required>
                <input type="date" name="checkoutDate" value="<?php echo $reservation['data_checkout']; ?>" required>
                <select name="paymentStatus" required>
                    <option value="1" <?php if ($reservation['status_plata']) echo 'selected'; ?>>Paid</option>
                    <option value="0" <?php if (!$reservation['status_plata']) echo 'selected'; ?>>Not Paid</option>
                </select>
                <button type="submit" name="updateReservation">Update Reservation</button>
            </form>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
</body>
</html>

