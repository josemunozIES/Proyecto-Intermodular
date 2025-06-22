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

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST["id"];
    $nombre = trim($_POST["nombre"]);
    $apellido = trim($_POST["apellido"]);
    $apellido2 = trim($_POST["apellido2"]);
    $especialidad = trim($_POST["especialidad"]);

    $sql = "UPDATE guias 
        SET nombre = $1, apellido = $2, apellido2 = $3, especialidad = $4
        WHERE id = $5";

$params = [$nombre, $apellido, $apellido2, $especialidad, $id];

    $result = pg_query_params($conn, $sql, $params);

    if ($result) {
        header("Location: guides.php");
        exit();
    } else {
        echo "Error updating guide.";
    }
}
?>
