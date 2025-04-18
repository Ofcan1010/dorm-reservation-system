<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

if (isset($_GET['dorm_id'])) {
    $dorm_id = intval($_GET['dorm_id']);
} else {
    echo "Dorm ID not provided.";
    exit();
}

// Yurt bilgilerini sorgula
$dormQuery = "SELECT * FROM dorms WHERE dorm_id = $dorm_id";
$dormResult = mysqli_query($conn, $dormQuery);
$dorm = mysqli_fetch_assoc($dormResult);

if (!$dorm) {
    echo "Dorm not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($dorm['dormName']); ?> - Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
        }
        .container {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }
        h1, h2 {
            color: #333;
        }
        p {
            margin: 10px 0;
        }
        .room {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .room h3 {
            margin: 0;
            color: #555;
        }
        .room p {
            margin: 5px 0;
        }
        .book-button {
            margin-top: 10px;
            display: inline-block;
            padding: 10px 15px;
            background-color: #28a745;
            color: white;
            text-decoration: none;
            border-radius: 5px;
        }
        .book-button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Dorm Details</h1>
    <p><strong>Dorm Name:</strong> <?php echo htmlspecialchars($dorm['dormName']); ?></p>
    <p><strong>Location:</strong> <?php echo htmlspecialchars($dorm['dormLocation']); ?></p>
    <p><strong>Facilities:</strong> <?php echo htmlspecialchars($dorm['dormFacilities']); ?></p>
    <p><strong>Capacity:</strong> <?php echo htmlspecialchars($dorm['dormCapacity']); ?> students</p>

    <h2>Rooms in <?php echo htmlspecialchars($dorm['dormName']); ?></h2>
    <?php
    // Odaları sorgula
    $roomQuery = "SELECT * FROM rooms WHERE dorm_id = $dorm_id";
    $roomResult = mysqli_query($conn, $roomQuery);

    if (mysqli_num_rows($roomResult) > 0) {
        while ($room = mysqli_fetch_assoc($roomResult)) {
            echo "<div class='room'>";
            echo "<h3>Room Number: " . htmlspecialchars($room['room_id']) . "</h3>";
            echo "<p><strong>Type:</strong> " . htmlspecialchars($room['type']) . "</p>";
            echo "<p><strong>Size:</strong> " . htmlspecialchars($room['size']) . " m²</p>";
            echo "<p><strong>Features:</strong> " . htmlspecialchars($room['features']) . "</p>";
            echo "<p><strong>Front View:</strong> " . htmlspecialchars($room['front_view']) . "</p>";
            echo "<p><strong>Price:</strong> $" . htmlspecialchars($room['price']) . "</p>";
            echo "<p><strong>Availability:</strong> " . htmlspecialchars($room['availability']) . "</p>";
            if ($room['availability'] === 'Available') {
                // Form ekle
                echo "<form method='POST' action='bookRoom.php'>
                          <input type='hidden' name='room_id' value='" . htmlspecialchars($room['room_id']) . "'>
                          <input type='hidden' name='dorm_id' value='" . htmlspecialchars($room['dorm_id']) . "'>
                          <button type='submit' class='book-button'>Book Now</button>
                      </form>";
            }
            echo "</div>";
        }
    } else {
        echo "<p>No rooms available in this dorm.</p>";
    }
    ?>
</div>
</body>
</html>
