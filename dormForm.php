<?php
require 'connect.php';

error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $name = mysqli_real_escape_string($conn, $_POST['username']);
    $address = mysqli_real_escape_string($conn, $_POST['adress']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $rooms = mysqli_real_escape_string($conn, $_POST['number_of_rooms']);

    $query = "INSERT INTO dorms (dormName, dormLocation, phone, dormCapacity, image) 
              VALUES ('$name', '$address', '$phone', '$rooms', '$image')";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Dormitory added successfully!');</script>";
    } else {
        echo "<script>alert('SQL Error: " . mysqli_error($conn) . "');</script>";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Dormitory</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="background">
    <header class="navigator">
        <ul>
            <li><img src="img/logo.jpeg" alt="Logo"></li>
            <li><a href="dorms.php">Dorms</a></li>
            <li><a href="manager.php">Managers</a></li>
            <li><a href="student.php">Students</a></li>
            <li><a href="logout.php">Logout</a></li>
        </ul>
    </header>

    <div class="form-container">
        <h1 class="text-center">Add Dormitory</h1>
        <form method="post" action="dormForm.php">
            <div class="form-group">
                <label for="username">Name:</label>
                <input type="text" id="username" name="username" required>
            </div>

            <div class="form-group">
                <label for="adress">Address:</label>
                <input type="text" id="adress" name="adress" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone number:</label>
                <input type="tel" id="phone" name="phone" required>
            </div>

            <div class="form-group">
                <label for="number_of_rooms">Number of rooms:</label>
                <input type="number" id="number_of_rooms" name="number_of_rooms" required>
            </div>

            <div class="button-group">
                <button type="submit">Submit</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>

    <footer class="customFooter">
        <div class="socialIcons">
            <a href="https://www.facebook.com"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://www.instagram.com"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://x.com"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.youtube.com"><i class="fa-brands fa-youtube"></i></a>
        </div>
        <div class="footerLogo">
            <img src="img/logo.jpeg" alt="Footer Logo">
        </div>
        <div class="footerBottom">
            <div class="footerNav">
                <a href="#">About</a>
                <a href="#">Contact Us</a>
                <a href="#">Privacy Policy</a>
            </div>
            <p>Copyright &copy;2024</p>
        </div>
    </footer>
</body>
</html>
