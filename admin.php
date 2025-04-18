<?php
session_start();
require 'connect.php';

if (!isset($_SESSION['loggedin']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

$dormQuery = "SELECT * FROM dorms";
$dormResult = mysqli_query($conn, $dormQuery);

$managerQuery = "SELECT * FROM managers";
$managerResult = mysqli_query($conn, $managerQuery);

$adminQuery = "SELECT * FROM user WHERE role = 'admin'";
$adminResult = mysqli_query($conn, $adminQuery);

$studentQuery = "SELECT * FROM user WHERE role = 'student'";
$studentResult = mysqli_query($conn, $studentQuery);

$roomQuery = "SELECT r.*, d.dormName 
              FROM rooms r 
              JOIN dorms d ON r.dorm_id = d.dorm_id";
$roomResult = mysqli_query($conn, $roomQuery);

if (!$studentResult || !$roomResult) {
    echo "Error fetching data: " . mysqli_error($conn);
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css">
    <style>
        /* Admin paneli stil düzenlemeleri */
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
            margin: 20px;
            padding: 30px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .section-title {
            font-size: 24px;
            margin-bottom: 20px;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .table th, .table td {
            padding: 10px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #e0e0e0;
        }

        .action-btn {
            text-decoration: none;
            color: #007BFF;
            padding: 5px 10px;
            border: 1px solid #007BFF;
            border-radius: 5px;
        }

        .action-btn:hover {
            background-color: #007BFF;
            color: white;
        }

        .welcome-message {
            font-size: 18px;
            color: #555;
            margin-bottom: 20px;
        }

        .button-group a {
            text-decoration: none;
            color: white;
            background-color: #007BFF;
            padding: 10px 20px;
            border-radius: 5px;
            margin-right: 10px;
        }

        .button-group a:hover {
            background-color: #0056b3;
        }

        footer.customFooter {
            background-color: #333;
            color: white;
            padding: 10px;
            text-align: center;
        }

        footer.customFooter .socialIcons a {
            color: white;
            margin: 0 10px;
            text-decoration: none;
        }

        footer.customFooter .socialIcons a:hover {
            color: #007BFF;
        }
    </style>
</head>
<body id="background">
<header class="navigator">
    <ul>
        <li><img src="img/logo.jpeg" alt="Logo"></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
</header>

    <div id="adminDiv">
        <h1 class="text-center">Admin Dashboard</h1>

        <div class="button-group">
            <a href="dormAdd.php" class="btn">Add Dorm</a>
            <a href="managerForm.php" class="btn">Add Manager</a>
            <a href="addRooms.php" class="btn">Add Room</a>
        </div>

        <h2 class="section-title">Manage Dorms</h2>
        <table class="table">
            <tr>
                <th>Dorm Name</th>
                <th>Capacity</th>
                <th>Location</th>
                <th>Facilities</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($dormResult)) { ?>
                <tr>
                    <td><?php echo isset($row['dormName']) ? $row['dormName'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['dormCapacity']) ? $row['dormCapacity'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['dormLocation']) ? $row['dormLocation'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['dormFacilities']) ? $row['dormFacilities'] : 'N/A'; ?></td>
                    <td><a href="editDorm.php?id=<?php echo $row['dorm_id']; ?>" class="action-btn">Edit</a> | <a href="deleteDorm.php?id=<?php echo $row['dorm_id']; ?>" class="action-btn">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <h2 class="section-title">Manage Rooms</h2> <!-- Yeni Rooms bölümü -->
        <table class="table">
            <tr>
                <th>Room ID</th>
                <th>Type</th>
                <th>Size</th>
                <th>Features</th>
                <th>Front View</th>
                <th>Price</th>
                <th>Availability</th>
                <th>Dorm Name</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($roomResult)) { ?>
                <tr>
                    <td><?php echo $row['room_id']; ?></td>
                    <td><?php echo $row['type']; ?></td>
                    <td><?php echo $row['size']; ?></td>
                    <td><?php echo $row['features']; ?></td>
                    <td><?php echo $row['front_view']; ?></td>
                    <td><?php echo $row['price']; ?></td>
                    <td><?php echo $row['availability']; ?></td>
                    <td><?php echo $row['dormName']; ?></td>
                    <td>
                        <a href="editRoom.php?room_id=<?php echo $row['room_id']; ?>" class="action-btn">Edit</a> |
                        <a href="deleteRoom.php?room_id=<?php echo $row['room_id']; ?>" class="action-btn">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </table>

        <h2 class="section-title">Manage Managers</h2>
        <table class="table">
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Email</th>
                <th>Assigned Dorm</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($managerResult)) { ?>
                <tr>
                    <td><?php echo isset($row['name']) ? $row['name'] : 'N/A'; ?> <?php echo isset($row['surname']) ? $row['surname'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['phone']) ? $row['phone'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['dorm_id']) ? $row['dorm_id'] : 'N/A'; ?></td>
                    <td><a href="editManager.php?id=<?php echo $row['manager_id']; ?>" class="action-btn">Edit</a> | <a href="deleteManager.php?id=<?php echo $row['manager_id']; ?>" class="action-btn">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <h2 class="section-title">Manage Student Users</h2>
        <table class="table">
            <tr>
                <th>Full Name</th>
                <th>Student Number</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($studentResult)) { ?>
                <tr>
                    <td><?php echo isset($row['full_name']) ? $row['full_name'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['student_number']) ? $row['student_number'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?></td>
                    <td><a href="editUser.php?id=<?php echo $row['student_number']; ?>" class="action-btn">Edit</a> | <a href="deleteUser.php?id=<?php echo $row['student_number']; ?>" class="action-btn">Delete</a></td>
                </tr>
            <?php } ?>
        </table>

        <h2 class="section-title">Admin Users</h2>
        <table class="table">
            <tr>
                <th>Full Name</th>
                <th>Email</th>
            </tr>
            <?php while ($row = mysqli_fetch_assoc($adminResult)) { ?>
                <tr>
                    <td><?php echo isset($row['full_name']) ? $row['full_name'] : 'N/A'; ?></td>
                    <td><?php echo isset($row['email']) ? $row['email'] : 'N/A'; ?></td>
                </tr>
            <?php } ?>
        </table>
    </div>

</body>
</html>