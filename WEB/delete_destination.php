<?php
session_start();
require_once "DB_Connection.php";

if (!isset($_SESSION['admin']) || !$_SESSION['admin']) {
    echo "Access denied.";
    exit;
}

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = "DELETE FROM destinos WHERE id = $1";
    $result = pg_query_params($conn, $query, array($id));

    if ($result) {
        header("Location: list_destinations.php");
        exit;
    } else {
        echo "Error deleting destination: " . pg_last_error($conn);
    }
} else {
    echo "Invalid ID.";
}
?>
