<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

$query = isset($_GET['query']) ? $_GET['query'] : '';

$searchResults = [];

if ($query) {
    $stmt = $conn->prepare("SELECT * FROM Destinatii WHERE nume_destinatie LIKE ? OR oras LIKE ?");
    $likeQuery = "%" . $query . "%";
    $stmt->bind_param("ss", $likeQuery, $likeQuery);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $searchResults[] = $row;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Results</title>
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

        .search-results {
            margin-top: 30px;
        }

        .search-results h2 {
            font-size: 2em;
            margin-bottom: 20px;
            text-align: center;
        }

        .search-results .result-item {
            background-size: cover;
            background-position: center;
            margin: 10px 0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
            position: relative;
            height: 300px; /* Adjust as necessary */
        }

        .search-results .result-item:hover {
            transform: scale(1.05);
        }

        .result-item-content {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            background: rgba(0, 0, 0, 0.5);
            padding: 20px;
            border-radius: 8px;
            color: white;
            text-align: center;
        }

        .result-item-content h3 {
            margin: 0;
            font-size: 1.5em;
        }

        .result-item-content p {
            margin: 10px 0 0;
            font-size: 1em;
        }

        .result-item a {
            display: block;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            text-decoration: none;
            z-index: 1;
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
        <div class="search-results">
            <h2>Search Results for "<?php echo htmlspecialchars($query); ?>"</h2>
            <?php if (empty($searchResults)): ?>
                <p>No results found.</p>
            <?php else: ?>
                <?php foreach ($searchResults as $result): ?>
                    <div class="result-item" style="background-image: url('data:image/jpeg;base64,<?php echo base64_encode($result['imagine']); ?>');">
                        <a href="accommodations_in_destination.php?id=<?php echo $result['ID_destinatie']-1; ?>"></a>
                        <div class="result-item-content">
                            <h3><?php echo htmlspecialchars($result['nume_destinatie']); ?></h3>
                            <p><?php echo htmlspecialchars($result['descriere']); ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
