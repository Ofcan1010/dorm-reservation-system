<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require 'connect.php';

if (!isset($_SESSION['student_number'])) {
    echo "Student number is not set in the session.";
    exit();
}

$student_number = $_SESSION['student_number'];

$query = "SELECT b.booking_id, b.room_id, b.booking_date, b.status, r.type, r.price
          FROM bookings b
          LEFT JOIN rooms r ON b.room_id = r.room_id
          WHERE b.student_number = '$student_number'";
$result = mysqli_query($conn, $query);

if (!$result) {
    echo "SQL Error: " . mysqli_error($conn);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

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
    <link rel="stylesheet" href="style.css">
    
    <title>My Bookings</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }

        th, td {
            padding: 10px;
            text-align: left;
            border: 1px solid #ddd;
        }

        th {
            background-color: #f4f4f4;
        }

        .btn-cancel {
            color: white;
            background-color: red;
            padding: 5px 10px;
            text-decoration: none;
            border-radius: 5px;
        }

        .message {
            text-align: center;
            padding: 10px;
            margin-top: 20px;
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
<header class="navigator">
    <ul>
      <li><img src="img/logo.jpeg" alt="Logo"></li>
      <li><a href="student.php">Home</a></li>
      <li><a href="booking.php">Bookings</a></li>
      <li><a href="account.php">Account</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
</header>
    <h1>My Bookings</h1>

    <?php if (isset($successMessage)) { ?>
        <div class="message success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php } elseif (isset($errorMessage)) { ?>
        <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php } ?>

    <?php if (mysqli_num_rows($result) > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Booking ID</th>
                    <th>Room ID</th>
                    <th>Room Type</th>
                    <th>Price</th>
                    <th>Booking Date</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['booking_id'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['room_id'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['type'] ?? ''); ?></td>
                        <td>$<?php echo htmlspecialchars($row['price'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['booking_date'] ?? ''); ?></td>
                        <td><?php echo htmlspecialchars($row['status'] ?? ''); ?></td>
                        <td>
                            <?php if ($row['status'] === 'Pending'): ?>
                                <a class="btn-cancel" href="booking.php?booking_id=<?php echo $row['booking_id']; ?>">Cancel</a>
                            <?php else: ?>
                                N/A
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no bookings.</p>
    <?php endif; ?>
</body>
</html>
