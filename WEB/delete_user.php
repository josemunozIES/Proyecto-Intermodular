<?php
include("DB_Connection.php");

 $email = $_GET["email"]

if ($email) {

    $query = "DELETE FROM usuarios WHERE email = $email";
    $result = pg_query($conn, $query);

    if ($result) {
        echo "Usuario eliminado correctamente. <a href='view_users.php'>Volver</a>";
    } else {
        echo "Error al eliminar el usuario.";
    }
}
?>
