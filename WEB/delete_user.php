<?php
session_start();
require_once "DB_Connection.php";

$emailToDelete = isset($_GET["email"]) ? trim($_GET["email"]) : null;
$currentEmail = $_SESSION["email"] ?? null;
$isAdmin = isset($_SESSION["admin"]) && $_SESSION["admin"];
$isOwner = $currentEmail && $currentEmail === $emailToDelete;

if (!$emailToDelete) {
    echo "Invalid request.";
    exit;
}

if (!$isAdmin && !$isOwner) {
    echo "You are not allowed to delete this user.";
    exit;
}

// Paso 1: eliminar reservas
pg_query_params($conn, "DELETE FROM bookings WHERE email_usuario = $1", [$emailToDelete]);

// Paso 2: eliminar relación con pasaporte (si existe)
pg_query_params($conn, "DELETE FROM pertenece_pasaporte WHERE email_usuario = $1", [$emailToDelete]);

// Paso 3: eliminar usuario
$query = "DELETE FROM usuarios WHERE email = $1";
$result = pg_query_params($conn, $query, [$emailToDelete]);

if ($result) {
    if ($isOwner) {
        session_unset();
        session_destroy();
        header("Location: logout.php");
        exit;
    } else {
        header("Location: view_users.php");
        exit;
    }
} else {
    echo "Error deleting user: " . pg_last_error($conn);
}
?>
