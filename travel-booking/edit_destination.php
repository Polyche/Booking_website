<?php
// Start the session
session_start();

// Include the database connection file
include 'db_connection.php';

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $destinationId = $_GET['id'];

    // Fetch the destination details
    $sql = "SELECT * FROM Destinatii WHERE ID_destinatie = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("i", $destinationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $destination = $result->fetch_assoc();

    if (!$destination) {
        echo "Destination not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

// Check if the form was submitted for updating the destination
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateDestination'])) {
    // Get the form data
    $destinationName = $_POST['destinationName'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $description = $_POST['description'];

    // Handle image upload if provided
    if (!empty($_FILES['image']['tmp_name'])) {
        // Check if file was uploaded without errors
        if ($_FILES['image']['error'] == 0) {
            $image = file_get_contents($_FILES['image']['tmp_name']);
            $sql = "UPDATE Destinatii SET nume_destinatie = ?, tara = ?, oras = ?, descriere = ?, imagine = ? WHERE ID_destinatie = ?";
            $stmt = $conn->prepare($sql);
            if (!$stmt) {
                die("Prepare failed: " . $conn->error);
            }
            $stmt->bind_param("sssssi", $destinationName, $country, $city, $description, $image, $destinationId);
        } else {
            echo "<script>alert('Error uploading image: " . $_FILES['image']['error'] . "');</script>";
        }
    } else {
        $sql = "UPDATE Destinatii SET nume_destinatie = ?, tara = ?, oras = ?, descriere = ? WHERE ID_destinatie = ?";
        $stmt = $conn->prepare($sql);
        if (!$stmt) {
            die("Prepare failed: " . $conn->error);
        }
        $stmt->bind_param("ssssi", $destinationName, $country, $city, $description, $destinationId);
    }

    if ($stmt->execute()) {
        echo "<script>alert('Destination updated successfully!'); window.location.href = 'manage_destinations.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'edit_destination.php?id=$destinationId';</script>";
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
    <title>Edit Destination</title>
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
        form input[type="file"],
        form textarea,
        form button[type="submit"] {
            width: 100%;
            padding: 15px;
            margin-top: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            box-sizing: border-box;
        }

        form textarea {
            height: 100px;
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

        .current-image {
            display: block;
            margin: 20px 0;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
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
                    <li><a href="admin_dashboard.html">Dashboard</a></li>
                    <li><a href="manage_destinations.php">Manage Destinations</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content container">
        <h1>Edit Destination</h1>
        <div class="form-container">
            <h2>Edit Destination Details</h2>
            <form id="editDestinationForm" method="post" action="edit_destination.php?id=<?php echo $destinationId; ?>" enctype="multipart/form-data">
                <input type="text" name="destinationName" value="<?php echo htmlspecialchars($destination['nume_destinatie']); ?>" placeholder="Destination Name" required>
                <input type="text" name="country" value="<?php echo htmlspecialchars($destination['tara']); ?>" placeholder="Country" required>
                <input type="text" name="city" value="<?php echo htmlspecialchars($destination['oras']); ?>" placeholder="City" required>
                <textarea name="description" placeholder="Description"><?php echo htmlspecialchars($destination['descriere']); ?></textarea>
                <input type="file" name="image">
                <?php if (!empty($destination['imagine'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($destination['imagine']); ?>" alt="Current Image" class="current-image">
                <?php endif; ?>
                <button type="submit" name="updateDestination">Update Destination</button>
            </form>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
</body>
</html>
