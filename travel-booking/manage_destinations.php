<?php
// Start the session
session_start();

// Include the database connection file
include 'db_connection.php';

// Check if the form was submitted for adding a destination
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['destinationName'])) {
    // Get the form data
    $destinationName = $_POST['destinationName'];
    $country = $_POST['country'];
    $city = $_POST['city'];
    $description = $_POST['description'];
    $image = file_get_contents($_FILES['image']['tmp_name']);

    // Insert the data into the database
    $sql = "INSERT INTO Destinatii (nume_destinatie, tara, oras, descriere, imagine) VALUES (?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssss", $destinationName, $country, $city, $description, $image);

    if ($stmt->execute()) {
        echo "<script>alert('New destination added successfully!'); window.location.href = 'manage_destinations.php';</script>";
    } else {
        echo "<script>alert('Error: " . $stmt->error . "'); window.location.href = 'manage_destinations.php';</script>";
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
    <title>Manage Destinations</title>
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
        <h1>Manage Destinations</h1>
        <div class="form-container">
            <h2>Add New Destination</h2>
            <form id="addDestinationForm" method="post" action="manage_destinations.php" enctype="multipart/form-data">
                <input type="text" name="destinationName" placeholder="Destination Name" required>
                <input type="text" name="country" placeholder="Country" required>
                <input type="text" name="city" placeholder="City" required>
                <textarea name="description" placeholder="Description"></textarea>
                <input type="file" name="image" required>
                <button type="submit">Add Destination</button>
            </form>
        </div>
        <div class="table-container">
            <h2>Existing Destinations</h2>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Destination Name</th>
                        <th>Country</th>
                        <th>City</th>
                        <th>Description</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php include 'fetch_destinations.php'; ?>
                </tbody>
            </table>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>
    <script>
        function editDestination(id) {
            window.location.href = `edit_destination.php?id=${id}`;
        }

        function deleteDestination(id) {
            if (confirm('Are you sure you want to delete this destination?')) {
                fetch(`delete_destination.php?id=${id}`, {
                    method: 'GET'
                }).then(response => {
                    if (response.ok) {
                        alert('Destination deleted successfully!');
                        window.location.reload();
                    } else {
                        alert('Error deleting destination.');
                    }
                });
            }
        }
    </script>
</body>
</html>
