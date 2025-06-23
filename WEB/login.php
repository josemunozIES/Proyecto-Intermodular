<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="styles.css">

</head>          

<body>
  <div class ="main-body">
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
      <h1 style="font-size: 38px;">Login</h1>

<form method="POST" action="login.php">
  <label for="email">Correo electrónico:</label>
  <input type="email" name="email" required>

  <label for="password">Contraseña:</label>
  <input type="password" name="password" required>

        <input class="book1" type="submit" value="Login">
</form>

      <a href="register_user.php" class="register-link">Don’t have an account? Register</a>

<?php
include("DB_Connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_POST['email']) && isset($_POST['password'])) {
        $email = pg_escape_string($conn, $_POST['email']);
        $password = pg_escape_string($conn, $_POST['password']);

        $query = "SELECT * FROM usuarios WHERE email = '$email' AND password = '$password'";
        $result = pg_query($conn, $query);

        if ($result && pg_num_rows($result) == 1) {
            $user = pg_fetch_assoc($result);
            $_SESSION['email'] = $user['email'];
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['admin'] = $user['admin'] === 't'; 

            header("Location: index.php");
            exit();
        } else {
            echo "<p class='error'>Correo o contraseña incorrectos.</p>";
        }
    } else {
        echo "<p class='error'>Faltan datos del formulario.</p>";
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
  </div>
</body>
</html>
