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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $ciudad = trim($_POST["ciudad"]);
    $pais = trim($_POST["pais"]);
    $requiere_pasaporte = $_POST["requiere_pasaporte"] === "true" ? 'true' : 'false';

    $sql = "UPDATE destinos 
            SET ciudad = $1, pais = $2, requiere_pasaporte = $3 
            WHERE id = $4";

    $params = [$ciudad, $pais, $requiere_pasaporte, $id];
    $result = pg_query_params($conn, $sql, $params);

    if ($result) {
        header("Location: list_destinations.php");
        exit();
    } else {
        echo "Error updating destination.";
    }
}
?>
