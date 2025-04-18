<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (!isset($_POST['room_id'])) {
        $errorMessage = "Room ID not provided.";
    } else {
        $student_number = $_SESSION['student_number'];
        $room_id = intval($_POST['room_id']);

        $checkQuery = "SELECT * FROM bookings WHERE student_number = $student_number AND status = 'Pending'";
        $checkResult = mysqli_query($conn, $checkQuery);

        if (!$checkResult) {
            die("SQL Error: " . mysqli_error($conn));
        }

        if (mysqli_num_rows($checkResult) > 0) {
            $errorMessage = "You already have a pending reservation!";
        } else {
            $insertQuery = "INSERT INTO bookings (student_number, room_id, status)
                            VALUES ($student_number, $room_id, 'Pending')";
            if (mysqli_query($conn, $insertQuery)) {
                $successMessage = "Room booking request submitted successfully!";
                
                $updateRoomQuery = "UPDATE rooms SET availability = 'Not Available' WHERE room_id = $room_id";
                mysqli_query($conn, $updateRoomQuery);
            } else {
                $errorMessage = "Error: " . mysqli_error($conn);
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Book Room</title>
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
    <h1>Book Room</h1>
    <?php if (isset($successMessage)) { ?>
        <div class="message success"><?php echo htmlspecialchars($successMessage); ?></div>
    <?php } elseif (isset($errorMessage)) { ?>
        <div class="message error"><?php echo htmlspecialchars($errorMessage); ?></div>
    <?php } ?>
    <a href="dormsRooms.php?dorm_id=<?php echo $_POST['dorm_id'] ?? ''; ?>">Back to Dorms</a>
</div>
</body>
</html>
