
<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit();
}

require 'connect.php'; 

$manager_id = $_SESSION['manager_id'];
$successMessage = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $type = mysqli_real_escape_string($conn, $_POST['type']);
    $size = intval($_POST['size']);
    $features = mysqli_real_escape_string($conn, $_POST['features']);
    $front_view = mysqli_real_escape_string($conn, $_POST['front_view']);
    $price = floatval($_POST['price']);
    $availability = mysqli_real_escape_string($conn, $_POST['availability']);
    $dorm_id = intval($_POST['dorm_id']);

    $authQuery = "SELECT dorm_id FROM dorms WHERE dorm_id = ? AND dorm_id IN (SELECT dorm_id FROM managers WHERE manager_id = ?)";
    $stmt = $conn->prepare($authQuery);
    $stmt->bind_param("ii", $dorm_id, $manager_id);
    $stmt->execute();
    $authResult = $stmt->get_result();

    if ($authResult->num_rows > 0) {
        // Yöneticinin yetkisi varsa, oda ekle
        $insertQuery = "INSERT INTO rooms (type, size, features, front_view, price, availability, dorm_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("sissdsi", $type, $size, $features, $front_view, $price, $availability, $dorm_id);

        if ($stmt->execute()) {
            $successMessage = "Room added successfully!";
        } else {
            $errorMessage = "Error: " . $stmt->error;
        }
    } else {
        $errorMessage = "You do not have permission to add a room to this dorm.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Add Room</title>
</head>
<body>
<header class="navigator">
    <ul>
        <li><img src="img/logo.jpeg" alt="Logo"></li>
        <li><a href="manager.php">Manager Dashboard</a></li>
    </ul>
</header>
<div class="container">
    <h1>New Room</h1>
    <?php if (!empty($successMessage)) { ?>
        <div class="message success"><?php echo $successMessage; ?></div>
    <?php } elseif (!empty($errorMessage)) { ?>
        <div class="message error"><?php echo $errorMessage; ?></div>
    <?php } ?>
    <form method="POST" action="addRoom.php">
        <label for="type">Room Type:</label>
        <input type="text" name="type" id="type" required>

        <label for="size">Room Size (m²):</label>
        <input type="number" name="size" id="size" min="1" required>

        <label for="features">Features:</label>
        <textarea name="features" id="features" rows="4" required></textarea>

        <label for="front_view">Front View:</label>
        <input type="text" name="front_view" id="front_view" required>

        <label for="price">Price (₺):</label>
        <input type="number" name="price" id="price" step="0.01" required>

        <label for="availability">Availability:</label>
        <select name="availability" id="availability" required>
            <option value="Available">Available</option>
            <option value="Not Available">Not Available</option>
        </select>

        <label for="dorm_id">Select Dorm:</label>
        <select name="dorm_id" id="dorm_id" required>
            <?php
            
            $dormQuery = "SELECT dorm_id, dormName FROM dorms WHERE dorm_id IN (SELECT dorm_id FROM managers WHERE manager_id = ?)";
            $stmt = $conn->prepare($dormQuery);
            $stmt->bind_param("i", $manager_id);
            $stmt->execute();
            $dormResult = $stmt->get_result();
            while ($dorm = $dormResult->fetch_assoc()) {
                echo "<option value='" . $dorm['dorm_id'] . "'>" . $dorm['dormName'] . "</option>";
            }
            ?>
        </select>

        <button type="submit">Add Room</button>
    </form>
</div>
</body>
</html>