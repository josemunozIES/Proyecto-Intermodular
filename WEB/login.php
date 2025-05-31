<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Login</title>
<link rel="stylesheet" href="styles.css">

</head>
<body>
    <header class="header container">
    <a href="bookings/create.php" class="book">Book now</a>
        <nav class="nav-boxes">
          <img src="images/logo.png" alt="DAW Logo" class="logo">
          <a href="index.php" class="nav-box">Home</a>
          <a href="#" class="nav-box">About Us</a>
          <a href="#" class="nav-box">Destinations</a>
          <a href="#" class="nav-box">Tours</a>
          <a href="#" class="nav-box">Blog</a>
          <a href="login.php" class="nav-box">Log in / Register</a>
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
  session_start();

  require_once "DB_Connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST["email"]);
    $passwordInput = trim($_POST["password"]);

    $result = pg_query_params($conn, "SELECT * FROM users WHERE email=$1", array($email));

    if ($row = pg_fetch_assoc($result)) {
        $_SESSION["user"] = $row["name"];
        echo "<p class='success'> Login successful. Welcome, " . htmlspecialchars($row["name"]) . "!</p>";
    } else {
        echo "<p class='error'> Invalid email or password.</p>";
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
