<?php
include("DB_Connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = intval($_POST['id']);
    $username = pg_escape_string($conn, $_POST['username']);

    $query = "UPDATE users SET username = '$username' WHERE id = $id";
    $result = pg_query($conn, $query);

    if ($result) {
        echo "Usuario actualizado correctamente. <a href='view_users.php'>Volver</a>";
    } else {
        echo "Error al actualizar el usuario.";
    }
}
?>
