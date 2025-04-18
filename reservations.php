<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

$student_number = $_SESSION['student_number']; // Oturumdaki öğrenci numarası


$reservationsQuery = "SELECT r.room_id, r.type, r.size, r.features, r.price, b.booking_id, b.status, b.booking_date 
                      FROM bookings b
                      JOIN rooms r ON b.room_id = r.room_id
                      WHERE b.student_number = $student_number";
$reservationsResult = mysqli_query($conn, $reservationsQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = intval($_POST['booking_id']);
    
    $cancelQuery = "UPDATE bookings SET status = 'Canceled' WHERE booking_id = $booking_id";
    $updateRoomQuery = "UPDATE rooms SET availability = 'Available' 
                        WHERE room_id = (SELECT room_id FROM bookings WHERE booking_id = $booking_id)";
    
    if (mysqli_query($conn, $cancelQuery) && mysqli_query($conn, $updateRoomQuery)) {
        $successMessage = "Reservation canceled successfully.";
    } else {
        $errorMessage = "Error canceling reservation: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Reservations</title>
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
        h1 {
            color: #333;
        }
        .reservation {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .reservation h3 {
            margin: 0;
            color: #555;
        }
        .reservation p {
            margin: 5px 0;
        }
        .cancel-button {
            margin-top: 10px;
            display: inline-block;
            padding: 10px 15px;
            background-color: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            border: none;
        }
        .cancel-button:hover {
            background-color: #c82333;
        }
        .message {
            margin-top: 20px;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .success {
            background-color: #28a745;
            color: white;
        }
        .error {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body>
<div class="container">
    <h1>Your Reservations</h1>
    <?php if (isset($successMessage)) { ?>
        <div class="message success"><?php echo $successMessage; ?></div>
    <?php } elseif (isset($errorMessage)) { ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
    <?php } ?>

    <?php if (mysqli_num_rows($reservationsResult) > 0) { ?>
        <?php while ($reservation = mysqli_fetch_assoc($reservationsResult)) { ?>
            <div class="reservation">
                <h3>Room Number: <?php echo htmlspecialchars($reservation['room_id']); ?></h3>
                <p><strong>Type:</strong> <?php echo htmlspecialchars($reservation['type']); ?></p>
                <p><strong>Size:</strong> <?php echo htmlspecialchars($reservation['size']); ?> m²</p>
                <p><strong>Features:</strong> <?php echo htmlspecialchars($reservation['features']); ?></p>
                <p><strong>Price:</strong> $<?php echo htmlspecialchars($reservation['price']); ?></p>
                <p><strong>Status:</strong> <?php echo htmlspecialchars($reservation['status']); ?></p>
                <p><strong>Booking Date:</strong> <?php echo htmlspecialchars($reservation['booking_date']); ?></p>
                <?php if ($reservation['status'] === 'Pending' || $reservation['status'] === 'Confirmed') { ?>
                    <form method="POST" action="reservations.php">
                        <input type="hidden" name="booking_id" value="<?php echo htmlspecialchars($reservation['booking_id']); ?>">
                        <button type="submit" class="cancel-button">Cancel Reservation</button>
                    </form>
                <?php } ?>
            </div>
        <?php } ?>
    <?php } else { ?>
        <p>No reservations found.</p>
    <?php } ?>
</div>
</body>
</html>
