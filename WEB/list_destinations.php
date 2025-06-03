<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>list_destinations</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="main-body">
        <Section>
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
        ?>
<?php
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    echo '<a href="view_users.php" class="nav-box">View users</a>';
}
?>
        <a href="view_bookings.php" class="nav-box">My Bookings</a>
        <a href="guides.php" class="nav-box">Our Guides</a>

        </nav>
    </header>

<?php
include("DB_Connection.php");

$query = "SELECT * FROM destinos";
$result = pg_query($conn, $query);

if (!$result) {
    echo "<p class='error'>Error al obtener los destinos.</p>";
    exit;
}

echo "<h2 class='guides-title'>Our travels</h2>";
echo "<div class='guides-container'>";  // Flexbox estilo guía

while ($row = pg_fetch_assoc($result)) {
    echo "<div class='guide-card'>";
    echo "<h3>" . htmlspecialchars($row['ciudad']) . "</h3>";

    // Mostrar el correo (ajusta el nombre del campo si es necesario)
    echo "<p>" . htmlspecialchars($row['pais']) . "</p>";
    $ciudad = pg_escape_literal($conn, $row['ciudad']); // escape seguro para SQL
    $id_pais = (int) $row['id']; // aseguramos que sea entero

    $subquery = "
        SELECT nombre, apellido, especialidad 
        FROM guias 
        INNER JOIN destinos ON guias.id_pais = destinos.id 
        WHERE destinos.id = $id_pais AND destinos.ciudad = $ciudad
    ";
    $subresult = pg_query($conn, $subquery);

    if ($subresult && pg_num_rows($subresult) > 0) {
        while ($guia = pg_fetch_assoc($subresult)) {
            echo "<p>" . htmlspecialchars($guia['nombre']) . " " . htmlspecialchars($guia['apellido']) . 
                 ", Speciality: " . htmlspecialchars($guia['especialidad']) . "</p>";
        }
    } else {
        echo "<p>No hay guías disponibles.</p>";
    }
    echo "<div style='margin-top: 15px; display: flex; gap: 10px; justify-content: center;'>";
   echo "</div>";
    echo "</div>";
}

echo "</div>";
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    echo "<div class='añadir-destino-box'>";
    echo '<a href="new_destination.php" >Add destination</a>';
    echo "</div>";
}
?>
  </main>

  <footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
  </footer>
        </Section>
    </div>
</body>
</html>