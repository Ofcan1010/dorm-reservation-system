<?php
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $dorm_id = mysqli_real_escape_string($conn, $_POST['dorm_id']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']); 

    $checkDormQuery = "SELECT * FROM managers WHERE dorm_id = '$dorm_id'";
    $checkResult = mysqli_query($conn, $checkDormQuery);

    if (mysqli_num_rows($checkResult) > 0) {
        echo "<script>alert('This dormitory already has a manager. Please select a different dormitory.');</script>";
    } else {

        $query = "INSERT INTO managers (username, name, surname, dorm_id, phone, email, password) 
                  VALUES ('$username', '$name', '$surname', '$dorm_id', '$phone', '$email', '$password')";

        if (mysqli_query($conn, $query)) {
            echo "<script>alert('Manager added successfully!');</script>";
        } else {
            echo "<script>alert('Error adding manager: " . mysqli_error($conn) . "');</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body id="background">
    <header class="navigator">
        <ul>
        <li><a href="admin.php">Admin Dashboard</a></li>
        </ul>
    </header>

    <div class="form-container">
        <form method="post" action="managerForm.php" style="max-width: 500px; margin: 0 auto; padding: 20px; background-color:white; border-radius: 10px;">
            <div class="form-group" style="margin-bottom: 15px;">
                <label for="username" style="color: black; font-weight: bold; display: block; margin-bottom: 5px;">Username:</label>
                <input type="text" id="username" name="username" style="width: 100%; padding: 10px; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="name" style="color: black; font-weight: bold; display: block; margin-bottom: 5px;">Name:</label>
                <input type="text" id="name" name="name" style="width: 100%; padding: 10px; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="surname" style="color: black; font-weight: bold; display: block; margin-bottom: 5px;">Surname:</label>
                <input type="text" id="surname" name="surname" style="width: 100%; padding: 10px; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="dorm_id" style="color: black; font-weight: bold; display: block; margin-bottom: 5px;">Dormitory:</label>
                <select id="dorm_id" name="dorm_id" style="width: 100%; padding: 10px; border-radius: 5px;" required>
                    <?php
                    $dormQuery = "SELECT dorm_id, dormName FROM dorms";
                    $dormResult = mysqli_query($conn, $dormQuery);

                    while ($row = mysqli_fetch_assoc($dormResult)) {
                        echo "<option value='" . $row['dorm_id'] . "'>" . $row['dormName'] . "</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="phone" style="color: black; font-weight: bold; display: block; margin-bottom: 5px;">Phone:</label>
                <input type="tel" id="phone" name="phone" style="width: 100%; padding: 10px; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 15px;">
                <label for="email" style="color: black; font-weight: bold; display: block; margin-bottom: 5px;">Email:</label>
                <input type="email" id="email" name="email" style="width: 100%; padding: 10px; border-radius: 5px;" required>
            </div>

            <div class="form-group" style="margin-bottom: 20px;">
                <label for="password" style="color: black; font-weight: bold; display: block; margin-bottom: 5px;">Password:</label>
                <input type="password" id="password" name="password" style="width: 100%; padding: 10px; border-radius: 5px;" required>
            </div>

            <div class="button-group" style="display: flex; justify-content: space-between;">
                <button type="submit" style="padding: 10px 20px; border-radius: 5px; background-color: #4CAF50; color: white; border: none;">Submit</button>
                <button type="reset" style="padding: 10px 20px; border-radius: 5px; background-color: #f44336; color: white; border: none;">Reset</button>
            </div>
        </form>
    </div>
</body>
</html>

