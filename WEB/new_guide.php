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
              echo '<a href="list_destinations.php" class="nav-box">Edit destinations</a>';
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

      <form method="post" action="new_guide.php">

        <label>Name:</label>
        <input type="text" name="nombre" required>

        <label>Surname:</label>
        <input type="text" name="apellido" required>

        <label>Surname2:</label>
        <input type="text" name="apellido2">

        <label>Speciality:</label>
          <select name="especialidad" required>
            <option value="Architecture">Architecture</option>
            <option value="Geography">Geography</option>
            <option value="Food">Food</option>
            <option value="History">History</option>
          </select>

        <label>City:</label>
        <input type="text" name="ciudad" required>

        <label>Country:</label>
        <input type="text" name="pais" required>

        <input class="book1" type="submit" value="Register">
      </form>

      <?php
      require_once "DB_Connection.php";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $nombre = trim($_POST["nombre"]);
          $apellido = trim($_POST["apellido"]);
          $apellido2 = !empty(trim($_POST["apellido2"])) ? trim($_POST["apellido2"]) : null;
          $especialidad = trim($_POST["especialidad"]);
          $ciudad = trim($_POST["ciudad"]);
          $pais = trim($_POST["pais"]);

        // Buscar el destino en la tabla destinos
            $destino_result = @pg_query_params($conn,
            "SELECT id FROM destinos WHERE ciudad ILIKE $1 AND pais ILIKE $2",
            array($ciudad, $pais));

            if ($destino_result && pg_num_rows($destino_result) > 0) {
            $destino = pg_fetch_assoc($destino_result);
            $id_pais = $destino['id'];

            // Insertar guía con el id del destino
            $result = @pg_query_params($conn,
                "INSERT INTO guias (nombre, apellido, apellido2, especialidad, id_pais)
                VALUES ($1, $2, $3, $4, $5)",
                array($nombre, $apellido, $apellido2, $especialidad, $id_pais));

            if ($result !== false) {
                echo "<p class='success'>Guide added successfully!</p>";
                header("refresh:3;url=index.php");
            } else {
                echo "<p class='error'>Error inserting guide: " . pg_last_error($conn) . "</p>";
            }
            } else {
                echo "<p class='error'>No matching destination found. Please register the destination first.</p>";
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