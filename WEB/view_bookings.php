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
<<<<<<< HEAD
              echo '<a href="list_destinations.php" class="nav-box">Our destinations</a>';
=======
              echo '<a href="list_destinations.php" class="nav-box">Edit destinations</a>';
>>>>>>> origin/develop
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
        <h1 style="font-size: 38px;">My Holidays</h1>

<?php
include("DB_Connection.php");
$email = $_SESSION['email'] ?? null;

if (!$email) {
    echo "<p class='error'>You must be logged in to view your bookings.</p>";
    exit;
}

// Mostrar información de pasaporte
$query_passport = "
    SELECT p.numero_pasaporte, p.pais_expedición
    FROM pertenece_pasaporte pp
    JOIN pasaporte p ON pp.numero_pasaporte = p.numero_pasaporte
    WHERE pp.email_usuario = $1
";
$result_passport = pg_query_params($conn, $query_passport, [$email]);

echo "<div class='update-passport-message'>";
if ($result_passport && pg_num_rows($result_passport) > 0) {
    $passport = pg_fetch_assoc($result_passport);
    echo "<p><strong>Passport:</strong> " . htmlspecialchars($passport['numero_pasaporte']) . "</p>";
    echo "<p><strong>Issued in:</strong> " . htmlspecialchars($passport['pais_expedición']) . "</p>";
} else {
   $userEmail = urlencode($_SESSION['email']);
    echo '<p class="update-passport-message"><a class="nav-boxs" href="edit_user.php?email=' . $userEmail . '">Click here to update your profile with your passport number</a>.</p>';
}
echo "</div>";

// Mostrar reservas
$query_bookings = "
    SELECT 
        b.id AS booking_id,         -- aquí traemos el id del booking
        b.inicio_booking, 
        b.final_booking, 
        d.ciudad, 
        d.pais, 
        d.id AS id_destino          -- alias para id_destino
    FROM bookings b
    JOIN destinos d ON b.id_destino = d.id
    WHERE b.email_usuario = $1
    ORDER BY b.inicio_booking DESC
";
$result_bookings = pg_query_params($conn, $query_bookings, [$email]);

if ($result_bookings && pg_num_rows($result_bookings) > 0) {
    echo "<h2 style='margin-top: 30px;'>Your Bookings</h2>";
    echo "<div class='booking-list'>";
    
    while ($row = pg_fetch_assoc($result_bookings)) {
        echo "<div class='booking-card'>";
        
        $booking_id = (int)$row['booking_id'];         // ya existe ahora
        $id_destino = (int)$row['id_destino'];         // ya existe ahora
        $ciudad = htmlspecialchars($row['ciudad']);
        
        echo "<p><strong>Destination:</strong> <a href='destination_detail.php?id=$id_destino'>$ciudad</a></p>";
        echo "<p><strong>Start Date:</strong> " . htmlspecialchars($row['inicio_booking']) . "</p>";
        echo "<p><strong>End Date:</strong> " . htmlspecialchars($row['final_booking']) . "</p>";
        
        // Delete form
        echo "<form action='delete_booking.php' method='post' style='margin-top: 10px;'>
                <input type='hidden' name='booking_id' value='$booking_id'>
                <input type='submit' value='Delete' class='sub-button' onclick=\"return confirm('Are you sure you want to delete this booking?');\">
              </form>";
        
        echo "</div>";
    }
    
    echo "</div>";
} else {
    echo "<p class='error' style='margin-top: 20px;'>You don't have any bookings yet.</p>";
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
