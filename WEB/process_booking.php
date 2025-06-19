<?php
session_start();
require_once "DB_Connection.php";

$user_email = $_SESSION["email"] ?? null;
$id_destino = intval($_POST["destination"] ?? 0);
$booking_begin = $_POST["booking_begin"] ?? null;
$booking_end = $_POST["booking_end"] ?? null;

$message = "";
$success = false;

// Validaciones básicas
if (!$user_email || !$id_destino || !$booking_begin || !$booking_end) {
    $message = "Missing booking information. Please complete all fields.";
} elseif (strtotime($booking_begin) > strtotime($booking_end)) {
    $message = "Start date must be before or equal to end date.";
} else {
    // Verificar si el destino requiere pasaporte
    $query_requirement = "SELECT requiere_pasaporte FROM destinos WHERE id = $1";
    $result_requirement = pg_query_params($conn, $query_requirement, [$id_destino]);

    if (!$result_requirement || pg_num_rows($result_requirement) === 0) {
        $message = "Could not verify if the destination requires a passport.";
        $redirect = "book_holiday.php";
    } else {
        $requires_passport = pg_fetch_result($result_requirement, 0, "requiere_pasaporte") === 't';

        // Si requiere pasaporte, comprobar si el usuario tiene uno
        if ($requires_passport) {
            $query_passport = "SELECT numero_pasaporte FROM pertenece_pasaporte WHERE email_usuario = $1";
            $result = pg_query_params($conn, $query_passport, [$user_email]);
            $passport_data = pg_fetch_assoc($result);
            $passport = $passport_data['numero_pasaporte'] ?? null;

            if (empty($passport)) {
                $message = "This destination requires a passport. Please register one first.";
                $redirect = "register_passport.php";
            }
        }

        // Si no requiere pasaporte o el usuario lo tiene, continuar
        if (!isset($redirect)) {
            // Verificar solapamientos
            $query_overlap = "
                SELECT 1 FROM bookings
                WHERE email_usuario = $1
                  AND daterange(inicio_booking, final_booking, '[]') &&
                      daterange($2::date, $3::date, '[]')
            ";
            $check = pg_query_params($conn, $query_overlap, [$user_email, $booking_begin, $booking_end]);

            if (!$check) {
                $message = "Error checking booking overlap: " . pg_last_error($conn);
            } elseif (pg_num_rows($check) > 0) {
                $message = "You already have a booking that overlaps with these dates.";
                $redirect = "book_holiday.php";
            } else {
                // Insertar reserva
                $query_insert = "
                    INSERT INTO bookings (email_usuario, id_destino, inicio_booking, final_booking)
                    VALUES ($1, $2, $3, $4)
                ";
                $insert = pg_query_params($conn, $query_insert, [$user_email, $id_destino, $booking_begin, $booking_end]);

                if ($insert) {
                    $message = "Booking successful! Destination ID: $id_destino from $booking_begin to $booking_end.";
                    $success = true;
                    $redirect = "index.php";
                } else {
                    $message = "Error booking the destination: " . pg_last_error($conn);
                    $redirect = "book_holiday.php";
                }
            }
        }
    }
}

// Fallback redirect if none set
if (!isset($redirect)) {
    $redirect = $success ? "index.php" : "book_holiday.php";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Booking Status</title>
  <link rel="stylesheet" href="styles.css">
  <meta http-equiv="refresh" content="4;url=<?= $redirect ?>">
</head>
<body>
  <div class="main-body">
    <header class="header container">
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
        if (isset($_SESSION["nombre"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["nombre"]) . '!</p>';
        }
      ?>
      </nav>
    </header>


    <main class="container">
      <div class="login-container">
        <h1 style="font-size: 36px;">Booking Status</h1>
        <p class="<?= $success ? 'success' : 'error' ?>"><?= htmlspecialchars($message) ?></p>
        <p style="text-align: center; margin-top: 10px;">
          You will be redirected shortly...<br>
          <a href="<?= $redirect ?>" class="sub-button">Click here if not redirected</a>
        </p>
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
