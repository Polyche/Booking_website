<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $roomId = $_GET['id'];
    $sql = "SELECT * FROM Camere WHERE ID_camera = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $roomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if (!$room) {
        echo "Room not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateRoom'])) {
    $unitId = $_POST['unitId'];
    $roomType = $_POST['roomType'];
    $capacity = $_POST['capacity'];
    $pricePerNight = $_POST['pricePerNight'];
    $availability = $_POST['availability'];

    $sql = "UPDATE Camere SET ID_unitate = ?, tip_camera = ?, capacitate = ?, pret_noapte = ?, disponibilitate = ? WHERE ID_camera = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isidsi", $unitId, $roomType, $capacity, $pricePerNight, $availability, $roomId);

    if ($stmt->execute()) {
        echo "<script>alert('Room updated successfully!'); window.location.href = 'manage_rooms.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'edit_rooms.php?id=$roomId';</script>";
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
    <title>Edit Room</title>
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
        form input[type="number"],
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
                <li><a href="manage_rooms.php">Back to Rooms</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content container">
        <h1>Edit Room</h1>
        <div class="form-container">
            <h2>Edit Room Details</h2>
            <form id="editRoomForm" method="post" action="edit_rooms.php?id=<?php echo $roomId; ?>">
                <select name="unitId" required>
                    <option value="">Select Accommodation Unit</option>
                    <?php
                    $sql = "SELECT ID_unitate, nume_unitate FROM Unitati_Cazare";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $selected = $row['ID_unitate'] == $room['ID_unitate'] ? 'selected' : '';
                            echo "<option value='{$row['ID_unitate']}' $selected>{$row['nume_unitate']}</option>";
                        }
                    }
                    ?>
                </select>
                <input type="text" name="roomType" value="<?php echo $room['tip_camera']; ?>" placeholder="Room Type" required>
                <input type="number" name="capacity" value="<?php echo $room['capacitate']; ?>" placeholder="Capacity" required>
                <input type="number" step="0.01" name="pricePerNight" value="<?php echo $room['pret_noapte']; ?>" placeholder="Price per Night" required>
                <select name="availability" required>
                    <option value="1" <?php if ($room['disponibilitate']) echo 'selected'; ?>>Available</option>
                    <option value="0" <?php if (!$room['disponibilitate']) echo 'selected'; ?>>Not Available</option>
                </select>
                <button type="submit" name="updateRoom">Update Room</button>
            </form>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
</body>
</html>
