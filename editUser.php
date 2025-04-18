<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $student_number = $_GET['id'];

    $query = "SELECT * FROM user WHERE student_number = '$student_number' AND role = 'student'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $student = mysqli_fetch_assoc($result);
        if (!$student) {
            echo "Student not found!";
            exit();
        }
    } else {
        echo "Unable to fetch student details from the database.";  // Veritabanı hatası
        exit();
    }
}

$successMessage = '';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $updateQuery = "UPDATE user SET full_name = '$full_name', email = '$email' WHERE student_number = '$student_number'";

    if (mysqli_query($conn, $updateQuery)) {
        $successMessage = "Student information updated successfully.";
    } else {
        $errorMessage = "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="style.css">
    <style>

        body {
            font-family: Arial, sans-serif;
        }

        #background {
            background-image: url('img/backgroundAdmin.jpeg'); 
            background-size: cover;
            background-position: center; 
            margin: 0;
            padding: 0;
            height: 100vh;
            width: 100%;
        }

        .form-container {
            max-width: 600px;
            margin: 50px auto;
            padding: 20px;
            background-color: rgba(51, 51, 51, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            color: white;
        }

        .form-container h1 {
            text-align: center;
            font-size: 24px;
            margin-bottom: 20px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            color: white;
            font-size: 18px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input:focus {
            outline: none;
            border-color: #007BFF;
        }

        .button-group {
            display: flex;
            justify-content: space-between;
        }

        .button-group button {
            background-color: #007bff;
            color: white;
            padding: 12px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            width: 48%;
        }

        .button-group button:hover {
            background-color: #0056b3;
        }

        .button-group button[type="reset"] {
            background-color: #28a745;
        }

        .button-group button[type="reset"]:hover {
            background-color: #218838;
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

        footer.customFooter {
            background-color: #A1887F;
            color: #fff;
            text-align: center;
            padding: 15px 0;
        }
        
        .socialIcons a {
            color: #fff;
            font-size: 38px;
            margin: 0 10px;
            text-decoration: none;
        }
        
        .footerLogo img {
            width: 80px;
            margin: 10px auto;
            display: block;
            border-radius: 50%;
        }

        .footerNav a {
            color: #fff;
            margin: 0 10px;
            text-decoration: none;
            font-size: 24px;
        }

        .footerNav a:hover {
            color: gold;
        }

        .socialIcons a:hover {
            color: gold;
        }

        .footerBottom {
            margin-top: 10px;
            font-size: 12px;
        }
    </style>
</head>
<body id="background">
    <header class="navigator">
        <ul>
            <li><img src="img/logo.jpeg" alt="Logo"></li>
            <li><a href="admin.php">Admin Dashboard</a></li>
        </ul>
    </header>

    <div class="form-container">
        <h1>Edit Student</h1>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="editUser.php?id=<?php echo $student['student_number']; ?>">
            <div class="form-group">
                <label for="full_name">Full Name</label>
                <input type="text" id="full_name" name="full_name" value="<?php echo $student['full_name']; ?>" required>
            </div>
        
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $student['email']; ?>" required>
            </div>
            <div class="button-group">
                <button type="submit">Update Student</button>
                <button type="reset">Reset</button>
            </div>
        </form>
    </div>

</body>
</html>
