<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT * FROM Utilizatori WHERE ID_utilizator = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user reservations
$sql = "SELECT Rezervari.*, Camere.tip_camera, Unitati_Cazare.nume_unitate, Destinatii.nume_destinatie 
        FROM Rezervari 
        JOIN Camere ON Rezervari.ID_camera = Camere.ID_camera 
        JOIN Unitati_Cazare ON Camere.ID_unitate = Unitati_Cazare.ID_unitate 
        JOIN Destinatii ON Unitati_Cazare.ID_destinatie = Destinatii.ID_destinatie 
        WHERE Rezervari.ID_utilizator = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$reservations = $stmt->get_result();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('image2.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            color: white;
        }

        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: 80vh;
            padding: 40px 0;
        }

        .profile-container, .reservations-container, .password-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
            margin-bottom: 20px;
        }

        .profile-container h2, .reservations-container h2, .password-container h2 {
            color: #008080;
            text-align: center;
            margin-bottom: 20px;
        }

        .profile-container form input[type="text"],
        .profile-container form input[type="email"],
        .profile-container form button[type="submit"],
        .password-container form input[type="password"],
        .password-container form button[type="submit"] {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        .profile-container form button[type="submit"],
        .password-container form button[type="submit"] {
            background-color: #008080;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .profile-container form button[type="submit"]:hover,
        .password-container form button[type="submit"]:hover {
            background-color: #064949;
            transform: scale(1.05);
        }

        .reservations-container table {
            color: #008080;
            width: 100%;
            border-collapse: collapse;
        }

        .reservations-container table, th, td {
            border: 1px solid #ccc;
        }

        .reservations-container th, td {
            padding: 10px;
            text-align: left;
        }

        .reservations-container th {
            background-color: #f2f2f2;
        }

        .cancel-button {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9em;
            transition: background-color 0.3s, transform 0.3s;
        }

        .cancel-button:hover {
            background-color: #c82333;
            transform: scale(1.05);
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

        footer {
            background-color: #008080;
            color: white;
            text-align: center;
            padding: 20px 0;
            margin-top: 40px;
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
        <h1><a href="front_page.php">
            <img src="logo.png" alt="Logo">
            </a></h1>
            <nav>
                <ul>
                    <li><a href="front_page.php">Home</a></li>
                    <li><a href="user_dashboard.php">My Account</a></li>
                    <li><button class="logout-button" onclick="location.href='logout.php'">Logout</button></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content container">
        <div class="profile-container">
            <h2>Your Profile</h2>
            <form method="post" action="update_profile.php">
                <input type="text" name="firstName" value="<?php echo $user['nume']; ?>" placeholder="First Name" readonly>
                <input type="text" name="lastName" value="<?php echo $user['prenume']; ?>" placeholder="Last Name" readonly>
                <input type="email" name="email" value="<?php echo $user['email']; ?>" placeholder="Email" readonly>
            </form>
        </div>
        <div class="password-container">
            <h2>Change Password</h2>
            <form method="post" action="change_password.php">
                <input type="password" name="current_password" placeholder="Current Password" required>
                <input type="password" name="new_password" placeholder="New Password" required>
                <input type="password" name="confirm_new_password" placeholder="Confirm New Password" required>
                <button type="submit">Change Password</button>
            </form>
        </div>
        <div class="reservations-container">
            <h2>Your Reservations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Destination</th>
                        <th>Accommodation</th>
                        <th>Room</th>
                        <th>Check-in Date</th>
                        <th>Check-out Date</th>
                        <th>Payment Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($reservation = $reservations->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($reservation['ID_rezervare']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['nume_destinatie']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['nume_unitate']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['tip_camera']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['data_checkin']); ?></td>
                            <td><?php echo htmlspecialchars($reservation['data_checkout']); ?></td>
                            <td><?php echo $reservation['status_plata'] ? 'Paid' : 'Not Paid'; ?></td>
                            <td>
                                <button class="cancel-button" onclick="cancelReservation(<?php echo $reservation['ID_rezervare']; ?>)">Cancel</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>

    <script>
        function cancelReservation(reservationId) {
            if (confirm('Are you sure you want to cancel this reservation?')) {
                // Make an AJAX request to cancel the reservation
                fetch(`cancel_reservation.php?id=${reservationId}`, {
                    method: 'GET'
                }).then(response => {
                    if (response.ok) {
                        alert('Reservation canceled successfully!');
                        window.location.reload();
                    } else {
                        alert('Error canceling reservation.');
                    }
                });
            }
        }
    </script>
</body>
</html>
