<?php
session_start();
require_once "DB_Connection.php";

// Depuración opcional:
if (!isset($_SESSION['admin'])) {
    echo "<p class='error'>No eres administrador.</p>";
    exit;
}

if (!isset($_POST['guide_id'], $_POST['destino_id'])) {
    echo "<p class='error'>Datos faltantes para asignar guía.</p>";
    exit;
}

$guide_id = intval($_POST['guide_id']);
$destino_id = intval($_POST['destino_id']);

$update = pg_query_params($conn, "UPDATE guias SET id_pais = $1 WHERE id = $2", [$destino_id, $guide_id]);

if ($update) {
    header("Location: destination_detail.php?id=$destino_id");
    exit;
} else {
    echo "<p class='error'>Error al asignar guía: " . pg_last_error($conn) . "</p>";
}
