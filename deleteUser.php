<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $student_number = $_GET['id'];

    $checkRoleQuery = "SELECT role FROM user WHERE student_number = '$student_number'";
    $result = mysqli_query($conn, $checkRoleQuery);

    if ($result) {
        $row = mysqli_fetch_assoc($result);
        $role = $row['role'];

        if ($role === 'student') {
            $deleteQuery = "DELETE FROM user WHERE student_number = '$student_number'";

            if (mysqli_query($conn, $deleteQuery)) {
                header("Location: admin.php");
                exit();
            } else {
                echo "Error: Could not execute delete query. " . mysqli_error($conn);
            }
        } else {
            echo "Error: The user is not a student or doesn't exist.";
        }
    } else {
        echo "Error: Could not prepare statement to check user role.";
    }
} else {
    echo "Error: No user ID provided.";
}
?>
