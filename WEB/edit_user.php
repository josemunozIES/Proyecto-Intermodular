<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>
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

<?php
include("DB_Connection.php");

if (!isset($_GET['email'])) {
    echo "<p class='error'>No email provided.</p>";
    exit;
}

$email = pg_escape_string($conn, $_GET['email']);
$query_user = "SELECT * FROM usuarios WHERE email = '$email'";
$result_user = pg_query($conn, $query_user);

if (!$result_user || pg_num_rows($result_user) === 0) {
    echo "<p class='error'>User not found.</p>";
    exit;
}

$user = pg_fetch_assoc($result_user);


$query_passport = "SELECT * FROM pasaporte WHERE numero_pasaporte = (SELECT numero_pasaporte FROM pertenece_pasaporte WHERE email_usuario = '$email')";
$result_passport = pg_query($conn, $query_passport);
$passport = pg_fetch_assoc($result_passport);
?>

<h2 class="guides-title">Edit User</h2>

<p><?= htmlspecialchars($user['email']) ?></p>
<br>
<form action="update_user.php" method="post" class="login-container">
    <input type="hidden" name="email_original" value="<?= htmlspecialchars($user['email']) ?>">
    <input type="hidden" name="password_original" value="<?= htmlspecialchars($user['password']) ?>">

    <label for="nombre">Name:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>

    <label for="apellido">Last Name:</label>
    <input type="text" name="apellido" value="<?= htmlspecialchars($user['apellido']) ?>">
<<<<<<< HEAD

    <label for="apellido2">Second Last Name:</label>
    <input type="text" name="apellido2" value="<?= htmlspecialchars($user['apellido2']) ?>">

    <label for="edad">Age:</label>
    <input type="number" name="edad" value="<?= htmlspecialchars($user['edad']) ?>">

=======

    <label for="apellido2">Second Last Name:</label>
    <input type="text" name="apellido2" value="<?= htmlspecialchars($user['apellido2']) ?>">

    <label for="edad">Age:</label>
    <input type="number" name="edad" value="<?= htmlspecialchars($user['edad']) ?>">

    <label for="email">Email:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

>>>>>>> origin/develop
    <label for="password">New Password (leave blank to keep current):</label>
    <input type="password" name="password">

    <label for="numero_pasaporte">Passport Number:</label>
    <input type="text" name="numero_pasaporte" value="<?= htmlspecialchars($passport['numero_pasaporte'] ?? '') ?>">

    <label for="pais_expedicion">Country of Issuance:</label>
    <input type="text" name="pais_expedicion" value="<?= htmlspecialchars($passport['pais_expedición'] ?? '') ?>">

    <input type="submit" value="Save Changes" class="sub-button">
</form>

<footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
</footer>
</div>
</body>
</html>
