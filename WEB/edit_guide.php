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
<?php
require_once "DB_Connection.php";

if (!isset($_GET["id"])) {
    die("Guide ID is missing.");
}

$id = $_GET["id"];
$sql = "SELECT * FROM guias WHERE id = $1";
$result = pg_query_params($conn, $sql, [$id]);

if (!$result || pg_num_rows($result) === 0) {
    die("Guide not found.");
}

$guide = pg_fetch_assoc($result);
?>
  <h1>Edit Guide</h1>
<form method="post" action="update_guide.php" class="form-grid">
  <input type="hidden" name="id" value="<?= htmlspecialchars($guide['id']) ?>">

  <div class="form-group">
    <label for="nombre">Name:</label>
    <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($guide['nombre']) ?>" required>
  </div>

  <div class="form-group">
    <label for="apellido">Surname:</label>
    <input type="text" id="apellido" name="apellido" value="<?= htmlspecialchars($guide['apellido']) ?>" required>
  </div>

  <div class="form-group">
    <label for="apellido2">Surname2:</label>
    <input type="text" id="apellido2" name="apellido2" value="<?= htmlspecialchars($guide['apellido2']) ?>">
  </div>

  <div class="form-group">
    <label for="especialidad">Speciality:</label>
    <select id="especialidad" name="especialidad" required>
      <?php
        $options = ["Architecture", "Geography", "Food", "History"];
        foreach ($options as $option) {
          $selected = ($guide["especialidad"] === $option) ? "selected" : "";
          echo "<option value=\"$option\" $selected>$option</option>";
        }
      ?>
    </select>
  </div>

  <div class="form-group full-width">
    <input class="book1" type="submit" value="Update Guide">
  </div>
</form>

</div>
</body>
</html>
