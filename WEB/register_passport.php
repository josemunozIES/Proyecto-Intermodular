<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register Passport</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
 <header class="header container">
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
      <a href="guides.php" class="nav-box">See guides</a>
      <?php
        if (isset($_SESSION["user"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["user"]) . '!</p>';
        }
      ?>
    </nav>
  </header>
  <main class="container">
    <div class="login-container">
      <h1 style="font-size: 38px;">Register Passport</h1>

      <form method="post" action="register_passport.php">
        <label>Passport Number:</label>
        <input type="text" name="passport" required>
        <input class="book1" type="submit" value="Update Passport">
      </form>

      <?php
      require_once "DB_Connection.php";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $email = $_SESSION["email"] ?? null;
          $passport = trim($_POST["passport"]);

          if (!$email) {
              echo "<p class='error'>You must be logged in to update your passport.</p>";
              exit();
          }

          $result = @pg_query_params($conn,
              "UPDATE users SET passport=$1 WHERE email=$2 RETURNING passport",
              [$passport, $email]);

          if ($result && pg_affected_rows($result) > 0) {
             header("refresh:3;url=book_holiday.php");
              $row = pg_fetch_assoc($result);
              echo "<p class='success'>Passport updated successfully! Going back to bookings... </p>";
          } else {
              $error = pg_last_error($conn);
              if (strpos($error, "duplicate key value violates unique constraint") !== false) {
                  echo "<p class='error'>Error: That passport number is already in use. Please choose another.</p>";
              } else {
                  echo "<p class='error'>Error updating passport: $error</p>";
              }
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
