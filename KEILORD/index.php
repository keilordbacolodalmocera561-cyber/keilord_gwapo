<?php
session_start();
include 'includes/db.php'; 

$isLoggedIn = isset($_SESSION['id']); 
?>

<link rel="stylesheet" href="assets/css/style.css">

<header>
  <nav>
  <ul class="nav-links">
    <li><a style="font-weight: bold; font-size: 1.2em; color: red;">KLORD'S CLOTHING</a></li>
    <li><a href="products/women.php">WOMEN</a></li>
    <li><a href="products/men.php">MEN</a></li>
    <li><a href="products/kids.php">KIDS</a></li>
    <li><a href="products/sport.php">SPORTSWEAR</a></li>
  </ul>

  <div class="nav-right">
    <a href="#promo-banner">Home</a>
    <a href="#welcome">Featured</a>
    <a href="#contact">Contact</a>
    <a href="#about">About</a>
    
    <input type="text" placeholder="Search the best clothes that fits your style">
    <?php if ($isLoggedIn): ?>
       <div class="dropdown">
  <a href="#" class="dropbtn">Orders ▾</a>
  <div class="dropdown-content">
    <a href="#" onclick="openOrdersModal('pending')">Pending</a>
    <a href="#" onclick="openOrdersModal('to_ship')">To Ship</a>
    <a href="#" onclick="openOrdersModal('to_receive')">To Receive</a>
    <a href="#" onclick="openOrdersModal('delivered')">Delivered</a>
  </div>
</div>

      <a href="logout.php">Logout</a>
    <?php else: ?>
      <a href="login.php">Login</a>
      <a href="register.php">Register</a>
    <?php endif; ?>
    <a href="cart.php" style="font-size: 1.5em;">🛒</a>
  </div>
</nav>

</header>
<br id="promo-banner">
<main class="landing">
 <section class="promo-banner">
  <div class="card-info">
    <div class="card-text">  
      <h2 style="color: red; font-size: 2.0em;">🔥LIMITED OFFER FOR YOU!!!</h2>
    <h3>Don't miss out on these exclusive deals!</h3></div>
      <div class="carousel-container">
  <div class="carousel-track">
    <div class="image-box">
      <img src="assets/images/uses/women.jpg" alt="Promo 1">
      <div class="image-text">Up to 50% Off – Limited Time</div>
    </div>
    <div class="image-box">
      <img src="assets/images/uses/men.png" alt="Promo 2">
      <div class="image-text">Exclusive Offer – Shop Now</div>
    </div>
    <div class="image-box">
      <img src="assets/images/uses/kid.png" alt="Promo 3">
      <div class="image-text">New Arrivals – Don't Miss Out</div>
    </div>
    <div class="image-box">
      <img src="assets/images/uses/sport.png" alt="Promo 4">
      <div class="image-text">Best Sportswear – Don't Miss Out</div>
    </div>
  </div>
</div>
      <h3>Bring back the JOY of Shopping with the</h3>
      <h1>Klord Credit Card</h1>
      <ul>
        <li style="color: red">5,000 pesos Welcome Gift</li>
        <li style="color: red">Crafted Free Shipping</li>
        <li style="color: red">Up to 6% Cashback</li>
      </ul>
    </div>
<div class="landing-gallery">
  <div class="gallery-grid">
    <h3>Shop the Latest Trends 🛒</h3>
    <a href="products/women.php">
      <img src="assets/images/uses/women.jpg" alt="Shop Women">
    </a>
    <a href="products/men.php">
      <img src="assets/images/uses/men.png" alt="Shop Men">
    </a>
    <a href="products/kids.php">
      <img src="assets/images/uses/kid.png" alt="Shop Kids">
    </a>
    <a href="products/sport.php">
      <img src="assets/images/uses/sport.png" alt="Shop Sportswear">
    </a>
     
      <h4 style="color: red;">🔥LIMITED OFFER FOR YOU!!!</h4>
      
    
  </div>
