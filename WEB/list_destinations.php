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
    echo "<p>" . htmlspecialchars($row['pais']) . "</p>";
    $requierePasaporte = $row['requiere_pasaporte'] === 't';
    echo "<p><strong>Passport required:</strong> " . ($requierePasaporte ? "Yes" : "No") . "</p>";


    $id_pais = (int) $row['id'];
    $ciudad = $row['ciudad'];

    $subquery = "
        SELECT nombre, apellido, especialidad 
        FROM guias 
        INNER JOIN destinos ON guias.id_pais = destinos.id 
        WHERE destinos.id = $1 AND destinos.ciudad = $2
    ";
    $subresult = pg_query_params($conn, $subquery, [$id_pais, $ciudad]);

    if ($subresult && pg_num_rows($subresult) > 0) {
        while ($guia = pg_fetch_assoc($subresult)) {
            echo "<p>" . htmlspecialchars($guia['nombre']) . " " . htmlspecialchars($guia['apellido']) . 
                 ", Speciality: " . htmlspecialchars($guia['especialidad']) . "</p>";
        }
    } else {
        echo "<p>No hay guías disponibles.</p>";
    }

    echo "<div style='margin-top: 15px; display: flex; gap: 10px; justify-content: center;'>";

    if (isset($_SESSION['admin']) && $_SESSION['admin']) {
        echo '<a href="edit_destination.php?id=' . $row['id'] . '" class="sub-button">Edit</a>';
        echo "<a href='delete_destination.php?id=" . urlencode($row['id']) . "' 
                 class='sub-button' 
                 onclick=\"return confirm('Are you sure you want to delete this destination?');\">
                 Delete
              </a>";

    }

    echo "</div>";
    echo "</div>";
}
?>

</div> 

<?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
  <div class="añadir-destino-box" style="margin: 50px auto; text-align: center;">
    <a href="new_destination.php" class="nav-boxs">Add destination</a>
</div>

<?php endif; ?>

  </main>

    <footer class="footer-container">
      <img src="images/logo.png" alt="DAW Logo" class="logo1">
      <p>Enjoy the touring</p>
      <img src="images/redes.png" alt="DAW Logo" class="redes">
    </footer>
  </div>
 
</body>
</html>
