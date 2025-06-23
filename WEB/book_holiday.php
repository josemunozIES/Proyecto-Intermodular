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
  <div class="main-body">
<header class="header_container">
      <a href="book_holiday.php" class="book">Book now</a>
      <nav class="nav-boxes">
        <img src="images/logo.png" alt="DAW Logo" class="logo">
        <a href="index.php" class="nav-box">Home</a>
        <?php
          if (isset($_SESSION["email"])) {
              echo '<a href="logout.php" class="nav-box">Logout</a>';
          } else {
              echo '<a href="login.php" class="nav-box">Login</a>';
              echo '<a href="register_user.php" class="nav-box">Register</a>';
          }

          if (isset($_SESSION['admin']) && $_SESSION['admin']) {
              echo '<a href="view_users.php" class="nav-box">View users</a>';
          } else if (isset($_SESSION["email"])){
            echo '<a href="view_users.php" class="nav-box">My Profile</a>';
          }
        ?>
        <a href="view_bookings.php" class="nav-box">My Bookings</a>
        <a href="guides.php" class="nav-box">Our Guides</a>
        <?php
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
              echo '<a href="list_destinations.php" class="nav-box">Our destinations</a>';
          } else {
            echo '<a href="list_destinations.php" class="nav-box">Destinations</a>';
          }
        if (isset($_SESSION["nombre"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["nombre"]) . '!</p>';
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

      // Obtener lista de destinos con guía
      $query = "
          SELECT d.id, d.ciudad, d.pais, g.nombre AS guide_name, g.apellido AS guide_surname
          FROM destinos d
          LEFT JOIN guias g ON d.id = g.id_pais
          ORDER BY d.ciudad
      ";
      $result = pg_query($conn, $query);
      ?>

      <form method="post" action="process_booking.php" id="bookingForm">
        <label for="destination">Select Destination:</label><br>
        <select name="destination" required>
          <option value="" disabled selected>Choose a destination</option>
          <?php
          $query_with_passport = "
              SELECT d.id, d.ciudad, d.pais, d.requiere_pasaporte, g.nombre AS guide_name, g.apellido AS guide_surname
              FROM destinos d
              LEFT JOIN guias g ON d.id = g.id_pais
              ORDER BY d.ciudad
          ";
          $result = pg_query($conn, $query_with_passport);

          while ($row = pg_fetch_assoc($result)) {
              $id = $row['id'];
              $city = htmlspecialchars($row['ciudad']);
              $country = htmlspecialchars($row['pais']);
              $passportRequired = $row['requiere_pasaporte'] === 't' ? ' (Passport required)' : ' (No passport required)';
              
              echo "<option value='$id'>$city, $country$passportRequired</option>";
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
          // Comprobar si tiene pasaporte
          $query = "
              SELECT p.numero_pasaporte AS passport
              FROM pertenece_pasaporte pp
              JOIN pasaporte p ON pp.numero_pasaporte = p.numero_pasaporte
              WHERE pp.email_usuario = $1
          ";
          $result = pg_query_params($conn, $query, [$email]);
          $user = pg_fetch_assoc($result);
          $passport = $user['passport'] ?? null;

          if (empty($passport)) {
          echo "
          <script>
            const destinationPassportMap = {};
          ";

          $query = "SELECT id, requiere_pasaporte FROM destinos";
          $result = pg_query($conn, $query);
          while ($row = pg_fetch_assoc($result)) {
              $id = (int)$row["id"];
              $requires = $row["requiere_pasaporte"] === 't' ? 'true' : 'false';
              echo "destinationPassportMap[$id] = $requires;\n";
          }

          echo "
            document.getElementById('bookingForm').addEventListener('submit', function(e) {
              const selectedDestination = document.querySelector('select[name=\"destination\"]').value;
              const needsPassport = destinationPassportMap[selectedDestination];

              if (needsPassport === true || needsPassport === 'true') {
                e.preventDefault();
                alert('You must register your passport number before booking this destination.');
              }
            });
          </script>
          ";

         
          $userEmail = urlencode($_SESSION['email']);
          echo '<p class="update-passport-message"><a class="nav-boxs" href="edit_user.php?email=' . $userEmail . '">Click here to update your profile with your passport number</a>.</p>';
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
  </div>
</body>
</html>
