<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register Passport</title>
<link rel="stylesheet" href="styles.css">
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
        <h1 style="font-size: 38px;">Register Passport</h1>

        <form method="post" action="register_passport.php">
          <label for="numero_pasaporte">Passport Number:</label>
          <input type="text" name="numero_pasaporte" required>

          <label for="pais_expedicion">Country of Issue:</label>
          <input type="text" name="pais_expedicion" required>

          <input class="sub-button" type="submit" value="Update Passport">
        </form>

        <?php
        include("DB_Connection.php");

        if (!isset($_SESSION['email'])) {
            echo "<p class='error'>No has iniciado sesión.</p>";
            exit;
        }

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $email = $_SESSION['email'];
            $passport = pg_escape_string($conn, $_POST['numero_pasaporte'] ?? '');
            $pais_expedicion = pg_escape_string($conn, $_POST['pais_expedicion'] ?? '');

            if (empty($passport) || empty($pais_expedicion)) {
                echo "<p class='error'>Debes introducir número de pasaporte y país de expedición.</p>";
                exit;
            }

            // Paso 1: insertar/actualizar pasaporte
            $query1 = "
                INSERT INTO pasaporte (numero_pasaporte, pais_expedición)
                VALUES ($1, $2)
                ON CONFLICT (numero_pasaporte)
                DO UPDATE SET pais_expedición = EXCLUDED.pais_expedición
            ";
            $res1 = pg_query_params($conn, $query1, [$passport, $pais_expedicion]);

            if (!$res1) {
                echo "<p class='error'>Error al registrar el pasaporte: " . pg_last_error($conn) . "</p>";
                exit;
            }

            // Paso 2: vincular con el usuario
            $query2 = "
                INSERT INTO pertenece_pasaporte (email_usuario, numero_pasaporte)
                VALUES ($1, $2)
                ON CONFLICT (email_usuario)
                DO UPDATE SET numero_pasaporte = EXCLUDED.numero_pasaporte
            ";
            $res2 = pg_query_params($conn, $query2, [$email, $passport]);

            if ($res2) {
                echo "<p class='success'>Pasaporte registrado correctamente.</p>";
                echo "<a href='view_bookings.php' class='sub-button'>Volver</a>";
            } else {
                echo "<p class='error'>Error actualizando pasaporte: " . pg_last_error($conn) . "</p>";
            }
        }
        ?>
      </div>
    </main>

    <footer class="footer-container">
      <img src="images/logo.png" alt="DAW Logo" class="logo1">
      <p>Enjoy the touring</p>
      <img src="images/redes.png" alt="DAW redes" class="redes">
    </footer>
  </div>
</body>
</html>
