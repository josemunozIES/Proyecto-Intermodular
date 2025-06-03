<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class ="main-body">
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
<?php
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    echo '<a href="view_users.php" class="nav-box">Ver Usuarios</a>';
}
?>
      <a href="view_bookings.php" class="nav-box">My Bookings</a>
      <a href="guides.php" class="nav-box">Our Guides</a>
      <?php
        if (isset($_SESSION["user"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["user"]) . '!</p>';
        }
      ?>
    </nav>
  </header>
  <main class="container">
    <div class="login-container">
      <h1 style="font-size: 38px;">Register</h1>

      <form method="post" action="register_user.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Repeat Password:</label>
        <input type="password" name="repeat_password" required>

        <label>First Name:</label>
        <input type="text" name="nombre" required>  <!-- Fixed field name -->

        <label>First Surname:</label>
        <input type="text" name="apellido" required>

        <label>Second Surname (optional):</label>
        <input type="text" name="apellido2">

         <label>Admin :</label>
        <input type="checkbox" name="admin">       

        <label>Passport Number (optional):</label>
        <input type="text" name="numero_pasaporte">  <!-- Fixed field name -->

        <label>Country of Issuance (optional):</label>  <!-- Added new field -->
        <input type="text" name="pais_expedicion">

        <input class="book1" type="submit" value="Register">
      </form>

      <?php
include("DB_Connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recoger datos del usuario
    $nombre = pg_escape_string($conn, $_POST['nombre']);
    $apellido = pg_escape_string($conn, $_POST['apellido']);
    $apellido2 = pg_escape_string($conn, $_POST['apellido2'] ?? '');
    $email = pg_escape_string($conn, $_POST['email']);
    $password = pg_escape_string($conn, $_POST['password']);
    $admin = pg_escape_string($conn, $_POST['admin']);
    

    // Insertar en tabla users (fixed table name)
    $query_usuario = "
        INSERT INTO usuarios (email, nombre, apellido, apellido2,password, admin)
        VALUES ('$email', '$nombre', '$apellido', '$apellido2', '$password', '$admin')
    ";
    $res_usuario = pg_query($conn, $query_usuario);

    if (!$res_usuario) {
        echo "<p class='error'>Error creando usuario: " . pg_last_error($conn) . "</p>";
        exit;
    }

    // Comprobar si se ingresó pasaporte
    if (!empty($numero_pasaporte)) {
        // Insertar en tabla pasaporte
        $query_pasaporte = "
            INSERT INTO pasaporte (numero_pasaporte, pais_expedición)
            VALUES ('$numero_pasaporte', '$pais_expedicion')
            ON CONFLICT (numero_pasaporte) DO NOTHING
        ";
        pg_query($conn, $query_pasaporte);

        // Insertar relación en pertenece_pasaporte
        $query_relacion = "
            INSERT INTO pertenece_pasaporte (email_usuario, numero_pasaporte)
            VALUES ('$email', '$numero_pasaporte')
        ";
        $res_relacion = pg_query($conn, $query_relacion);
        
        if (!$res_relacion) {
            echo "<p class='error'>Error registrando pasaporte: " . pg_last_error($conn) . "</p>";
        }
    }

    echo "<p class='success'>Usuario registrado correctamente.</p>";
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