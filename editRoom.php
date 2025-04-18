<?php
session_start();
include 'connect.php';

if (isset($_GET['room_id'])) {
    $room_id = $_GET['room_id'];

    $query = "SELECT * FROM rooms WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $room_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $room = $result->fetch_assoc();

    if (!$room) {
        echo "Room not found.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $type = $_POST['type'];
    $size = $_POST['size'];
    $features = $_POST['features'];
    $front_view = $_POST['front_view'];
    $price = $_POST['price'];
    $availability = $_POST['availability'];

    $query = "UPDATE rooms SET type = ?, size = ?, features = ?, front_view = ?, price = ?, availability = ? WHERE room_id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('sissdsi', $type, $size, $features, $front_view, $price, $availability, $room_id);
    if ($stmt->execute()) {
        header("Location: admin.php");
        exit();
    } else {
        echo "Error updating room.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Edit Room</title>
</head>
<body>
<header class="navigator">
    <ul>
        <li><img src="img/logo.jpeg" alt="Logo"></li>
        <li><a href="admin.php">Admin Dashboard</a></li>
    </ul>
</header>
    <div class="container">
        <h1 class="title">Edit Room</h1>
        <form method="POST" class="form-container">
            <label for="type">Type:</label>
            <input type="text" id="type" name="type" value="<?php echo htmlspecialchars($room['type']); ?>" required>

            <label for="size">Size:</label>
            <input type="number" id="size" name="size" value="<?php echo htmlspecialchars($room['size']); ?>" required>

            <label for="features">Features:</label>
            <input type="text" id="features" name="features" value="<?php echo htmlspecialchars($room['features']); ?>" required>

            <label for="front_view">Front View:</label>
            <input type="text" id="front_view" name="front_view" value="<?php echo htmlspecialchars($room['front_view']); ?>" required>

            <label for="price">Price:</label>
            <input type="number" step="0.01" id="price" name="price" value="<?php echo htmlspecialchars($room['price']); ?>" required>

            <label for="availability">Availability:</label>
            <select id="availability" name="availability" required>
                <option value="Available" <?php echo $room['availability'] === 'Available' ? 'selected' : ''; ?>>Available</option>
                <option value="Not Available" <?php echo $room['availability'] === 'Not Available' ? 'selected' : ''; ?>>Not Available</option>
            </select>

            <button type="submit" class="btn">Update Room</button>
        </form>
    </div>
</body>
</html>