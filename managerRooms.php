<?php
require 'connect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

if (!isset($_SESSION['manager_id'])) {
    echo "Error: Session manager_id is not set.";
    exit();
}

$manager_id = $_SESSION['manager_id'];

$query = "SELECT dorm_id FROM managers WHERE manager_id = ?";
$stmt = $conn->prepare($query);
if (!$stmt) {
    echo "Error preparing statement: " . $conn->error;
    exit();
}
$stmt->bind_param('i', $manager_id);

if ($stmt->execute()) {
    $result = $stmt->get_result();
    if ($result && $row = $result->fetch_assoc()) {
        $dorm_id = $row['dorm_id'];
        
        if (empty($dorm_id)) {
            echo "Error: No dorm assigned to this manager (dorm_id is NULL or empty).";
            exit();
        }


        $roomQuery = "SELECT * FROM rooms WHERE dorm_id = ?";
        $stmt = $conn->prepare($roomQuery);
        if (!$stmt) {
            echo "Error preparing room query: " . $conn->error;
            exit();
        }
        $stmt->bind_param('i', $dorm_id);
        $stmt->execute();
        $roomsResult = $stmt->get_result();

    } else {
        echo "Error: Manager with ID $manager_id has no dorm assigned.";
        exit();
    }
} else {
    echo "Error executing dorm query: " . $stmt->error;
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Manager Rooms</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('img/backgroundStudent.jpeg');
            background-size: cover;
            background-attachment: fixed;
        }
        .navigator {
            background-color: #333;
            color: white;
            padding: 10px;
        }
        .navigator ul {
            list-style: none;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
        }
        .navigator ul li {
            margin: 0 20px;
        }
        .navigator ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }
        #roomDiv {
            margin: 40px auto;
            padding: 30px;
            max-width: 90%;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            color: #444;
            font-size: 28px;
            margin-bottom: 20px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #007BFF;
            color: white;
            text-transform: uppercase;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .table td {
            color: #555;
        }
        .table td.price {
            font-weight: bold;
            color: #007BFF;
        }
    </style>
</head>
<body>
<header class="navigator">
    <ul>
        <li><a href="manager.php">Home</a></li>
        <li><a href="all_bookings.php">Financials</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</header>

<div id="roomDiv">
    <h1>Rooms</h1>
    <table class="table">
        <tr>
            <th>Room ID</th>
            <th>Type</th>
            <th>Size</th>
            <th>Features</th>
            <th>Front View</th>
            <th>Price</th>
            <th>Availability</th>
        </tr>
        <?php
        if ($roomsResult && mysqli_num_rows($roomsResult) > 0) {
            while ($room = mysqli_fetch_assoc($roomsResult)) {
                echo "<tr>
                        <td>{$room['room_id']}</td>
                        <td>{$room['type']}</td>
                        <td>{$room['size']} m²</td>
                        <td>{$room['features']}</td>
                        <td>{$room['front_view']}</td>
                        <td class='price'>\${$room['price']}</td>
                        <td>{$room['availability']}</td>
                      </tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'>No rooms assigned to your dorm.</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>