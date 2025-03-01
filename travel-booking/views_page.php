<?php
session_start();
include 'db_connection.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// Function to fetch and display data from a view
function displayView($conn, $viewName, $columns) {
    $sql = "SELECT * FROM $viewName";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        echo "<table>";
        echo "<thead><tr>";
        foreach ($columns as $column) {
            echo "<th>$column</th>";
        }
        echo "</tr></thead>";
        echo "<tbody>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            foreach ($columns as $column) {
                echo "<td>{$row[$column]}</td>";
            }
            echo "</tr>";
        }
        echo "</tbody></table>";
    } else {
        echo "<p>No data available in $viewName.</p>";
    }
}

// Fetch data for each view
$views = [
    "Most_Popular_Destinations" => ["ID_destinatie", "nume_destinatie", "total_accommodations"],
    "Available_Rooms" => ["ID_camera", "tip_camera", "capacitate", "pret_noapte", "nume_unitate", "nume_destinatie"],
    "User_Reservations_Summary" => ["ID_utilizator", "nume", "prenume", "total_reservations"],
    "Accommodation_Ratings" => ["ID_destinatie", "nume_destinatie", "average_rating"]
];

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Database Views</title>
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
            color: #333;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        header nav ul li a:hover {
            background-color: #064949;
        }

        .header-container img {
            width: 20%;
            height: auto;
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
                <li><a href="admin_dashboard.html">Back to Admin Dashboard</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <h1>Database Views</h1>
        <?php foreach ($views as $viewName => $columns): ?>
            <div class="view-container">
                <h2><?php echo str_replace('_', ' ', $viewName); ?></h2>
                <?php displayView($conn, $viewName, $columns); ?>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>
<?php
$conn->close();
?>
