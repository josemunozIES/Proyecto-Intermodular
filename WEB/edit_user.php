<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Users</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class = "main-body">
        <header class="header container">
        <a href="book_holiday.php" class="book">Book now</a>
        <nav class="nav-boxes">
        <img src="images/logo.png" alt="DAW Logo" class="logo">
        <a href="index.php" class="nav-box">Home</a>
        <?php
            if (isset($_SESSION["user"]) && $_SESSION['user']) {
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

        </nav>
    </header>

<?php
include("DB_Connection.php");

if (!isset($_GET['email'])) {
    echo "<p class='error'>No se proporcionó el email del usuario.</p>";
    exit;
}

$email = pg_escape_string($conn, $_GET['email']);
$query = "SELECT * FROM usuarios WHERE email = '$email'";
$result = pg_query($conn, $query);

if (!$result || pg_num_rows($result) === 0) {
    echo "<p class='error'>Usuario no encontrado.</p>";
    exit;
}

$user = pg_fetch_assoc($result);
?>

<!-- Formulario -->
<h2 class="guides-title">Editar Usuario</h2>

<form action="update_user.php" method="post" class="login-container">
    <input type="hidden" name="email_original" value="<?= htmlspecialchars($user['email']) ?>">

    <label for="nombre">Nombre de usuario:</label>
    <input type="text" name="nombre" value="<?= htmlspecialchars($user['nombre']) ?>" required>

    <label for="email">Correo electrónico:</label>
    <input type="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>

    <input type="submit" value="Guardar cambios" class="sub-button">
</form>