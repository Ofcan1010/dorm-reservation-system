
<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
require 'connect.php';


if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}


$manager_id = $_SESSION['manager_id'];
$query = "SELECT name, username FROM managers WHERE manager_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $manager_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $row = $result->fetch_assoc()) {
    $manager_name = htmlspecialchars($row['name']);
    $manager_username = htmlspecialchars($row['username']);
} else {
    echo "Error fetching manager details.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Manager Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-image: url('img/backgroundStudent.jpeg');
            background-size: cover;
            margin: 0;
            padding: 0;
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
        #managerDiv {
            margin: 40px auto;
            padding: 20px;
            max-width: 80%;
            background-color: rgba(255, 255, 255, 0.95);
            border-radius: 8px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            text-align: center;
        }
        h1 {
            color: #444;
            font-size: 28px;
        }
        .button-group {
            margin-top: 20px;
        }
        .button-group form button {
            background-color: #007BFF;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .button-group form button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
<header class="navigator">
    <ul>
        <li><img src="img/logo.jpeg" alt="Logo"></li>
        <li><a href="manager.php">Home</a></li>
        <li><a href="managerRooms.php">Rooms</a></li>
        <li><a href="all_bookings.php">Financials</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</header>

<div id="managerDiv">
    <h1>Welcome, <?php echo $manager_name; ?>!</h1>
    <div class="button-group">
        <!-- Add Room yönlendirmesi -->
        <form action="addRoom.php" method="get">
            <button type="submit">Add Room</button>
        </form>
    </div>
</div>
</body>
</html>