<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>View Users</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="main-body">
<header class="header container">
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
        if (isset($_SESSION["nombre"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["nombre"]) . '!</p>';
        }
      ?>
      </nav>
    </header>

<?php
include("DB_Connection.php");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
<<<<<<< HEAD
    $email_original = $_POST['email_original'];
    $apellido = $_POST['apellido'];
    $nombre = $_POST['nombre'];
    $apellido2 = empty($_POST['apellido2']) ? null : $_POST['apellido2'];
    $edad = trim($_POST['edad']);
    $edad = $edad === '' ? null : (int)$edad;

    $num_pasaporte = empty($_POST['numero_pasaporte']) ? null : $_POST['numero_pasaporte'];
    $pais_expedicion = empty($_POST['pais_expedicion']) ? null : $_POST['pais_expedicion'];

    $nueva_contraseña = $_POST['password_original'];
    if (!empty($_POST['password'])) {
        $nueva_contraseña = $_POST['password'];
    }

    // ACTUALIZAR USUARIO
    $query_usuario = "UPDATE usuarios 
                      SET nombre = $1, apellido = $2, apellido2 = $3, edad = $4, password = $5 
                      WHERE email = $6";

    $params_usuario = [$nombre, $apellido, $apellido2, $edad, $nueva_contraseña, $email_original];
    $result_usuario = pg_query_params($conn, $query_usuario, $params_usuario);

    // PASAPORTE
    if ($num_pasaporte && $pais_expedicion) {
        $check_passport = "SELECT 1 FROM pasaporte WHERE numero_pasaporte = $1";
        $check_result = pg_query_params($conn, $check_passport, [$num_pasaporte]);

        if (pg_num_rows($check_result) > 0) {
            $query_pasaporte = "UPDATE pasaporte SET \"pais_expedición\" = $1 WHERE numero_pasaporte = $2";
            $result_pasaporte = pg_query_params($conn, $query_pasaporte, [$pais_expedicion, $num_pasaporte]);
        } else {
            $query_pasaporte = "INSERT INTO pasaporte (numero_pasaporte, \"pais_expedición\") VALUES ($1, $2)";
            $result_pasaporte = pg_query_params($conn, $query_pasaporte, [$num_pasaporte, $pais_expedicion]);
        }

        // RELACIÓN usuario - pasaporte
        $check_passport = "SELECT 1 FROM pertenece_pasaporte WHERE email_usuario = $1";
        $check_result = pg_query_params($conn, $check_passport, [$email_original]);

        if (pg_num_rows($check_result) > 0) {
            $query_UsPas = "UPDATE pertenece_pasaporte SET numero_pasaporte = $1 WHERE email_usuario = $2";
            $result_UsPas = pg_query_params($conn, $query_UsPas, [$num_pasaporte, $email_original]);
        } else {
            $query_UsPas = "INSERT INTO pertenece_pasaporte (numero_pasaporte, email_usuario) VALUES ($1, $2)";
            $result_UsPas = pg_query_params($conn, $query_UsPas, [$num_pasaporte, $email_original]);
        }
    }

    // MENSAJES
    if (isset($result_UsPas) && !$result_UsPas) {
        echo "<p class='error'>Error al actualizar relación con pasaporte: " . pg_last_error($conn) . "</p>";
    }
    if (isset($result_pasaporte) && !$result_pasaporte) {
        echo "<p class='error'>Error al actualizar pasaporte: " . pg_last_error($conn) . "</p>";
    }
    if ($result_usuario) {
        echo "<p class='success'>Usuario actualizado correctamente.</p><br><br><br>";
        echo "<a href='view_users.php' class='button-return'>Volver</a>";
    } else {
        echo "<p class='error'>Error al actualizar el usuario: " . pg_last_error($conn) . "</p>";
=======
    $email_original = pg_escape_string($conn, $_POST['email_original']);
    $email_nuevo = pg_escape_string($conn, $_POST['email']);
    $nombre = pg_escape_string($conn, $_POST['nombre']);
    $apellido = pg_escape_string($conn, $_POST['apellido']);
    $apellido2 = pg_escape_string($conn, $_POST['apellido2']);
    $edad = isset($_POST['edad']) ? (int)$_POST['edad'] : null;

    $posted_password = trim($_POST['password']); // trim for easier check

    if (empty($posted_password)) {
        $query_old_password = "SELECT password FROM usuarios WHERE email = '$email_original'";
        $result_old_password = pg_query($conn, $query_old_password);
        $row_old_password = pg_fetch_assoc($result_old_password);
        $password = $row_old_password['password'];
    } else {
        $password = pg_escape_string($conn, $posted_password);
    }

    $numero_pasaporte = pg_escape_string($conn, $_POST['numero_pasaporte']);
    $pais_expedicion = pg_escape_string($conn, $_POST['pais_expedicion']);


    $query_user = "UPDATE usuarios SET 
                    email = '$email_nuevo', 
                    nombre = '$nombre', 
                    apellido = '$apellido', 
                    apellido2 = '$apellido2', 
                    edad = " . ($edad ?: "NULL") . ", 
                    password = '$password' 
                    WHERE email = '$email_original'";
    $result_user = pg_query($conn, $query_user);

    $query_passport = "UPDATE pasaporte SET 
                        pais_expedición = '$pais_expedicion' 
                        WHERE numero_pasaporte = '$numero_pasaporte'";
    $result_passport = pg_query($conn, $query_passport);

    $success = $result_user && $result_passport;

    if ($success) {
        echo "<p class='success'>Usuario y pasaporte actualizados correctamente.</p>";
        echo "<a href='view_users.php' class='sub-button'>Volver</a>";
    } else {
        echo "<p class='error'>Error al actualizar los datos.</p>";
>>>>>>> origin/develop
    }
}

$redirect = $success ? "view_users.php" : "view_users.php";
?>
<meta http-equiv="refresh" content="4;url=<?= $redirect ?>">
<p style="text-align: center; margin-top: 10px;">
  You will be redirected shortly...<br>
  <a href="<?= $redirect ?>" class="sub-button">Click here if not redirected</a>
</p>
</div>
</body>
</html>
