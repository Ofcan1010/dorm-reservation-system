<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $manager_id = $_GET['id'];

    $checkRoleQuery = "SELECT * FROM managers WHERE manager_id = '$manager_id'";
    $result = mysqli_query($conn, $checkRoleQuery);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
            $deleteQuery = "DELETE FROM managers WHERE manager_id = '$manager_id'";

            if (mysqli_query($conn, $deleteQuery)) {
                header("Location: admin.php");
                exit();
            } else {
                echo "Error: Could not execute delete query. " . mysqli_error($conn);
            }
        } else {
            echo "Error: The manager doesn't exist.";
        }
    } else {
        echo "Error: Could not prepare statement to check user role.";
    }
} else {
    echo "Error: No user ID provided.";
}
?>
