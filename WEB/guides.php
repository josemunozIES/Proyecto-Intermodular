<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Our Guides</title>
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
          } else if (isset($_SESSION["email"])) {
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
      <h1 class="guides-title">Meet Our Guides</h1>
      <p class="intro">Our team of expert guides is ready to show you hidden gems and unforgettable experiences. Let’s meet them!</p>

      <?php
      include("DB_Connection.php");

      $query = "SELECT * FROM guias";
      $result = pg_query($conn, $query);

      if (!$result) {
          echo "<p class='error'>Error al obtener los guías.</p>";
          exit;
      }

      echo "<br><br><div class='guides-container'>";

      while ($row = pg_fetch_assoc($result)) {
          echo "<div class='guide-card'>";
          echo "<h3>" . htmlspecialchars($row['nombre']) . " " . htmlspecialchars($row['apellido']);
          if (!empty($row['apellido2'])) {
              echo " " . htmlspecialchars($row['apellido2']);
          }
          echo "</h3>";
          echo "<p><strong>Specialty:</strong> " . htmlspecialchars($row['especialidad']) . "</p>";

          if (isset($_SESSION['admin']) && $_SESSION['admin']) {
              echo "<div style='margin-top: 10px; display: flex; justify-content: center; gap: 10px;'>";
              echo "<a class='sub-button' href='edit_guide.php?id=" . $row['id'] . "'>Edit</a>";
              echo "<a class='sub-button' href='delete_guide.php?id=" . $row['id'] . "' onclick=\"return confirm('Are you sure you want to delete this guide?');\">Delete</a>";
              echo "</div>";
          }

          echo "</div>";
      }

      echo "</div>";
      ?>

      <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
      <div class="añadir-destino-box" style="margin: 50px auto; text-align: center;">
        <a href="new_guide.php" class="sub-button">Add guides</a>
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