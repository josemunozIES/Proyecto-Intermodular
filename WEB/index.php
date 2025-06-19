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
              echo '<a href="list_destinations.php" class="nav-box">Edit destinations</a>';
          } else {
            echo '<a href="list_destinations.php" class="nav-box">Destinations</a>';
          }
        if (isset($_SESSION["nombre"])) {
            echo '<p style="font-size: 20px;">Hi ' . htmlspecialchars($_SESSION["nombre"]) . '!</p>';
        }
      ?>
      </nav>
    </header>

      <section class="hero-container">
        <div class="hero-content">
          <h1><br>Discover the<br> Most Lovely<br> Places</h1>
          <p>Plan and book your perfect trip with expert<br>advice, travel tips, destination information,<br>and inspiration from us.</p>
        </div>
        <div class="hero-image">
          <img src="images/section1.png" class="man-icon" alt="Man holding passport">
          <img src="images/mapa.png" class="map-icon" alt="Map Icon">
        </div>
      </section>
      <section class="destinations-container">
        <h2 class="titulo-interactivo">Popular Destinations</h2>
        <hr>
        <div class="card-container">
          <div class="card">
            <img src="images/noticia1.jpg" alt="Mountain Hiking" class="card-image">
            <h3>Bali, Indonesia</h3>
            <p>Touristic Tour</p>
            <a style="text-decoration:none" href="book_holiday.php" class="card-button">Book Now</a>

          </div>
          <div class="card">
            <img src="images/noticia2.jpg" alt="Machu Picchu" class="card-image">
            <h3>Machu Picchu, Peru</h3>
            <p>Touristic Tour</p>
            <a style="text-decoration:none" href="book_holiday.php" class="card-button">Book Now</a>

          </div>
          <div class="card">
            <img src="images/noticia3.jpg" alt="Grand Canyon" class="card-image">
            <h3>The Grand Canyon, Arizona</h3>
            <p>Mountain Hicking Tour</p>
            <a style="text-decoration:none" href="book_holiday.php" class="card-button">Book Now</a>

          </div>
        </div>
        <hr>
      </section>
      <br><br>
      <section>
        <div class="guides-section">
          <div class="card">
              <a href="guides.php">
                <img src="images/guides.jpg" alt="Our Guides" class="card-know">
                <h2 class="title-guides">Get to know our guides</h2>
              </a>
          </div>
          <div class="card register-box">
            <?php
              if (isset($_SESSION["email"])) {
                echo '<p>¡Welcome ' . $_SESSION["nombre"] . '!<br><br>A lot of adventures are waiting for you, <br><br><a href="book_holiday.php">¡Book a trip now!</a></p>';
              } else {
                echo '<p>¡Welcome to DAW travels!<br><br>A lot of adventures are waiting here, <br><br><a href="register_user.php">¡Register now!</a></p>';
              }
            ?>
          </div>
          <div class="card">
            <a href="list_destinations.php">
                <img src="images/destination.jpg" alt="Our Guides" class="card-know">
                <h2 class="title-destination">Discover all of our destinations</h2>
            </a>
          </div>
        </div>
      </section>
      
      
      <section class="newsletter">
        <div class="newsletter-content">
          <mg src="images/bg-newsletter.jpg" alt="Newsletter" class="newsletter-image">
          <h2>Sign up to our newsletter</h2>
          <p>Receive the latest news, updates, and many other things<br> every week.</p>
            <form method="post" action="">
            <input type="email" name="newsletter_email" placeholder="    Enter your email address" required>
            <input type="submit" name="subscribe" value="Subscribe" class="sub-button">
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
</div>
</body>
</html>