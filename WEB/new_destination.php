<?php 
    session_start(); 
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
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
<<<<<<< HEAD
              echo '<a href="list_destinations.php" class="nav-box">Our destinations</a>';
=======
              echo '<a href="list_destinations.php" class="nav-box">Edit destinations</a>';
>>>>>>> origin/develop
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
    <div class="login-container">
      <h1 style="font-size: 38px;">Register</h1>

      <form method="post" action="new_destination.php">

        <label>City:</label>
        <input type="text" name="ciudad" required>

        <label>Country:</label>
        <input type="text" name="pais" required>

        <label>
            <p style = "text-align: center">Requires Passport<p>
            <input type="checkbox" name="requiere_pasaporte" value="true">
            
        </label>

        <input class="book1" type="submit" value="Register">
      </form>

      <?php
      require_once "DB_Connection.php";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $ciudad = trim($_POST["ciudad"]);
          $pais = trim($_POST["pais"]);
          $requiere_pasaporte = isset($_POST["requiere_pasaporte"]) ? "true" : "false";
          $result = @pg_query_params($conn,
              "INSERT INTO destinos (ciudad, pais, requiere_pasaporte)
               VALUES ($1, $2, $3)",
              array($ciudad, $pais, $requiere_pasaporte));

            if ($result !== false) {
            $row = pg_fetch_assoc($result);
            if ($row !== false) {
                echo "<p class='success'>Destination created successfully! City: " . htmlspecialchars($row['ciudad']) . " Country: " . htmlspecialchars($row['pais']) . "</p>";
            } else {
                echo "<p class='success'>Destination created successfully!</p>";
            }
            header("refresh:3;url=index.php");
            } else {
                echo "<p class='error'>Error inserting destination.</p>";
            }
      }
      ?>
    </div>
  </main>

  <footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
  </footer>
  </div>
</body>
</html>