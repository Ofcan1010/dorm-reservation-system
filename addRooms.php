<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require 'connect.php'; // Veritabanı bağlantısı

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $size = intval($_POST['size']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $front_view = mysqli_real_escape_string($conn, $_POST['front_view']);
    $price = floatval($_POST['price']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);
    $dorm_id = intval($_POST['dorm_id']);

    $insertQuery = "INSERT INTO rooms (type, size, features, front_view, price, availability, dorm_id)
                    VALUES ('$type', $size, '$features', '$front_view', $price, '$availability', $dorm_id)";

    if (mysqli_query($conn, $insertQuery)) {
        $successMessage = "Room added successfully!";
    } else {
        $errorMessage = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Room</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 10px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            width: 50%;
            margin: auto;
        }
        h1 {
            color: #333;
        }
        form {
            display: flex;
            flex-direction: column;
        }
        label {
            margin-top: 10px;
        }
        input, select, textarea, button {
            margin-top: 5px;
            padding: 10px;
            font-size: 1em;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        button {
            background-color: #28a745;
            color: white;
            cursor: pointer;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            color: white;
            text-align: center;
            border-radius: 5px;
        }
        .success {
            background-color: #28a745;
        }
        .error {
            background-color: #dc3545;
        }
    </style>
</head>
<body>
<header class="navigator">
    <ul>
        <li><img src="img/logo.jpeg" alt="Logo"></li>
        <li><a href="admin.php">Admin Dashboard</a></li>
    </ul>
</header>
<div class="container">
    <h1>New Room</h1>
    <?php if (isset($successMessage)) { ?>
        <div class="message success"><?php echo $successMessage; ?></div>
    <?php } elseif (isset($errorMessage)) { ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
    <?php } ?>
    <form method="POST" action="addRooms.php">
        <label for="type">Room Type:</label>
        <input type="text" name="type" id="type" required>

        <label for="size">Room Size (m²):</label>
        <input type="number" name="size" id="size" min="1" required>

        <label for="features">Features:</label>
        <textarea name="features" id="features" rows="4" required></textarea>

        <label for="front_view">Front View:</label>
        <input type="text" name="front_view" id="front_view" required>

        <label for="price">Price (₺):</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="availability">Availability:</label>
        <select name="availability" id="availability" required>
            <option value="Available">Available</option>
            <option value="Not Available">Not Available</option>
        </select>

        <label for="dorm_id">Select Dorm:</label>
        <select name="dorm_id" id="dorm_id" required>
            <?php
            $dormQuery = "SELECT dorm_id, dormName FROM dorms";
            $dormResult = mysqli_query($conn, $dormQuery);
            while ($dorm = mysqli_fetch_assoc($dormResult)) {
                echo "<option value='" . $dorm['dorm_id'] . "'>" . $dorm['dormName'] . "</option>";
            }
            ?>
        </select>

        <button type="submit">Add Room</button>
    </form>
</div>
</body>
</html>
