<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Travel Website</title>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" 
  rel="stylesheet">
 <link rel="stylesheet" href="./styles.css">
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
          <a href="view_bookings.php" class="nav-box">View Bookings</a>
           <?php
            if (isset($_SESSION["user"])) {
                echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["user"]) . '!</p>';
            }
            ?>
            </nav>
           </header>
      
      <section class="hero-container">
          <div class="hero-image">
            <img src="images/section1.png" alt="Man holding passport">
            <img src="images/mapa.png" class="map-icon" alt="Map Icon">
          </div>
        <div class="hero-content">
          <h1><br>Discover the<br> Best Lovely<br> Places</h1>
          <p>Plan and book your perfect trip with expert advice, travel<br> tips, destination information, and inspiration from us.</p>
          <div class="guides-section">
            <a href="guides.php">
          <img src="images/guides.jpg" alt="Our Guides" class="guides-image">
             </a>
        </div>
        </div>
        
      </section>
      
      <section class="destinations-container">
        <h2>Find Popular<br> Destination</h2>
        <div class="card-container">
          <div class="card">
            <img src="images/noticia1.jpg" alt="Mountain Hiking" class="card-image">
            <h3>Mountain Hiking Tour</h3>
            <p>Mountain Hicking Tour</p>
            <a style="text-decoration:none" href="bookings/create.php" class="card-button">Book Now</a>

          </div>
          <div class="card">
            <img src="images/noticia2.jpg" alt="Machu Picchu" class="card-image">
            <h3>Machu Picchu, Peru</h3>
            <p>Machu Picchu, Peru</p>
            <a style="text-decoration:none" href="bookings/create.php" class="card-button">Book Now</a>

          </div>
          <div class="card">
            <img src="images/noticia3.jpg" alt="Grand Canyon" class="card-image">
            <h3>The Grand Canyon, Arizona</h3>
            <p>Mountain Hicking Tour</p>
            <a style="text-decoration:none" href="bookings/create.php" class="card-button">Book Now</a>

          </div>
        </div>
      </section>
      
      <section class="newsletter">
        <div class="newsletter-content">
          <mg src="images/bg-newsletter.jpg" alt="Newsletter" class="newsletter-image">
          <h2><br>Sign up to our newsletter</h2>
          <p>Receive the latest news, updates, and many other things<br> every week.</p>
          <form>
            <input type="email" placeholder="   Enter your email address ">
          </form>
        </div>
      </section>
  </main>

<?php
require_once "DB_Connection.php";
?>

  <footer class="footer-container">
    <img src="images/logo.png" alt="DAW Logo" class="logo1">
    <p>Enjoy the touring</p>
    <img src="images/redes.png" alt="DAW Logo" class="redes">
  </footer>
</body>
</html>