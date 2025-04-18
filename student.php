<?php
session_start();


if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    // Eğer kullanıcı giriş yapmamışsa login sayfasına yönlendir
    header("Location: login.php");
    exit();
}

require 'connect.php';
$user = $_SESSION;

$student_number = $_SESSION['student_number'];

$reservationsQuery = "SELECT r.room_id, r.type, r.size, r.features, r.price, b.booking_id, b.status, b.booking_date 
                    FROM bookings b
                    JOIN rooms r ON b.room_id = r.room_id
                    WHERE b.student_number = $student_number";
$reservationsResult = mysqli_query($conn, $reservationsQuery);

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
  $booking_id = intval($_POST['booking_id']);
  
  $cancelQuery = "UPDATE bookings SET status = 'Canceled' WHERE booking_id = $booking_id";
  $updateRoomQuery = "UPDATE rooms SET availability = 'Available' 
                      WHERE room_id = (SELECT room_id FROM bookings WHERE booking_id = $booking_id)";
  
  if (mysqli_query($conn, $cancelQuery) && mysqli_query($conn, $updateRoomQuery)) {
      $successMessage = "Reservation canceled successfully.";
  } else {
      $errorMessage = "Error canceling reservation: " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Student</title>
    <style>

        #home ul {
            list-style: none;
            padding: 0;
        }

        #home li {
            background: #f8f9fa;
            border: 1px solid #ddd;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        #home {
            background: rgba(255, 255, 255, 0.9); 
            padding: 20px;
            border-radius: 10px;
            margin: 20px auto;
            width: 80%;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
        }
    </style>
</head>
<body id="background" style="background-image: url('img/backgroundStudent.jpeg');">
<header class="navigator">
    <ul>
      <li><img src="img/logo.jpeg" alt="Logo"></li>
      <li><a href="student.php">Home</a></li>
      <li><a href="booking.php">Bookings</a></li>
      <li><a href="account.php">Account</a></li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
</header>
<div id="studentDiv">
    <h1>Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h1>
</div>
<div id="home">
    <h2>Available Dorms</h2>
    <?php
    // Veritabanından yurtları çek
    $query = "SELECT dorm_id, dormName, dormLocation, dormFacilities, dormCapacity FROM dorms";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {
        echo "<ul>";
        while ($dorm = mysqli_fetch_assoc($result)) {
            echo "<li>";
            echo "<strong>Dorm Name:</strong> " . htmlspecialchars($dorm['dormName']) . "<br>";
            echo "<strong>Location:</strong> " . htmlspecialchars($dorm['dormLocation']) . "<br>";
            echo "<strong>Facilities:</strong> " . htmlspecialchars($dorm['dormFacilities']) . "<br>";
            echo "<strong>Dorm Capacity:</strong> " . htmlspecialchars($dorm['dormCapacity']) . "<br>";
            echo "<a href='dormsRooms.php?dorm_id=" . $dorm['dorm_id'] . "'>View Rooms</a>";
            echo "</li><br>";
        }
        echo "</ul>";
    } else {
        echo "<p>No dorms available at the moment.</p>";
    }
    ?>
</div>


<footer class="customFooter">
  <div class="footerContainer">
    <div class="socialIcons">
      <a href="https://www.facebook.com"><i class="fa-brands fa-facebook"></i></a>
      <a href="https://www.instagram.com"><i class="fa-brands fa-instagram"></i></a>
      <a href="https://x.com"><i class="fa-brands fa-twitter"></i></a>
      <a href="https://www.youtube.com"><i class="fa-brands fa-youtube"></i></a>
    </div>
  </div>
  <div class="footerLogo">
    <img src="img/logo.jpeg" alt="Logo"/>
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