<?php
session_start();
include 'db_connection.php';

if (isset($_GET['id'])) {
    $accommodationId = $_GET['id'];
    $sql = "SELECT * FROM Unitati_Cazare WHERE ID_unitate = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $accommodationId);
    $stmt->execute();
    $result = $stmt->get_result();
    $accommodation = $result->fetch_assoc();

    if (!$accommodation) {
        echo "Accommodation not found.";
        exit;
    }
} else {
    echo "Invalid request.";
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['updateAccommodation'])) {
    $destinationId = $_POST['destinationId'];
    $unitName = $_POST['unitName'];
    $unitType = $_POST['unitType'];
    $address = $_POST['address'];
    $rating = $_POST['rating'];
    $description = $_POST['description'];

    $sql = "UPDATE Unitati_Cazare SET ID_destinatie = ?, nume_unitate = ?, tip_unitate = ?, adresa = ?, rating = ?, descriere = ? WHERE ID_unitate = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("isssdsi", $destinationId, $unitName, $unitType, $address, $rating, $description, $accommodationId);

    if ($stmt->execute()) {
        echo "<script>alert('Accommodation updated successfully!'); window.location.href = 'manage_accommodations.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'edit_accommodations.php?id=$accommodationId';</script>";
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
    <title>Edit Accommodation</title>
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
        form textarea,
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

        header nav ul li a {
            color: #fff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .header-container img {
            width: 20%;
            height: auto;
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
                <li><a href="manage_accommodations.php">Back to Accommodations</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content container">
        <h1>Edit Accommodation</h1>
        <div class="form-container">
            <h2>Edit Accommodation Details</h2>
            <form id="editAccommodationForm" method="post" action="edit_accommodations.php?id=<?php echo $accommodationId; ?>">
                <select name="destinationId" required>
                    <option value="">Select Destination</option>
                    <?php
                    $sql = "SELECT ID_destinatie, nume_destinatie FROM Destinatii";
                    $result = $conn->query($sql);
                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            $selected = $row['ID_destinatie'] == $accommodation['ID_destinatie'] ? 'selected' : '';
                            echo "<option value='{$row['ID_destinatie']}' $selected>{$row['nume_destinatie']}</option>";
                        }
                    }
                    ?>
                </select>
                <input type="text" name="unitName" value="<?php echo $accommodation['nume_unitate']; ?>" placeholder="Unit Name" required>
                <input type="text" name="unitType" value="<?php echo $accommodation['tip_unitate']; ?>" placeholder="Unit Type" required>
                <input type="text" name="address" value="<?php echo $accommodation['adresa']; ?>" placeholder="Address" required>
                <input type="number" step="0.1" name="rating" value="<?php echo $accommodation['rating']; ?>" placeholder="Rating" required>
                <textarea name="description" placeholder="Description"><?php echo $accommodation['descriere']; ?></textarea>
                <button type="submit" name="updateAccommodation">Update Accommodation</button>
            </form>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
</body>
</html>
