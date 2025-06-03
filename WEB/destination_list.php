<?php
session_start();
require_once "DB_Connection.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>All Destinations</title>
  <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="main-body">
  <header class="header container">
    <a href="book_holiday.php" class="book">Book now</a>
    <nav class="nav-boxes">
      <img src="images/logo.png" alt="DAW Logo" class="logo">
      <a href="index.php" class="nav-box">Home</a>
      <a href="view_bookings.php" class="nav-box">My Bookings</a>
      <a href="guides.php" class="nav-box">Our Guides</a>
    </nav>
  </header>

  <main class="container">
    <div class="login-container">
      <h1>Available Destinations</h1>

      <form method="get" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="Search city..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <input type="submit" value="Search" class="book1">
      </form>

      <ul>
        <?php
        $search = $_GET['search'] ?? '';
        if ($search) {
            $query = "SELECT id, ciudad, pais FROM destinos WHERE ciudad ILIKE $1 ORDER BY ciudad";
            $result = pg_query_params($conn, $query, ["%$search%"]);
        } else {
            $query = "SELECT id, ciudad, pais FROM destinos ORDER BY ciudad";
            $result = pg_query($conn, $query);
        }

        while ($row = pg_fetch_assoc($result)) {
            $id = $row["id"];
            $city = htmlspecialchars($row["ciudad"]);
            $country = htmlspecialchars($row["pais"]);
            echo "<li><a href='destination_detail.php?id=$id'>$city, $country</a></li>";
        }
        ?>
      </ul>
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
