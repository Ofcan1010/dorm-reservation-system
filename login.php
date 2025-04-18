<?php
session_start();
require 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $queryUser = "SELECT username, password, role, student_number FROM user WHERE username = ?";
    $stmtUser = mysqli_prepare($conn, $queryUser);
    mysqli_stmt_bind_param($stmtUser, "s", $username);
    mysqli_stmt_execute($stmtUser);
    $resultUser = mysqli_stmt_get_result($stmtUser);

    if (mysqli_num_rows($resultUser) === 1) {
        $row = mysqli_fetch_assoc($resultUser);


        if ($password === $row['password']) { 
            // Oturum bilgilerini ayarla
            $_SESSION['loggedin'] = true;
            $_SESSION['username'] = $row['username'];
            $_SESSION['role'] = $row['role'];
            $_SESSION['student_number'] = $row['student_number'];

            if ($_SESSION['role'] === 'admin') {
                header("Location: admin.php");
            } elseif ($_SESSION['role'] === 'student') {
                header("Location: student.php");
            }
            exit();
        } else {
            echo "<p class='error'>Incorrect password.</p>";
        }
    } else {

        $queryManager = "SELECT username, password, manager_id FROM managers WHERE username = ?";
        $stmtManager = mysqli_prepare($conn, $queryManager);
        mysqli_stmt_bind_param($stmtManager, "s", $username);
        mysqli_stmt_execute($stmtManager);
        $resultManager = mysqli_stmt_get_result($stmtManager);

        if (mysqli_num_rows($resultManager) === 1) {
            $rowManager = mysqli_fetch_assoc($resultManager);


            if ($password === $rowManager['password']) { 
               
                $_SESSION['loggedin'] = true;
                $_SESSION['username'] = $rowManager['username'];
                $_SESSION['role'] = 'manager';
                $_SESSION['manager_id'] = $rowManager['manager_id'];

                
                header("Location: manager.php");
                exit();
            } else {
                echo "<p class='error'>Incorrect password.</p>";
            }
        } else {
            echo "<p class='error'>User not found.</p>";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            background-image: url('img/backgroundStudent.jpeg');
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        form {
            max-width: 400px;
            width: 100%;
            padding: 20px;
            border: 1px solid #ccc;
            border-radius: 10px;
            background: white;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            gap: 10px;
        }
        button {
            flex: 1;
            padding: 10px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
        .sign-up {
            background-color: #28a745;
        }
        .sign-up:hover {
            background-color: #1e7e34;
        }
        .error {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <form method="POST" action="login.php">
        <h2>Login</h2>
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <div class="button-group">
            <button type="submit">Login</button>
            <button type="button" class="sign-up" onclick="window.location.href='register.php'">Sign Up</button>
        </div>
    </form>
</body>
</html>
