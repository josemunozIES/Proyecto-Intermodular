<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="styles.css">

</head>          

<body>
   <header class="header container">
    <a href="book_holiday.php" class="book">Book now</a>
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
      <a href="view_users.php" class="nav-box">View Users</a>
      <a href="view_bookings.php" class="nav-box">My Bookings</a>
      <a href="guides.php" class="nav-box">Our Guides</a>
      <?php
        if (isset($_SESSION["user"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["user"]) . '!</p>';
        }
      ?>
    </nav>
  </header>
<main class="container">
    <div class="login-container">
      <h1 style="font-size: 38px;">Login</h1>

      <form method="post" action="login.php">
        <label>Username:</label>
        <input type="email" name="username" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <input class="book1" type="submit" value="Login">
      </form>

      <a href="register_user.php" class="register-link">Don’t have an account? Register</a>

      <?php
require_once "DB_Connection.php";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["username"]);
    $passwordInput = trim($_POST["password"]);

    $result = @pg_query_params($conn, "SELECT * FROM users WHERE email=$1", array($email));

    if ($row = pg_fetch_assoc($result)) {

        if ($passwordInput == $row['password']) { 
            $_SESSION["user"] = $row["nombre"]; 
            $_SESSION["email"] = $row["email"];
            $_SESSION["admin"] = $row["admin"];
            header("Location: index.php");
            exit();
        } else {
            $errorMsg = "Invalid password.";
        }
    } else {
        $errorMsg = "Invalid email or password.";
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
</body>
</html>
