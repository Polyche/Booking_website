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

// Fetch destinations and accommodations count
$sql = "SELECT Destinatii.*, COUNT(Unitati_Cazare.ID_unitate) AS num_accommodations 
        FROM Destinatii 
        LEFT JOIN Unitati_Cazare ON Destinatii.ID_destinatie = Unitati_Cazare.ID_destinatie 
        GROUP BY Destinatii.ID_destinatie";
$result = $conn->query($sql);

$destinations = [];
while ($row = $result->fetch_assoc()) {
    $destinations[] = $row;
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: url('image1.jpg') no-repeat center center fixed;
            background-size: cover;
            margin: 0;
            padding: 0;
            color: #333;
        }

        .container {
            width: 90%;
            margin: 0 auto;
            padding: 20px;
            animation: fadeIn 1s ease-in-out;
        }

        .header-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 0;
        }

        header h1 {
            color: #008080;
            font-size: 2.5em;
            animation: slideInLeft 1s ease-in-out;
        }

        header nav ul {
            display: flex;
            list-style: none;
        }

        header nav ul li {
            margin-left: 20px;
        }

        header nav ul li a {
            color: #ffffff;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            background-color: #008080;
            transition: background-color 0.3s, transform 0.3s;
        }

        header nav ul li a:hover {
            background-color: #064949;
            transform: scale(1.05);
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

        .welcome-message {
            text-align: center;
            margin-top: 20px;
            animation: fadeInDown 1s ease-in-out;
        }

        .welcome-message h1 {
            color: #008080;
            font-size: 3em;
        }

        .search-bar {
            margin-top: 30px;
            text-align: center;
            position: relative;
        }

        .search-bar input[type="text"] {
            width: 60%;
            padding: 15px;
            font-size: 1em;
            border: 1px solid #ccc;
            border-radius: 50px;
            background-color: white;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            margin-right: 10px;
            transition: box-shadow 0.3s;
        }

        .search-bar input[type="text"]:focus {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
        }

        .search-bar button {
            padding: 15px 30px;
            font-size: 1em;
            border: none;
            border-radius: 5px;
            background-color: #008080;
            color: white;
            cursor: pointer;
            transition: background-color 0.3s, transform 0.3s;
        }

        .search-bar button:hover {
            background-color: #064949;
            transform: scale(1.05);
        }

        .offers {
            margin-top: 40px;
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            animation: fadeInUp 1s ease-in-out;
        }

        .offers h2 {
            font-size: 2em;
            margin-bottom: 10px;
            text-align: center;
            width: 100%;
            color: #008080;
        }

        .offers p {
            font-size: 1.2em;
            margin-bottom: 20px;
            text-align: center;
            width: 100%;
            color: #666;
        }

        .offers .offer-item {
    background-image: url('plane.jpeg');
    background-size: cover;
    background-position: center;
    color: white;
    width: 300px;
    height: 300px;
    margin: 10px;
    padding: 20px;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    text-align: center;
    transition: transform 0.3s;
    display: flex;
    flex-direction: column;
    justify-content: center;
    position: relative;
    overflow: hidden;
    animation: slideInUp 0.5s ease-out;
}

        .offers .offer-item:before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1;
            transition: opacity 0.3s;
        }

        .offers .offer-item:hover:before {
            opacity: 0.7;
        }

        .offers .offer-item h3,
        .offers .offer-item p {
            position: relative;
            z-index: 2;
            background: rgba(0, 0, 0, 0.5);
            padding: 6px;
            border-radius: 5px;
            margin: 10px 0;
        }

        .offers .offer-item a {
            display: block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #008080;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1em;
            transition: background-color 0.3s, transform 0.3s;
            text-decoration: none;
            z-index: 2;
        }

        .offers .offer-item a:hover {
            background-color: #064949;
            transform: scale(1.05);
        }

        .offers .offer-item:hover {
            transform: scale(1.05);
        }

        /* Dropdown list */
        #suggestions {
            position: relative;
            background-color: white;
            border: 1px solid #ccc;
            border-radius: 5px;
            width: 60%;
            max-height: 150px;
            overflow-y: auto;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            left: 30px;
            transform: translateX(20%);
        }

        #suggestions div {
            padding: 10px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #suggestions div:hover {
            background-color: #f0f0f0;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @keyframes slideInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
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
                    <li><a href="user_dashboard.php">My Account</a></li>
                    <li><button class="logout-button" onclick="location.href='logout.php'">Logout</button></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="welcome-message">
            <h1>Welcome, <?php echo htmlspecialchars($user['nume']); ?>!</h1>
            <p>Find your next stay. Search deals on hotels, homes, and much more...</p>
        </div>
        <div class="search-bar">
            <input type="text" id="search-input" placeholder="Search destinations and accommodations..." oninput="showSuggestions(this.value)">
            <button onclick="search()">Search</button>
            <div id="suggestions"></div>
        </div>
        <div class="offers">
            <h2>Special Offers</h2>
            <div class="offer-item">
                <h3>Fly to a much-visited vacation</h3>
                <p>Find inspiration, compare, and book flights with more flexibility.</p>
                <a href="https://wizzair.com/" target="_blank">Search Flights</a>
            </div>
        </div>
        <div class="offers">
            <h2>Explore Destinations</h2>
            <?php foreach ($destinations as $destination): ?>
                <div class="offer-item" style="background-image: url('data:image/jpeg;base64,<?php echo base64_encode($destination['imagine']); ?>');">
                    <h3><?php echo htmlspecialchars($destination['nume_destinatie']); ?></h3>
                    <p><?php echo htmlspecialchars($destination['num_accommodations']); ?> accommodations</p>
                    <a href="accommodations_in_destination.php?id=<?php echo $destination['ID_destinatie']; ?>">View Accommodations</a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>

    <script>
        function search() {
            var query = document.getElementById('search-input').value;
            if (query) {
                window.location.href = 'search_results.php?query=' + encodeURIComponent(query);
            }
        }

        function showSuggestions(value) {
            if (value.length < 2) {
                document.getElementById('suggestions').innerHTML = '';
                return;
            }

            const xhr = new XMLHttpRequest();
            xhr.open('GET', 'fetch_suggestions.php?query=' + encodeURIComponent(value), true);
            xhr.onload = function() {
                if (this.status === 200) {
                    const suggestions = JSON.parse(this.responseText);
                    let suggestionsDiv = document.getElementById('suggestions');
                    suggestionsDiv.innerHTML = '';

                    suggestions.forEach(suggestion => {
                        let suggestionItem = document.createElement('div');
                        suggestionItem.textContent = suggestion;
                        suggestionItem.onclick = function() {
                            document.getElementById('search-input').value = suggestion;
                            suggestionsDiv.innerHTML = '';
                        };
                        suggestionsDiv.appendChild(suggestionItem);
                    });
                }
            };
            xhr.send();
        }
    </script>
</body>
</html>
