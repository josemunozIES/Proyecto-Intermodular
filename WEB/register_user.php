<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Register</title>
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
      <h1 style="font-size: 38px;">Register</h1>

      <form method="post" action="register_user.php">
        <label>Email:</label>
        <input type="email" name="email" required>

        <label>Password:</label>
        <input type="password" name="password" required>

        <label>First Name:</label>
        <input type="text" name="name" required>

        <label>First Surname:</label>
        <input type="text" name="surname" required>

        <label>Second Surname (optional):</label>
        <input type="text" name="second_surname">

        <label>Age:</label>
        <input type="number" name="age" min="18" required>

        <input class="book1" type="submit" value="Register">
      </form>

      <?php
      require_once "DB_Connection.php";

      if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $email = trim($_POST["email"]);
            $passwordInput = password_hash(trim($_POST["password"]), PASSWORD_DEFAULT);
            $nombre = trim($_POST["name"]);
            $apellido = trim($_POST["surname"]);
            $apellido2 = !empty($_POST["second_surname"]) ? trim($_POST["second_surname"]) : null;
            $edad = intval($_POST["age"]);

    $result = @pg_query_params($conn,
        "INSERT INTO users (email, nombre, apellido, apellido2, edad, password)
         VALUES ($1, $2, $3, $4, $5, $6) RETURNING email",
        array($email, $nombre, $apellido, $apellido2, $edad, $passwordInput));

    if ($result !== false) {
        $row = pg_fetch_assoc($result);
        header("refresh:2;url=register_user.php");
        echo "<p class='success'> User created successfully! Email: " . htmlspecialchars($row['email']) . "</p>";
    } else {
        $error = pg_last_error($conn);
        if (strpos($error, "duplicate key value violates unique constraint") !== false) {
            header("refresh:2;url=register_user.php");
            echo "<p class='error'> --------> ERROR! Email already in use. Please try another. <--------</p>";
        } else {
            header("refresh:2;url=register_user.php");
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
</body>
</html>
