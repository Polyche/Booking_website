<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit;
}

$destinationId = $_GET['id'];

// Fetch the destination name
$stmt = $conn->prepare("SELECT nume_destinatie FROM Destinatii WHERE ID_destinatie = ?");
$stmt->bind_param("i", $destinationId);
$stmt->execute();
$result = $stmt->get_result();
$destination = $result->fetch_assoc();

if (!$destination) {
    echo "Destination not found.";
    exit;
}

$destinationName = $destination['nume_destinatie'];

// Fetch accommodations for the destination
$stmt = $conn->prepare("SELECT * FROM Unitati_Cazare WHERE ID_destinatie = ?");
$stmt->bind_param("i", $destinationId);
$stmt->execute();
$result = $stmt->get_result();

$accommodations = [];
while ($row = $result->fetch_assoc()) {
    $accommodations[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accommodations in <?php echo htmlspecialchars($destinationName); ?></title>
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

        .header-container img {
            width: 20%;
            height: auto;
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

        .accommodations {
            margin-top: 30px;
        }

        .accommodations h2 {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .accommodations table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .accommodations table, th, td {
            border: 1px solid #ccc;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .reserve-button {
            display: inline-block;
            padding: 10px 20px;
            background-color: #008080;
            color: white;
            text-align: center;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s, transform 0.3s;
        }

        .reserve-button:hover {
            background-color: #064949;
            transform: scale(1.05);
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
    <div class="container">
        <div class="accommodations">
            <h2>Accommodations in <?php echo htmlspecialchars($destinationName); ?></h2>
            <?php if (empty($accommodations)): ?>
                <p>No accommodations found for this destination.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Accommodation Name</th>
                            <th>Type</th>
                            <th>Address</th>
                            <th>Rating</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($accommodations as $accommodation): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($accommodation['nume_unitate']); ?></td>
                                <td><?php echo htmlspecialchars($accommodation['tip_unitate']); ?></td>
                                <td><?php echo htmlspecialchars($accommodation['adresa']); ?></td>
                                <td><?php echo htmlspecialchars($accommodation['rating']); ?></td>
                                <td><?php echo htmlspecialchars($accommodation['descriere']); ?></td>
                                <td><a href="reserve_accommodation.php?id=<?php echo $accommodation['ID_unitate']; ?>" class="reserve-button">Reserve</a></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
</body>
</html>
