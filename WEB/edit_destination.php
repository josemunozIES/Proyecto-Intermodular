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
require_once "DB_Connection.php";

if (!isset($_GET["id"])) {
    die("Destination ID is missing.");
}

$id = $_GET["id"];
$sql = "SELECT * FROM destinos WHERE id = $1";
$result = pg_query_params($conn, $sql, [$id]);

if (!$result || pg_num_rows($result) === 0) {
    die("Destination not found.");
}

$destino = pg_fetch_assoc($result);
?>
  <h1>Edit Destination</h1>

  <form method="post" action="update_destination.php" class="form-grid">
    <input type="hidden" name="id" value="<?= htmlspecialchars($destino['id']) ?>">

    <div class="form-group">
      <label for="ciudad">City:</label>
      <input type="text" id="ciudad" name="ciudad" value="<?= htmlspecialchars($destino['ciudad']) ?>" required>
    </div>

    <div class="form-group">
      <label for="pais">Country:</label>
      <input type="text" id="pais" name="pais" value="<?= htmlspecialchars($destino['pais']) ?>" required>
    </div>

    <div class="form-group">
      <label for="requiere_pasaporte">Requires Passport:</label>
      <select id="requiere_pasaporte" name="requiere_pasaporte" required>
        <option value="true" <?= $destino['requiere_pasaporte'] == 't' ? 'selected' : '' ?>>Yes</option>
        <option value="false" <?= $destino['requiere_pasaporte'] == 'f' ? 'selected' : '' ?>>No</option>
      </select>
    </div>

    <div class="form-group full-width">
      <input type="submit" class="book1" value="Update Destination">
    </div>
  </form>
</div>
</body>
</html>
