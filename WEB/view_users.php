<?php
session_start();
if (!isset($_SESSION["user"]) || $_SESSION["admin"] !== "yes") {
    header("Location: index.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Users</title>
<link rel="stylesheet" href="styles.css">

</head>
<body>
  <header class="header container">
    <a href="bookings/create.php" class="book">Book now</a>
    <nav class="nav-boxes">
      <img src="images/logo.png" alt="DAW Logo" class="logo">
      <a href="index.php" class="nav-box">Home</a>
          <?php
              if (isset($_SESSION["user"])) {
                  echo '<a href="logout.php" class="nav-box">Logout</a>';
              } else {
                  echo '<a href="login.php" class="nav-box">Login</a>';
                  echo '<a href="register_user.php" class="nav-box">Register</a>';
              }
          ?>
    </nav>
  </header>

  <main class="container1">
    <h1 style="font-size: 40px;">All User Details</h1>

    <?php
    require_once "DB_Connection.php";

    $result = pg_query($conn, "SELECT * FROM users ORDER BY nombre");

    if (!$result) {
        echo "<p class='error'> Error fetching users: " . pg_last_error($conn) . "</p>";
    } else {
        if (pg_num_rows($result) > 0) {
            echo "<table>";
            echo 
            "<tr>
            <th>Email</th>
            <th>First Name</th>
            <th>First Surname</th>
            <th>Second Surname</th>
            <th>Age</th>
            <th>Password</th
            ></tr>";

            while ($row = pg_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars($row["email"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["nombre"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["apellido"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["apellido2"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["edad"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["password"]) . "</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>No users found.</p>";
        }
    }
    ?>
  </main>

  <footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
  </footer>

</body>
</html>
