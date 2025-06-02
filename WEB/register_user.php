<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
  <div class ="main-body">
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
<?php
if (isset($_SESSION['admin']) && $_SESSION['admin']) {
    echo '<a href="view_users.php" class="nav-box">Ver Usuarios</a>';
}
?>
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
      <h1 style="font-size: 38px;">Register</h1>

      <form method="post" action="register_user.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>Repeat Password:</label>
        <input type="password" name="repeat_password" required>

        <label>First Name:</label>
        <input type="text" name="name" required>

        <label>First Surname:</label>
        <input type="text" name="surname" required>

        <label>Second Surname (optional):</label>
        <input type="text" name="second_surname">

        <label>Age:</label>
        <input type="number" name="age" min="18" required>

        <label>Passport Number (optional):</label>
        <input type="text" name="passport">

        <input class="book1" type="submit" value="Register">
      </form>

      <?php
      require_once "DB_Connection.php";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
          $email = trim($_POST["email"]);
          $passwordInput = trim($_POST["password"]);
          $repeatPassword = trim($_POST["repeat_password"]);
          $nombre = trim($_POST["name"]);
          $apellido = trim($_POST["surname"]);
          $apellido2 = !empty($_POST["second_surname"]) ? trim($_POST["second_surname"]) : null;
          $edad = intval($_POST["age"]);
          $passport = !empty($_POST["passport"]) ? trim($_POST["passport"]) : null;

          if ($passwordInput !== $repeatPassword) {
              echo "<p class='error'>Passwords do not match. Please try again.</p>";
              exit();
          }

          $result = @pg_query_params($conn,
              "INSERT INTO users (email, nombre, apellido, apellido2, edad, password, passport)
               VALUES ($1, $2, $3, $4, $5, $6, $7) RETURNING email",
              array($email, $nombre, $apellido, $apellido2, $edad, $passwordInput, $passport));

          if ($result !== false) {
              $row = pg_fetch_assoc($result);
              header("refresh:3;url=index.php");
              echo "<p class='success'> User created successfully! Email: " . htmlspecialchars($row['email']) . "</p>";
          } else {
              $error = pg_last_error($conn);
              if (strpos($error, "duplicate key value violates unique constraint") !== false) {
                  header("refresh:3;url=register_user.php");
                  echo "<p class='error'> --------> ERROR! Email already in use. Please try another. <--------</p>";
              } else {
                  header("refresh:3;url=register_user.php");
                  echo "<p class='error'> ------- Error creating user: $error --------</p>";
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
  </div>
</body>
</html>
