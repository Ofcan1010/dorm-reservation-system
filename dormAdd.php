<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Dorm</title>
    <link rel="stylesheet" href="style.css">

    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: black;
            font-size: 18px;
            font-weight: bold;
        }

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .button-group button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        .button-group button:hover {
            background-color: #0056b3;
        }

        .alert {
            padding: 15px;
            margin-top: 20px;
            border-radius: 4px;
            font-size: 16px;
            text-align: center;
        }

        .alert-success {
            background-color: #28a745;
            color: white;
        }

        .alert-danger {
            background-color: #dc3545;
            color: white;
        }
    </style>
</head>
<body id="background">
    <header class="navigator">
        <ul>
            <li><a href="admin.php">Admin Dashboard</a></li>
        </ul>
    </header>

    <div class="form-container">
        <h1 class="text-center" style="color: black; font-size: 24px;">Add Dorm</h1>
        <form method="post" action="dormAdd.php" enctype="multipart/form-data">
            <div class="form-group">
                <label for="dormName">Dorm Name</label>
                <input type="text" id="dormName" name="dormName" required>
            </div>
            <div class="form-group">
                <label for="dormCapacity">Dorm Capacity</label>
                <input type="number" id="dormCapacity" name="dormCapacity" required>
            </div>
            <div class="form-group">
                <label for="dormLocation">Dorm Location</label>
                <input type="text" id="dormLocation" name="dormLocation" required>
            </div>
            <div class="form-group">
                <label for="dormFacilities">Dorm Facilities</label>
                <textarea id="dormFacilities" name="dormFacilities" rows="3" required></textarea>
            </div>
            <div class="form-group">
                <label for="dormImage">Dorm Image</label>
                <input type="file" id="dormImage" name="dormImage" accept="image/*">
            </div>
            <div class="button-group">
                <button type="submit">Add Dorm</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>

    <footer class="customFooter">
        <div class="socialIcons">
            <a href="https://www.facebook.com"><i class="fa-brands fa-facebook"></i></a>
            <a href="https://www.instagram.com"><i class="fa-brands fa-instagram"></i></a>
            <a href="https://x.com"><i class="fa-brands fa-twitter"></i></a>
            <a href="https://www.youtube.com"><i class="fa-brands fa-youtube"></i></a>
        </div>


    <?php
    include 'connect.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $dormName = mysqli_real_escape_string($conn, $_POST['dormName']);
        $dormCapacity = mysqli_real_escape_string($conn, $_POST['dormCapacity']);
        $dormLocation = mysqli_real_escape_string($conn, $_POST['dormLocation']);
        $dormFacilities = mysqli_real_escape_string($conn, $_POST['dormFacilities']);

        $sql = "INSERT INTO dorms (dormName, dormCapacity, dormLocation, dormFacilities) 
                VALUES ('$dormName', '$dormCapacity', '$dormLocation', '$dormFacilities')";

        if (mysqli_query($conn, $sql)) {
            echo "<div class='alert alert-success'>New dorm added successfully!</div>";
        } else {
            echo "<div class='alert alert-danger'>Error: " . mysqli_error($conn) . "</div>";
        }
    }
    ?>
</body>
</html>
