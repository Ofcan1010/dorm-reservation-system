<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $dorm_id = $_GET['id'];

    $query = "SELECT * FROM dorms WHERE dorm_id = '$dorm_id'";
    $result = mysqli_query($conn, $query);
    
    if ($result) {
        $row = mysqli_fetch_assoc($result);
        if (!$row) {
            echo "Error: Yurt bulunamadı!";
            exit();
        }
    } else {
        echo "Error: Veritabanından yurt bilgileri çekilemedi.";
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $dormName = mysqli_real_escape_string($conn, $_POST['dormName']);
    $dormCapacity = mysqli_real_escape_string($conn, $_POST['dormCapacity']);
    $dormLocation = mysqli_real_escape_string($conn, $_POST['dormLocation']);
    $dormFacilities = mysqli_real_escape_string($conn, $_POST['dormFacilities']);
    
    $updateQuery = "UPDATE dorms SET dormName = '$dormName', dormCapacity = '$dormCapacity', dormLocation = '$dormLocation', dormFacilities = '$dormFacilities' WHERE dorm_id = '$dorm_id'";

    if (mysqli_query($conn, $updateQuery)) {
        echo "<div class='success-message'>Yurt bilgileri başarıyla güncellendi.</div>";
        header("Location: admin.php");  // Güncellenen bilgilerle yönlendirme
        exit();
    } else {
        echo "<div class='error-message'>Error: Yurt bilgileri güncellenemedi. " . mysqli_error($conn) . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dorm</title>
    <link rel="stylesheet" href="style.css">
    <style>

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .navigator {
            background-color: #333;
            padding: 10px;
        }

        .navigator ul {
            list-style: none;
            margin: 0;
            padding: 0;
            text-align: center;
        }

        .navigator ul li {
            display: inline;
            margin-right: 20px;
        }

        .navigator ul li a {
            color: white;
            text-decoration: none;
            font-size: 18px;
        }

        #adminDiv {
            margin: 20px auto;
            padding: 30px;
            max-width: 600px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .form-group input {
            width: 100%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .form-group input:focus {
            border-color: #007BFF;
            outline: none;
        }

        .btn {
            display: inline-block;
            padding: 10px 20px;
            background-color: #007BFF;
            color: white;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
            margin-top: 10px;
            width: 100%;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .success-message {
            background-color: #28a745;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .error-message {
            background-color: #dc3545;
            color: white;
            padding: 10px;
            text-align: center;
            border-radius: 5px;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<header class="navigator">
    <ul>
        <li><a href="admin.php">Admin Dashboard</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</header>

<div id="adminDiv">
    <h2 class="section-title">Edit Dorm</h2>

    <!-- Success or Error Message -->
    <?php if (isset($message)) { echo $message; } ?>

    <form method="POST">
        <div class="form-group">
            <label for="dormName">Dorm Name:</label>
            <input type="text" name="dormName" id="dormName" value="<?php echo $row['dormName']; ?>" required>
        </div>

        <div class="form-group">
            <label for="dormCapacity">Capacity:</label>
            <input type="number" name="dormCapacity" id="dormCapacity" value="<?php echo $row['dormCapacity']; ?>" required>
        </div>

        <div class="form-group">
            <label for="dormLocation">Location:</label>
            <input type="text" name="dormLocation" id="dormLocation" value="<?php echo $row['dormLocation']; ?>" required>
        </div>

        <div class="form-group">
            <label for="dormFacilities">Facilities:</label>
            <input type="text" name="dormFacilities" id="dormFacilities" value="<?php echo $row['dormFacilities']; ?>" required>
        </div>

        <button type="submit" class="btn">Update Dorm</button>
    </form>
</div>

</body>
</html>
