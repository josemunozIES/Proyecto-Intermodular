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
    <div class = "main-body">
        <header class="header container">
        <a href="book_holiday.php" class="book">Book now</a>
        <nav class="nav-boxes">
        <img src="images/logo.png" alt="DAW Logo" class="logo">
        <a href="index.php" class="nav-box">Home</a>
<?php>
include("DB_Connection.php");

if (!isset($_GET['id'])) {
    echo "ID de usuario no proporcionado.";
    exit;
}

$id = intval($_GET['id']);

$query = "SELECT * FROM users WHERE id = $id";
$result = pg_query($conn, $query);

if (!$result || pg_num_rows($result) == 0) {
    echo "Usuario no encontrado.";
    exit;
}

$user = pg_fetch_assoc($result);
?>

<h2>Editar Usuario</h2>
<form action="update_user.php" method="post">
    <input type="hidden" name="id" value="<?= $user['id'] ?>">
    Nombre de usuario: <input type="text" name="username" value="<?= htmlspecialchars($user['username']) ?>"><br><br>
    <!-- Añade más campos si tu tabla `users` los tiene -->
    <input type="submit" value="Guardar cambios">
</form>
