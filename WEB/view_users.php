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

$query = "SELECT * FROM usuarios";
$result = pg_query($conn, $query);

if (!$result) {
    echo "<p class='error'>Error al obtener los usuarios.</p>";
    exit;
}

echo "<h2 class='guides-title'>Usuarios registrados</h2>";
echo "<div class='guides-container'>";  // Flexbox estilo guía

while ($row = pg_fetch_assoc($result)) {
    echo "<div class='guide-card'>";
    echo "<h3>" . htmlspecialchars($row['nombre']) . "</h3>";

    // Mostrar el correo (ajusta el nombre del campo si es necesario)
    echo "<p>" . htmlspecialchars($row['email']) . "</p>";

    echo "<div style='margin-top: 15px; display: flex; gap: 10px; justify-content: center;'>";
    echo "<a href='edit_user.php?email=" . urlencode($row['email']) . "' class='sub-button'>Modificar</a>";
    echo "<a href='delete_user.php?id=" . $row['email'] . "' class='sub-button' onclick=\"return confirm('¿Estás seguro de que quieres eliminar este usuario?');\">Eliminar</a>";
    echo "</div>";
    echo "</div>";
}

echo "</div>";
?>
  </main>

  <footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
  </footer>
</div>
 
</body>
</html>
