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
    <a href="book_holiday.php" class="book">Book now</a>
    <nav class="nav-boxes">
      <img src="images/logo.png" alt="DAW Logo" class="logo">
      <a href="index.php" class="nav-box">Home</a>
      <?php
          if (isset($_SESSION["user"])) {
              echo '<a href="logout.php" class="nav-box">Logout</a>';
          } else {
              echo '<a href="login.php" class="nav-box">Login</a>';
              echo '<a href="register_user.php" class="nav-box">Register</a>';
          }
      ?>
      <a href="view_users.php" class="nav-box">View Users</a>
      <a href="view_bookings.php" class="nav-box">My Bookings</a>
      <a href="guides.php" class="nav-box">See guides</a>
      <?php
        if (isset($_SESSION["user"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["user"]) . '!</p>';
        }
      ?>
    </nav>
  </header>

  <main class="container">
    <div class="login-container">
      <h1 style="font-size: 38px;">My Holidays</h1>

      <?php
      require_once "DB_Connection.php";

      $user_email = $_SESSION["email"] ?? null;

      if (!$user_email) {
          echo "<p class='error'>You must be logged in to view your bookings.</p>";
          echo "<p class='login-message'>Please <a href='login.php' class='nav-box'>log in</a> to continue.</p>";
      } else {
          $result = pg_query_params($conn, "SELECT passport FROM users WHERE email=$1", [$user_email]);
          $user = pg_fetch_assoc($result);
          $passport = $user['passport'] ?? null;

          if (!$passport) {
              echo "<p class='error'>No passport found in your profile. Please update it.</p>";
          } else {
              $query = "
                  SELECT destination_ciudad, booking_begin, booking_end, created_at
                  FROM bookings
                  WHERE user_passport = $1
                  ORDER BY booking_begin
              ";
              $result = pg_query_params($conn, $query, [$passport]);

              echo "<div class='bookings-container'>";

              if (pg_num_rows($result) > 0) {
                  while ($row = pg_fetch_assoc($result)) {
                      $destination = htmlspecialchars($row['destination_ciudad']);
                      $begin = htmlspecialchars($row['booking_begin']);
                      $end = htmlspecialchars($row['booking_end']);
                      $dateTime = new DateTime($row['created_at']);
                      $created_at = $dateTime->format('Y-m-d H:i');

                      echo "<div class='booking-row'>";
                      echo "<p><strong>Destination:</strong> $destination</p>";
                      echo "<p><strong>Booking Start:</strong> $begin</p>";
                      echo "<p><strong>Booking End:</strong> $end</p>";
                      echo "<p><strong>Booked on:</strong> $created_at</p>";
                      echo "</div>";
                  }
              } else {
                  echo "<p>No bookings found.</p>";
              }

              echo "</div>";
          }
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
