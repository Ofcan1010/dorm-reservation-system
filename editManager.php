<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $manager_id = $_GET['id'];

    $query = "SELECT * FROM managers WHERE manager_id = '$manager_id'";
    $result = mysqli_query($conn, $query);

    if ($result) {
        $manager = mysqli_fetch_assoc($result);
        if (!$manager) {
            echo "Manager not found!";
            exit();
        }
    } else {
        echo "Unable to fetch manager details from the database.";
        exit();
    }
}

$successMessage = '';
$errorMessage = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $dorm_id = mysqli_real_escape_string($conn, $_POST['dorm_id']);

    $updateQuery = "UPDATE managers SET name = '$name', surname = '$surname', phone = '$phone', email = '$email', dorm_id = '$dorm_id' WHERE manager_id = '$manager_id'";

    if (mysqli_query($conn, $updateQuery)) {
        $successMessage = "Manager successfully updated.";
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
    <title>Edit Manager</title>
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

        .form-group input, .form-group textarea {
            width: 100%;
            padding: 10px;
            font-size: 16px;
            margin-top: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .form-group input:focus, .form-group textarea:focus {
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
        <h1>Edit Manager</h1>
        <?php if ($successMessage): ?>
            <div class="alert alert-success"><?php echo $successMessage; ?></div>
        <?php elseif ($errorMessage): ?>
            <div class="alert alert-danger"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <form method="POST" action="editManager.php?id=<?php echo $manager['manager_id']; ?>">
            <div class="form-group">
                <label for="name">First Name</label>
                <input type="text" id="name" name="name" value="<?php echo $manager['name']; ?>" required>
            </div>
            <div class="form-group">
                <label for="surname">Last Name</label>
                <input type="text" id="surname" name="surname" value="<?php echo $manager['surname']; ?>" required>
            </div>
            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" id="phone" name="phone" value="<?php echo $manager['phone']; ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo $manager['email']; ?>" required>
            </div>
            <div class="form-group">
                <label for="dorm_id">Assigned Dorm</label>
                <input type="number" id="dorm_id" name="dorm_id" value="<?php echo $manager['dorm_id']; ?>" required>
            </div>
            <div class="button-group">
                <button type="submit">Update Manager</button>
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
        <div class="footerLogo">
            <img src="img/logo.jpeg" alt="Footer Logo">
        </div>
        <div class="footerBottom">
            <div class="footerNav">
                <a href="#">About</a>
                <a href="#">Contact Us</a>
                <a href="#">Privacy Policy</a>
            </div>
            <p>Copyright &copy;2024</p>
        </div>
    </footer>
</body>
</html>
