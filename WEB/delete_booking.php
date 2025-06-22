<?php
session_start();
require_once "DB_Connection.php";

$booking_id = $_POST["booking_id"] ?? null;

if ($booking_id) {
    $query = "DELETE FROM bookings WHERE id = $1";
    $result = pg_query_params($conn, $query, [$booking_id]);

    if ($result) {
        echo "<p class='success'>Booking deleted successfully!</p>";
    } else {
        echo "<p class='error'>Error deleting booking: " . pg_last_error($conn) . "</p>";
    }

    header("refresh:2;url=view_bookings.php");
    exit();
} else {
        header("refresh:3;url=view_bookings.php");

    echo "<p class='error'>Invalid request.</p>";
}
?>
