<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <header class="header container">
    <a href="bookings/create.php" class="book">Book now</a>
    <nav class="nav-boxes">
      <img src="images/logo.png" alt="DAW Logo" class="logo">
      <a href="index.php" class="nav-box">Home</a>
      <a href="login.php" class="nav-box">Login</a>
      <a href="register_user.php" class="nav-box">Register</a>
      <a href="view_users.php" class="nav-box">View Users</a>
    </nav>
  </header>

  <main class="container">
    <div class="login-container">
      <h1 style="font-size: 38px;">My Holidays</h1>

      <?php
      require_once "DB_Connection.php";

      $email = $_SESSION["email"] ?? null;
      if (!$email) {
          echo "<p class='error'>You must be logged in to see your bookings.</p>";
          exit();
      }

      $result = pg_query_params($conn, "SELECT passport FROM users WHERE email=$1", [$email]);
      $user = pg_fetch_assoc($result);
      $passport = $user['passport'] ?? null;

      if (!$passport) {
          echo "<p class='error'>You have no passport on file, so no bookings available.</p>";
          exit();
      }

      $query = "
        SELECT destination_ciudad, booking_begin, booking_end
        FROM bookings
        WHERE user_passport = $1
      ";
      $bookings = pg_query_params($conn, $query, [$passport]);

      if (pg_num_rows($bookings) > 0) {
          while ($row = pg_fetch_assoc($bookings)) {
              echo "<div style='border: 1px solid #ccc; padding: 10px; border-radius: 10px; margin: 10px 0;'>";
              echo "<strong>Destination:</strong> " . htmlspecialchars($row['destination_ciudad']) . "<br>";
              echo "<strong>Booking Start:</strong> " . htmlspecialchars($row['booking_begin']) . "<br>";
              echo "<strong>Booking End:</strong> " . htmlspecialchars($row['booking_end']) . "<br>";
              echo "</div>";
          }
      } else {
          echo "<p>No bookings found.</p>";
      }
      ?>
    </div>
  </main>

  <footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
  </footer>
</body>
</html>