</div>
  <div class="carousel">
    <h3>Discover Your Style</h3>
    <p style="color: #444;">Explore our curated collections and find your perfect fit.</p>
    <p style="color: #444;" >Shop now and enjoy exclusive discounts!</p>
    <p style="color: #444;">Don't miss out on our limited-time offers!  </p>
    <p style="color: #444;">Join our newsletter for the latest updates. </p>
    <p style="color: #444;">Stay ahead in fashion with Klord’s.</p>
    <p style="color: #444;">Be the first to know about new arrivals and exclusive offers! </p>
    <h4 style="color: red;">🔥LIMITED OFFER FOR YOU!!!</h4>
    <p style="color: #444;">Sign up now and get 10% off your first purchase!</p>
    <p style="color: #444;">Hurry, offer ends soon!</p>
    <br>
   
  </div>
</section>

<br id="welcome">
<br>
<br>
  <section  class="welcome">
    <h2>Welcome to Klord’s Clothing</h2>
    <p >Step into a world of refined style and artisanal craftsmanship. At Klord’s, every piece tells a story—designed with intention, tailored for elegance, and made to elevate your everyday.</p>
  </section>

  <section class="categories">
    <div class="category">
      <img src="assets/images/uses/women.jpg" alt="Shop Women">
      <br>
      <a href="products/women.php" class="btn">SHOP WOMEN ></a>
    </div>
    <div class="category">
      <img src="assets/images/uses/men.png" alt="Shop Men">
            <br>
      <a href="products/men.php" class="btn">SHOP MEN ></a>
    </div>
    <div class="category">
      <img src="assets/images/uses/kid.png" alt="Shop Kids">
            <br>
      <a href="products/kids.php" class="btn">SHOP KIDS ></a>
    </div>
    <div class="category">
      <img src="assets/images/uses/sport.png" alt="Shop Sportswear">
            <br>
      <a href="products/sport.php" class="btn">SHOP SPORTSWEAR ></a>
    </div>
  </section>
  <br id="contact">
  <br>
  <br>

<section class="contact-section">
  <h2>Contact Us</h2>
  <p class="contact-subtitle">We’d love to hear from you. Whether it’s feedback, questions, or partnership ideas—drop us a message below.</p>
  <form method="post" action="contact.php" class="contact-form">
    <input type="text" name="name" placeholder="Your Name" required>
    <input type="email" name="email" placeholder="Your Email" required>
    <textarea name="message" placeholder="Your Message" required></textarea>
    <button type="submit">Send Message</button>
  </form>
</section>
<section id="about" class="about">
  <h2>About Us</h2>
  <p>
    At Klord’s Clothing, we believe fashion is more than fabric—it’s a reflection of identity, confidence, and craftsmanship. Founded with a vision to redefine everyday elegance, Klord blends timeless design with modern sensibility, offering curated collections that speak to both style and substance.
  </p>
  <p>
    Every piece we create is thoughtfully designed and meticulously crafted to ensure quality, comfort, and distinction. From tailored essentials to statement pieces, our garments are made to elevate your wardrobe and empower your presence—whether you're dressing for the boardroom, the boulevard, or beyond.
  </p>
  <p>
    We are committed to ethical sourcing, sustainable practices, and a seamless shopping experience. With Klord, you’re not just wearing fashion—you’re embracing a lifestyle of refinement, authenticity, and purpose.
  </p>
</section>
</main>

<!-- Orders Modal -->
<div id="ordersModal" class="modal">
  <div class="modal-content">
    <span class="close" onclick="closeOrdersModal()">&times;</span>
    <h2 id="modalTitle">My Orders</h2>
    <div id="ordersContainer">
      <p>Loading orders...</p>
    </div>
  </div>
</div>


<script>
function openOrdersModal(status) {
  document.getElementById("ordersModal").style.display = "flex";
  document.getElementById("modalTitle").innerText = "Orders - " + status.replace("_", " ").toUpperCase();

  // Fetch orders with AJAX
  fetch("fetch_orders.php?status=" + status)
    .then(response => response.text())
    .then(data => {
      document.getElementById("ordersContainer").innerHTML = data;
    });
}

function closeOrdersModal() {
  document.getElementById("ordersModal").style.display = "none";
}
</script>
