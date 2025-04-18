<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require 'connect.php';

if (isset($_POST['submit'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password']; 
    $confirm_password = $_POST['confirm_password'];
    $full_name = $_POST['full_name']; 
    $student_number = $_POST['student_number']; 

    $duplicate = mysqli_query($conn, "SELECT * FROM user WHERE username='$username'");
    if (mysqli_num_rows($duplicate) > 0) {
        echo "<script> alert('Username Has Already Taken'); </script>";
    } else {

        if ($password == $confirm_password) {
           
            $query = "INSERT INTO user (username, email, password, role, full_name, student_number) 
                      VALUES ('$username', '$email', '$password', 'student', '$full_name', '$student_number')";
            if (mysqli_query($conn, $query)) {
                echo "<script> alert('Registration Successful');</script>";
            } else {
                echo "<script> alert('Error: Could not execute query.');</script>";
            }
        } else {
            echo "<script> alert('Password Does Not Match');</script>";
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
    <title>Register</title>
    <script src="js/script.js" defer></script>
</head>
<body id="background" style="background-image: url('img/backgroundRegister.jpeg'); background-size: 80%;">

    <div class="container">
        <div class="box">
            <img src="img/logo.jpeg">

            <div class="form-container">
                <form action="" method="post">
                    <div class="form-group">
                        <label for="full_name">Full Name:</label>
                        <input type="text" id="full_name" name="full_name" required>
                    </div>

                    <div class="form-group">
                        <label for="username">User Name:</label>
                        <input type="text" id="username" name="username" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="password">Password:</label>
                        <input type="password" id="password" name="password" required>
                    </div>

                    <div class="form-group">
                        <label for="confirm_password">Confirm Password:</label>
                        <input type="password" id="confirm_password" name="confirm_password" required>
                    </div>

                    <div class="form-group">
                        <label for="student_number">Student Number:</label>
                        <input type="text" id="student_number" name="student_number" required>
                    </div>

                    <div class="button-group">
                        <button type="submit" name="submit">Sign Up</button>
                        <button type="button" onclick="window.location.href='login.php'">Log In</button>
                        <button type="reset">Clear</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
