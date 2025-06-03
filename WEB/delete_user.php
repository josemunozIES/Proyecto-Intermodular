<?php
include("DB_Connection.php");

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $query = "DELETE FROM users WHERE id = $id";
    $result = pg_query($conn, $query);

    if ($result) {
        echo "Usuario eliminado correctamente. <a href='view_users.php'>Volver</a>";
    } else {
        echo "Error al eliminar el usuario.";
    }
}
?>
