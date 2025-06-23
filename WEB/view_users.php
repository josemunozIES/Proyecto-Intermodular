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
<div class="main-body">
    <header class="header_container">
      <a href="book_holiday.php" class="book">Book now</a>
      <nav class="nav-boxes">
        <img src="images/logo.png" alt="DAW Logo" class="logo">
        <a href="index.php" class="nav-box">Home</a>
        <?php
          if (isset($_SESSION["email"])) {
              echo '<a href="logout.php" class="nav-box">Logout</a>';
          } else {
              echo '<a href="login.php" class="nav-box">Login</a>';
              echo '<a href="register_user.php" class="nav-box">Register</a>';
          }

          if (isset($_SESSION['admin']) && $_SESSION['admin']) {
              echo '<a href="view_users.php" class="nav-box">View users</a>';
          } else if (isset($_SESSION["email"])){
            echo '<a href="view_users.php" class="nav-box">My Profile</a>';
          }
        ?>
        <a href="view_bookings.php" class="nav-box">My Bookings</a>
        <a href="guides.php" class="nav-box">Our Guides</a>
        <?php
        if (isset($_SESSION['admin']) && $_SESSION['admin']) {
<<<<<<< HEAD
              echo '<a href="list_destinations.php" class="nav-box">Our destinations</a>';
=======
              echo '<a href="list_destinations.php" class="nav-box">Edit destinations</a>';
>>>>>>> origin/develop
          } else {
            echo '<a href="list_destinations.php" class="nav-box">Destinations</a>';
          }
        if (isset($_SESSION["nombre"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["nombre"]) . '!</p>';
        }
      ?>
      </nav>
    </header>
<?php
include("DB_Connection.php");

// Determinar si usuario es admin
$isAdmin = isset($_SESSION['admin']) && $_SESSION['admin'];
$isUser = isset($_SESSION['email']) && $_SESSION['email'];

if (!$isAdmin && !$isUser) {
    echo "<p class='error'>You must be logged in to view this page.</p>";
    exit;
}

echo "<h2 class='guides-title'>" . ($isAdmin ? "Usuarios registrados" : "My Profile") . "</h2>";
echo "<div class='guides-container'>"; // Flexbox estilo guía

if ($isAdmin) {
    // Admin: ve todo
    $query = "SELECT * FROM usuarios";
} else {
    // Regular user: solo su perfil
    $email = pg_escape_string($conn, $_SESSION['email']);
    $query = "SELECT * FROM usuarios WHERE email = '$email'";
}

$result = pg_query($conn, $query);

if (!$result) {
    echo "<p class='error'>Error retrieving users.</p>";
    exit;
}

while ($row = pg_fetch_assoc($result)) {
    echo "<div class='guide-card'>";
    echo "<h3>" . htmlspecialchars($row['nombre']) . "</h3>";
    echo "<p>" . htmlspecialchars($row['email']) . "</p>";

    echo "<div style='margin-top: 15px; display: flex; gap: 10px; justify-content: center;'>";
    echo "<a href='edit_user.php?email=" . urlencode($row['email']) . "' class='sub-button'>Edit</a>";
    
   
if ($isAdmin || ($isUser && $_SESSION['email'] === $row['email'])) {
    echo "<a href='delete_user.php?email=" . urlencode($row['email']) . "' class='sub-button' onclick=\"return confirm('Are you sure you want to delete this account?');\">Delete</a>";
}


    echo "</div>";
    echo "</div>";
}

echo "</div>";
?>

<footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
</footer>
</div>
</body>
</html>
