<?php
session_start();
require_once "DB_Connection.php";

$guideId = isset($_GET["id"]) ? (int) $_GET["id"] : null;

if (!isset($_SESSION["admin"]) || !$_SESSION["admin"]) {
    echo "You are not authorized to delete guides.";
    exit;
}

if (!$guideId) {
    echo "Invalid request. Guide ID is required.";
    exit;
}

$query = "DELETE FROM guias WHERE id = $1";
$result = pg_query_params($conn, $query, array($guideId));

if ($result) {
    header("Location: guides.php");
    exit;
} else {
    echo "Error deleting guide.";
}
?>
