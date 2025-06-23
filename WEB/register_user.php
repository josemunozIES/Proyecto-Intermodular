<?php
session_start();
require_once "DB_Connection.php";

$errors = [];
$submitted = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $submitted = true;

    $email = trim($_POST["email"]);
    $passwordInput = trim($_POST["password"]);
    $repeatPassword = trim($_POST["repeat_password"]);
    $nombre = trim($_POST["name"]);
    $apellido = trim($_POST["surname"]);
    $apellido2 = !empty($_POST["second_surname"]) ? trim($_POST["second_surname"]) : null;
    $edad = intval($_POST["age"]);
    $passport = !empty($_POST["passport"]) ? trim($_POST["passport"]) : null;
    $country = !empty($_POST["country"]) ? trim($_POST["country"]) : null;

    // ✅ Basic validations
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }

    if ($passwordInput !== $repeatPassword) {
        $errors['password'] = "Passwords do not match.";
    }

    if ($passport && empty($country)) {
        $errors['passport'] = "If passport is provided, country is required.";
    }

    // ✅ Passport ownership validation (if passport provided)
    if ($passport && empty($errors['passport'])) {
        $check_query = "SELECT email_usuario FROM pertenece_pasaporte WHERE numero_pasaporte = $1";
        $check_result = pg_query_params($conn, $check_query, array($passport));

        if ($check_result && pg_num_rows($check_result) > 0) {
            $row = pg_fetch_assoc($check_result);
            if ($row['email_usuario'] !== $email) {
                $errors['passport'] = "This passport number is already in use by another user.";
            }
        }
    }

    // ✅ Attempt user insert only if all validations are clean
    if (empty($errors)) {
        $result = @pg_query_params($conn,
            "INSERT INTO usuarios (email, nombre, apellido, apellido2, edad, password)
             VALUES ($1, $2, $3, $4, $5, $6) RETURNING email",
            array($email, $nombre, $apellido, $apellido2, $edad, $passwordInput));

        if ($result !== false) {
            // ✅ Passport insert (optional)
            if ($passport !== null) {
                $passport_query = "INSERT INTO pasaporte (numero_pasaporte, pais_expedición)
                                   VALUES ($1, $2)
                                   ON CONFLICT (numero_pasaporte) DO NOTHING";
                pg_query_params($conn, $passport_query, array($passport, $country));

                $relation_query = "INSERT INTO pertenece_pasaporte (email_usuario, numero_pasaporte)
                                   VALUES ($1, $2)
                                   ON CONFLICT (email_usuario) DO NOTHING";
                pg_query_params($conn, $relation_query, array($email, $passport));
            }

            // ✅ Show success and redirect
            $row = pg_fetch_assoc($result);
            echo "<p class='success'>User created successfully! Email: " . htmlspecialchars($row['email']) . "</p>";
            header("refresh:3;url=index.php");
            exit;
        } else {
            // ✅ Catch unique constraint (email in use)
            $error = pg_last_error($conn);
            if (strpos($error, "duplicate key value") !== false) {
                $errors['email'] = "Email already in use. Please try another.";
            } else {
                $errors['database'] = "Database error: $error";
            }
        }
    }
}
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

     <form method="post" action="register_user.php">
      <label>Email:</label>
      <input type="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
      <?php if (!empty($errors['email'])): ?>
        <span class="error"><?= $errors['email'] ?></span>
      <?php endif; ?>

      <label>Password:</label>
      <input type="password" name="password" required>
      <?php if (!empty($errors['password'])): ?>
        <span class="error"><?= $errors['password'] ?></span>
      <?php endif; ?>

      <label>Repeat Password:</label>
      <input type="password" name="repeat_password" required>

      <label>First Name:</label>
      <input type="text" name="name" required value="<?= isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '' ?>">

      <label>First Surname:</label>
      <input type="text" name="surname" required value="<?= isset($_POST['surname']) ? htmlspecialchars($_POST['surname']) : '' ?>">

      <label>Second Surname (optional):</label>
      <input type="text" name="second_surname" value="<?= isset($_POST['second_surname']) ? htmlspecialchars($_POST['second_surname']) : '' ?>">

      <label>Age:</label>
      <input type="number" name="age" min="18" required value="<?= isset($_POST['age']) ? htmlspecialchars($_POST['age']) : '' ?>">

      <label>Passport Number (optional):</label>
      <input type="text" name="passport" value="<?= isset($_POST['passport']) ? htmlspecialchars($_POST['passport']) : '' ?>">
      <?php if (!empty($errors['passport'])): ?>
        <span class="error"><?= $errors['passport'] ?></span>
      <?php endif; ?>

      <label>Country of Origin (only if you add the passport):</label>
      <input type="text" name="country" value="<?= isset($_POST['country']) ? htmlspecialchars($_POST['country']) : '' ?>">

      <input class="book1" type="submit" value="Register">
    </form>


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
