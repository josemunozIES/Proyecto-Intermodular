<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Book Your Holiday</title>
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
      <h1 style="font-size: 38px;">Book Your Holiday</h1>

      <?php
      require_once "DB_Connection.php";

      $email = $_SESSION["email"] ?? null;

      $query = "
          SELECT d.ciudad, d.pais, g.nombre AS guide_name, g.apellido AS guide_surname
          FROM destinations d
          LEFT JOIN guides g ON d.ciudad = g.ciudad
          ORDER BY d.ciudad
      ";
      $result = pg_query($conn, $query);

      $destinations = [];
      while ($row = pg_fetch_assoc($result)) {
          $city = htmlspecialchars($row['ciudad']);
          $country = htmlspecialchars($row['pais']);
          $guide = htmlspecialchars($row['guide_name'] . ' ' . $row['guide_surname']);

          if (!isset($destinations[$city])) {
              $destinations[$city] = [
                  'country' => $country,
                  'guides' => []
              ];
          }
          $destinations[$city]['guides'][] = $guide;
      }
      ?>

      <form method="post" action="process_booking.php" id="bookingForm">
        <label for="destination">Select Destination:</label><br>
        <select name="destination" required>
          <option value="" disabled selected>Choose a destination</option>
          <?php
          foreach ($destinations as $city => $data) {
              $guides = implode(", ", $data['guides']);
              echo '<option value="' . $city . '">' . $city . ', ' . $data['country'] . ' </option>';
          }
          ?>
        </select>

        <label for="booking_begin">Booking Start Date:</label>
        <input type="date" name="booking_begin" required>

        <label for="booking_end">Booking End Date:</label>
        <input type="date" name="booking_end" required>

        <input class="book1" type="submit" value="Book Now">
      </form>

      <?php
      if ($email) {
          $result = @pg_query_params($conn, "SELECT passport FROM users WHERE email=$1", [$email]);
          $user = pg_fetch_assoc($result);
          $passport = $user['passport'] ?? null;

          if (empty($passport)) {
              echo "<p class='error' style='margin-top:20px;'>You need a passport to complete your booking. Please register it below.</p>";
              echo '<p class="update-passport-message"><a class="nav-boxs" href="register_passport.php">Click here to update your profile with your passport number</a>.</p>';
              echo "
              <script>
                document.getElementById('bookingForm').addEventListener('submit', function(e) {
                  e.preventDefault();
                  alert('You must register your passport number before booking!');
                });
              </script>
              ";
          }
      } else {
          echo "<p class='error' style='margin-top:20px;'>You must be logged in to make a booking.</p>";
          echo "<p class='login-message'>Please <a href='login.php' class='nav-box'>log in</a> to continue.</p>";
          echo "
          <script>
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
              e.preventDefault();
              alert('You must be logged in to make a booking.');
            });
          </script>
          ";
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
