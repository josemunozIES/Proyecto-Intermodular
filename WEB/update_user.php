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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email_original = pg_escape_string($conn, $_POST['email_original']);
    $email_nuevo = pg_escape_string($conn, $_POST['email']);
    $nombre = pg_escape_string($conn, $_POST['nombre']);

    $query = "UPDATE usuarios SET email = '$email_nuevo', nombre = '$nombre' WHERE email = '$email_original'";
    $result = pg_query($conn, $query);

    if ($result) {
        echo "<p class='success'>Usuario actualizado correctamente.</p>";
        echo "<a href='view_users.php' class='sub-button'>Volver</a>";
    } else {
        echo "<p class='error'>Error al actualizar el usuario.</p>";
    }
}
?>
