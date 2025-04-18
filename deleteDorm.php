<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $dorm_id = $_GET['id']; 

    $checkDormQuery = "SELECT * FROM dorms WHERE dorm_id = '$dorm_id'"; 
    $result = mysqli_query($conn, $checkDormQuery);

    if ($result) {
        $row = mysqli_fetch_assoc($result);

        if ($row) {
        
            $deleteQuery = "DELETE FROM dorms WHERE dorm_id = '$dorm_id'"; 

            if (mysqli_query($conn, $deleteQuery)) {
                header("Location: admin.php");
                exit();
            } else {
                echo "Error: Could not execute delete query. " . mysqli_error($conn); 
            }
        } else {
            echo "Error: The dorm doesn't exist.";
        }
    } else {
        echo "Error: Could not prepare statement to check dorm existence.";
    }
} else {
    echo "Error: No dorm ID provided.";
}
?>
