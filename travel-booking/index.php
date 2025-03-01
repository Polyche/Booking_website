<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Website</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: url('image1.jpg') no-repeat center center fixed; /* Add your background image here */
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
            justify-content: space-between;
            align-items: center;
            min-height: 80vh;
            padding: 40px 0;
        }

        .form-container {
            background: rgba(255, 255, 255, 0.9);
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
        }

        .header-container img {
            width: 20%;
            height: auto;
        }

        .welcome-text {
            max-width: 500px;
            color: white;
            text-align: center;
            position: relative;
            top: -210px; /* Adjust this value as needed */
        }

        .welcome-text h1 {
            position: relative;
            top:125px;
            font-size: 3em;
            margin-bottom: 10px;
            color: white;
        }

        .welcome-text p {
            position: relative;
            top: 160px;
            font-size: 1.2em;
            margin-bottom: 20px;
            color: white;
        }

        form input[type="email"],
        form input[type="password"],
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
            color: #064949;
        }

        a {
            color: #007bff;
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        .password-container {
            position: relative;
        }

        .toggle-password {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            right: 10px;
            cursor: pointer;
        }

        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
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
                    <li><a href="register.html">Register</a></li>
                </ul>
            </nav>
        </div>
    </header>
    <div class="main-content container">
        <div class="form-container">
            <h2>Login</h2>
            <form action="login.php" method="post">
                <input type="email" name="email" placeholder="Email" required>
                <div class="password-container">
                    <input type="password" name="parola" id="password" placeholder="Password" required>
                    <span class="toggle-password" onclick="togglePasswordVisibility()"><i class="far fa-eye"></i></span>
                </div>
                <?php
                if (isset($_SESSION["error"])) {
                    echo '<div class="error-message">' . $_SESSION["error"] . '</div>';
                    unset($_SESSION["error"]);
                }
                ?>
                <button type="submit">Login</button>
            </form>
            <p>Don't have an account? <a href="register.html">Register here</a>.</p>
        </div>
        <div class="welcome-text">
            <h1>Welcome to Our Booking Website</h1>
            <p>Find your next stay. Search deals on hotels, homes, and much more...</p>
        </div>
    </div>
    <footer>
        <p>© 2024 Booking Website. All rights reserved.</p>
    </footer>

    <script>
        function togglePasswordVisibility() {
            var passwordInput = document.getElementById("password");
            var toggleButton = document.querySelector(".toggle-password i");
            
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleButton.classList.remove("fa-eye");
                toggleButton.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleButton.classList.remove("fa-eye-slash");
                toggleButton.classList.add("fa-eye");
            }
        }
    </script>
</body>
</html>
