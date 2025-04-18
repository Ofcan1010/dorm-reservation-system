<?php
session_start();
include 'connect.php';

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    $query = "DELETE FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $room_id);

    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error deleting room.";
    }
} else {
    echo "Invalid request.";
}
?>