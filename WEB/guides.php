<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Our Guides</title>
<link rel="stylesheet" href="styles.css">

</head>
<body>
  <header class="header container">
    <a href="book_holiday.php" class="book">Book now</a>
    <nav class="nav-boxes">
      <img src="images/logo.png" alt="DAW Logo" class="logo">
      <a href="index.php" class="nav-box">Home</a>
      <?php
          if (isset($_SESSION["user"])) {
              echo '<a href="logout.php" class="nav-box">Logout</a>';
          } else {
              echo '<a href="login.php" class="nav-box">Login</a>';
              echo '<a href="register_user.php" class="nav-box">Register</a>';
          }
      ?>
      <a href="view_users.php" class="nav-box">View Users</a>
      <a href="view_bookings.php" class="nav-box">My Bookings</a>
      <a href="guides.php" class="nav-box">See guides</a>
      <?php
        if (isset($_SESSION["user"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["user"]) . '!</p>';
        }
      ?>
    </nav>
  </header>

  <main class="container">
    <h1 class="guides-title">Meet Our Guides</h1>
        <p class="intro">Our team of expert guides is ready to show you 
            hidden gems and unforgettable experiences. Let’s meet them!</p>


    <div class="guides-container">
        <div class="guide-card">
            <img src="images/guides/marie.jpg" alt="Marie Dupont" class="guide-photo">
            <h3>Marie Dupont</h3>
            <p><strong>Specialty:</strong> Geography</p>
            <p><strong>City:</strong> Paris</p>
            <p>Marie’s passion for the diverse landscapes and hidden spots of Paris will take you off the beaten path and into the heart of the city’s natural beauty.</p>
        </div>

      <div class="guide-card">
        <img src="images/guides/hiroshi.jpg" alt="Hiroshi Tanaka" class="guide-photo">
        <h3>Hiroshi Tanaka</h3>
        <p><strong>Specialty:</strong> History</p>
        <p><strong>City:</strong> Tokyo</p>
        <p>Hiroshi brings Tokyo’s fascinating history to life, from ancient shrines to modern marvels. You’ll feel like you’re traveling through time itself!</p>
      </div>

      <div class="guide-card">
        <img src="images/guides/john.jpg" alt="John Smith" class="guide-photo">
        <h3>John Smith</h3>
        <p><strong>Specialty:</strong> Architecture</p>
        <p><strong>City:</strong> New York</p>
        <p>John will show you the towering skyscrapers, hidden architectural gems, and the creative spirit that defines the Big Apple.</p>
      </div>

      <div class="guide-card">
        <img src="images/guides/ana.jpg" alt="Ana Lopez" class="guide-photo">
        <h3>Ana Lopez</h3>
        <p><strong>Specialty:</strong> Food</p>
        <p><strong>City:</strong> Cancun</p>
        <p>Ana’s deep knowledge of Cancun’s vibrant food scene will introduce you to the most delicious local dishes and culinary traditions.</p>
      </div>
      <div class="guide-card">
        <img src="images/guides/lucas.jpg" alt="Lucas Martín" class="guide-photo">
        <h3>Lucas Martín</h3>
        <p><strong>Specialty:</strong> Architecture</p>
        <p><strong>City:</strong> Barcelona</p>
        <p>Lucas knows every hidden corner of Barcelona’s parks and surrounding hills. He’ll introduce you to the region’s unique ecosystems and natural wonders.</p>
        </div>

        <div class="guide-card">
        <img src="images/guides/sophie.jpg" alt="Sophie Dubois" class="guide-photo">
        <h3>Sophie Dubois</h3>
        <p><strong>Specialty:</strong> Food </p>
        <p><strong>City:</strong> Florence</p>
        <p>Sophie’s deep knowledge of Florence’s Renaissance treasures and modern art scene will transport you through centuries of creativity and inspiration.</p>
        </div>

    </div>
  </main>

  <footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
  </footer>
</body>
</html>
