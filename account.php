<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

$student_number = $_SESSION['student_number'];

// Öğrencinin aktif rezervasyonunu sorgula
$reservationQuery = "SELECT r.room_id, r.type, r.size, r.features, r.price, b.booking_date, b.status 
                     FROM bookings b
                     JOIN rooms r ON b.room_id = r.room_id
                     WHERE b.student_number = $student_number AND b.status = 'Pending'";
$reservationResult = mysqli_query($conn, $reservationQuery);

// Rezervasyon bilgilerini al
$reservation = mysqli_fetch_assoc($reservationResult);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    
    <title>Account</title>
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
        .reservation-details {
            margin-top: 20px;
        }
        .price-info {
            font-size: 1.2em;
            color: #28a745;
        }
        .installments {
            margin-top: 10px;
            padding: 10px;
            background-color: #f1f1f1;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<header class="navigator">
    <ul>
      <li><img src="img/logo.jpeg" alt="Logo"></li>
      <li><a href="student.php">Home</a></li>
      <li><a href="booking.php">Bookings</a></li>
      <li><a href="account.php">Account</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
</header>
<div class="container">
    <h1>Account</h1>
    <?php if ($reservation) { ?>
        <div class="reservation-details">
            <h2>Reservation Details</h2>
            <p><strong>Room Number:</strong> <?php echo htmlspecialchars($reservation['room_id']); ?></p>
            <p><strong>Type:</strong> <?php echo htmlspecialchars($reservation['type']); ?></p>
            <p><strong>Size:</strong> <?php echo htmlspecialchars($reservation['size']); ?> m²</p>
            <p><strong>Features:</strong> <?php echo htmlspecialchars($reservation['features']); ?></p>
            <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($reservation['booking_date']); ?></p>
            <p class="price-info"><strong>Total Price:</strong> $<?php echo htmlspecialchars($reservation['price']); ?></p>
            
            <div class="installments">
                <h3>Installment Plan</h3>
                <p>3 Installments: $<?php echo number_format($reservation['price'] / 3, 2); ?> per installment</p>
                <p>6 Installments: $<?php echo number_format($reservation['price'] / 6, 2); ?> per installment</p>
                <p>12 Installments: $<?php echo number_format($reservation['price'] / 12, 2); ?> per installment</p>
            </div>
        </div>
    <?php } else { ?>
        <p>You have no active reservations.</p>
    <?php } ?>
</div>
</body>
</html>