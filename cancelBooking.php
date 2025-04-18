<?php
require 'connect.php';
session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['booking_id'])) {
    $booking_id = intval($_GET['booking_id']);

    $query = "UPDATE bookings SET status = 'Canceled' WHERE booking_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $booking_id);

    if ($stmt->execute()) {
        header("Location: all_bookings.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }
} else {
    echo "Invalid booking ID.";
}
?>