<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>My Bookings</title>
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
          }
        ?>
        <a href="view_bookings.php" class="nav-box">My Bookings</a>
        <a href="guides.php" class="nav-box">Our Guides</a>
      </nav>
    </header>

    <main class="container">
      <div class="login-container">
        <h1 style="font-size: 38px;">My Holidays</h1>

<?php
require_once "DB_Connection.php";

$id_destino = $_GET["id"] ?? null;
if (!$id_destino) {
    echo "<p class='error'>No destination selected.</p>";
    exit;
}
?>

  <main class="container">
    <div class="login-container">
      <?php
      $query_dest = "SELECT ciudad, pais FROM destinos WHERE id = $1";
      $res_dest = pg_query_params($conn, $query_dest, [$id_destino]);
      $dest = pg_fetch_assoc($res_dest);
      echo "<h1 style='font-size: 32px;'>" . htmlspecialchars($dest["ciudad"]) . ", " . htmlspecialchars($dest["pais"]) . "</h1>";
      ?>

      <h2 style='margin-top: 30px;'>Registered Users</h2>
      <ul>
      <?php
      $query_users = "
          SELECT DISTINCT u.nombre, u.apellido 
          FROM bookings b
          JOIN pertenece_pasaporte pp ON b.email_usuario = pp.email_usuario
          JOIN usuarios u ON pp.email_usuario = u.email
          WHERE b.id_destino = $1
      ";
      $res_users = pg_query_params($conn, $query_users, [$id_destino]);
      if (pg_num_rows($res_users) === 0) {
          echo "<p>No users registered yet.</p>";
      } else {
          while ($user = pg_fetch_assoc($res_users)) {
              echo "<li>" . htmlspecialchars($user['nombre']) . " " . htmlspecialchars($user['apellido']) . "</li>";
          }
      }
      ?>
      </ul>

      <h2 style='margin-top: 30px;'>Guides</h2>
      <ul>
      <?php
      $query_guides = "SELECT nombre, apellido FROM guias WHERE id_pais = $1";
      $res_guides = pg_query_params($conn, $query_guides, [$id_destino]);
      if (pg_num_rows($res_guides) === 0) {
          echo "<p>No guides assigned.</p>";
      } else {
          while ($guide = pg_fetch_assoc($res_guides)) {
              echo "<li>" . htmlspecialchars($guide['nombre']) . " " . htmlspecialchars($guide['apellido']) . "</li>";
          }
      }
      ?>
      </ul>

      <?php if (isset($_SESSION['admin']) && $_SESSION['admin']): ?>
        <h3 style='margin-top: 30px;'>Assign Guide to This Destination</h3>
        <form method="post" action="assign_guide.php">
          <input type="hidden" name="destino_id" value="<?= htmlspecialchars($id_destino) ?>">
          <label>Select Guide:</label><br>
          <select name="guide_id" required>
            <option value="" disabled selected>Select one</option>
            <?php
            $all_guides = pg_query($conn, "SELECT id, nombre, apellido FROM guias ORDER BY nombre");
            while ($g = pg_fetch_assoc($all_guides)) {
              $id = htmlspecialchars($g['id']);
              $name = htmlspecialchars($g['nombre'] . ' ' . $g['apellido']);
              echo "<option value='$id'>$name</option>";
            }
            ?>
          </select>
          <br><br>
          <input type="submit" value="Assign" class="book1">
        </form>
      <?php endif; ?>
    </div>
  </main>

  <footer class="footer-container">
    <img src="images/logo.png" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" class="redes">
  </footer>
</div>
</body>
</html>
