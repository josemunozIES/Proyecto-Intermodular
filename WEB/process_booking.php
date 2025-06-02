<?php
session_start();
require_once "DB_Connection.php";

$user_email = $_SESSION["email"] ?? null;
$destination = $_POST["destination"] ?? null;
$booking_begin = $_POST["booking_begin"] ?? null;
$booking_end = $_POST["booking_end"] ?? null;

if (!$user_email || !$destination || !$booking_begin || !$booking_end) {
    echo "<p class='error'>Missing booking information. Please complete all fields.</p>";
    exit();
}

$result = pg_query_params($conn, "SELECT passport FROM users WHERE email=$1", [$user_email]);
$user = pg_fetch_assoc($result);
$passport = $user['passport'] ?? null;

if (empty($passport)) {
    header("refresh:3;url=register_passport.php");
    echo "<p class='error'>You must have a passport number to book a holiday.</p>";
    exit();
}

$overlap_query = "
    SELECT 1 FROM bookings
    WHERE user_passport = $1
      AND daterange(booking_begin, booking_end, '[]') &&
          daterange($2::date, $3::date, '[]')
";
$overlap_check = pg_query_params($conn, $overlap_query, [$passport, $booking_begin, $booking_end]);

if (pg_num_rows($overlap_check) > 0) {
    header("refresh:3;url=book_holiday.php");
    echo "<p class='error'>Error: You already have a booking that overlaps with these dates.</p>";
    exit();
}

$insert = pg_query_params($conn, "
    INSERT INTO bookings (user_passport, destination_ciudad, booking_begin, booking_end)
    VALUES ($1, $2, $3, $4)
", [$passport, $destination, $booking_begin, $booking_end]);

if ($insert) {
    header("refresh:3;url=index.php");
    echo "<p class='success'>Booking successful! Destination: $destination from $booking_begin to $booking_end.</p>";
} else {
    header("refresh:3;url=book_holiday.php");
    echo "<p class='error'>Error booking the destination.</p>";
}
?>
