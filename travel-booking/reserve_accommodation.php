<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$accommodationId = $_GET['id'];

// Fetch the accommodation details
$stmt = $conn->prepare("SELECT * FROM Unitati_Cazare WHERE ID_unitate = ?");
$stmt->bind_param("i", $accommodationId);
$stmt->execute();
$result = $stmt->get_result();
$accommodation = $result->fetch_assoc();

if (!$accommodation) {
    echo "Accommodation not found.";
    exit;
}

// Fetch the rooms for the accommodation
$stmt = $conn->prepare("SELECT * FROM Camere WHERE ID_unitate = ?");
$stmt->bind_param("i", $accommodationId);
$stmt->execute();
$rooms = $stmt->get_result();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['room_id'])) {
    $userId = $_SESSION['user_id'];
    $roomId = $_POST['room_id'];
    $checkinDate = $_POST['checkin_date'];
    $checkoutDate = $_POST['checkout_date'];
    $statusPlata = 0; // Assuming payment status is unpaid

    // Fetch current unavailable dates
    $stmt = $conn->prepare("SELECT unavailable_dates FROM Camere WHERE ID_camera = ?");
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();
    $stmt->close();

    $unavailableDates = json_decode($room['unavailable_dates'], true) ?: [];

    // Check for overlapping dates
    $period = new DatePeriod(
        new DateTime($checkinDate),
        new DateInterval('P1D'),
        (new DateTime($checkoutDate))->modify('+1 day')
    );

    foreach ($period as $date) {
        if (in_array($date->format('Y-m-d'), $unavailableDates)) {
            echo "<script>alert('The room is not available for the selected dates.'); window.location.href = 'reserve_accommodation.php?id=$accommodationId';</script>";
            exit;
        }
    }

    // Insert reservation into the database
    $stmt = $conn->prepare("INSERT INTO Rezervari (ID_camera, ID_utilizator, data_checkin, data_checkout, status_plata) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iissi", $roomId, $userId, $checkinDate, $checkoutDate, $statusPlata);

    if ($stmt->execute()) {
        // Add new unavailable dates
        foreach ($period as $date) {
            $unavailableDates[] = $date->format('Y-m-d');
        }

        $unavailableDates = array_unique($unavailableDates);
        $unavailableDatesJson = json_encode($unavailableDates);

        // Update room's unavailable dates
        $stmt = $conn->prepare("UPDATE Camere SET unavailable_dates = ? WHERE ID_camera = ?");
        $stmt->bind_param("si", $unavailableDatesJson, $roomId);
        $stmt->execute();
        $stmt->close();

        echo "<script>alert('Reservation successful!'); window.location.href = 'user_dashboard.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'reserve_accommodation.php?id=$accommodationId';</script>";
    }

    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reserve Accommodation</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f3f4f6;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
        }

        .header-container {
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
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header nav ul li a:hover {
            background-color: #e0e0e0;
        }

        .logout-button {
            background-color: #dc3545;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.3s;
        }

        .logout-button:hover {
            background-color: #c82333;
            transform: scale(1.05);
        }

        .reservation-form {
            margin-top: 30px;
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .reservation-form h2 {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .reservation-form form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .reservation-form form label {
            font-size: 1em;
            margin-bottom: 5px;
        }

        .reservation-form form input[type="date"],
        .reservation-form form button[type="submit"],
        .reservation-form form select {
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .reservation-form form button[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .reservation-form form button[type="submit"]:hover {
            background-color: #0056b3;
            transform: scale(1.05);
        }
    </style>
</head>
<body>
    <header>
        <div class="header-container">
            <h1>YourLogo</h1>
            <nav>
                <ul>
                    <li><a href="front_page.php">Home</a></li>
                    <li><a href="user_dashboard.php">My Account</a></li>
                    <li><button class="logout-button" onclick="location.href='logout.php'">Logout</button></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="reservation-form">
            <h2>Reserve a Room in <?php echo htmlspecialchars($accommodation['nume_unitate']); ?></h2>
            <?php if ($rooms->num_rows > 0): ?>
                <form method="post" action="reserve_accommodation.php?id=<?php echo $accommodationId; ?>">
                    <label for="room_id">Select Room:</label>
                    <select id="room_id" name="room_id" required>
                        <?php while ($room = $rooms->fetch_assoc()): ?>
                            <option value="<?php echo $room['ID_camera']; ?>">
                                <?php echo htmlspecialchars($room['tip_camera']) . " - " . htmlspecialchars($room['pret_noapte']) . " per night"; ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                    <label for="checkin_date">Check-in Date:</label>
                    <input type="date" id="checkin_date" name="checkin_date" required>
                    <label for="checkout_date">Check-out Date:</label>
                    <input type="date" id="checkout_date" name="checkout_date" required>
                    <button type="submit">Reserve</button>
                </form>
            <?php else: ?>
                <p>No rooms available for this accommodation.</p>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
</body>
</html>
