<?php
require 'connect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

$query = "SELECT b.booking_id, u.full_name AS student_name, r.type AS room_type, r.price, b.status, b.booking_date 
          FROM bookings b
          JOIN rooms r ON b.room_id = r.room_id
          JOIN user u ON b.student_number = u.student_number";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error fetching bookings: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>All Bookings</title>
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
        #studentDiv {
            margin: 20px auto;
            padding: 20px;
            max-width: 90%;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        h1 {
            text-align: center;
            color: #333;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th, .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .table tr:hover {
            background-color: #f1f1f1;
        }
        .action-btn {
            text-decoration: none;
            color: white;
            background-color: #e74c3c; /* Kırmızı buton */
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
        }
        .action-btn:hover {
            background-color: #c0392b; /* Daha koyu kırmızı */
        }
    </style>
</head>
<body>
<header class="navigator">
    <ul>
      <li><img src="img/logo.jpeg"></li>
      <li><a href="manager.php">Home</a></li>
      <li><a href="managerRooms.php">Rooms</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
</header>
<div id="studentDiv">
    <h1>Financials</h1>

    <!-- Booking List -->
    <table class="table">
        <tr>
            <th>Booking ID</th>
            <th>Student Name</th>
            <th>Room Type</th>
            <th>Price</th>
            <th>Status</th>
            <th>Booking Date</th>
            <th>Actions</th>
        </tr>
        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>
                        <td>{$row['booking_id']}</td>
                        <td>{$row['student_name']}</td>
                        <td>{$row['room_type']}</td>
                        <td>\${$row['price']}</td>
                        <td>{$row['status']}</td>
                        <td>{$row['booking_date']}</td>";

                // Eğer durum Canceled değilse iptal butonunu göster
                if ($row['status'] !== 'Canceled') {
                    echo "<td><a href='cancelBooking.php?booking_id={$row['booking_id']}' class='action-btn'>Cancel</a></td>";
                } else {
                    echo "<td>Already Canceled</td>";
                }
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='7' style='text-align: center;'>No bookings available</td></tr>";
        }
        ?>
    </table>
</div>
</body>
</html>