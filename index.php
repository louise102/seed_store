<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Seed Store - Home</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="hero">
    <div class="left">
      <h2>Grow your dreams with organic seeds</h2>
      <p>From vibrant flowers to fresh vegetables, discover our premium collection of organic and heirloom seeds to cultivate your perfect garden.</p>
      <a class="btn btn-primary" href="product.php">Shop Now</a>
      <a class="btn btn-secondary" href="product.php">View Catalog</a>
      <div style="display:flex; gap: 20px; margin-top:18px;">
        <div><strong>25+</strong><br>Years Experience</div>
        <div><strong>500+</strong><br>Products</div>
        <div><strong>10k+</strong><br>Happy Farmers</div>
      </div>
    </div>
    <div class="right">
      <img src="logo.jpg" alt="Seed Store Logo" style="width:100%; border-radius:18px; box-shadow:var(--shadow);">
    </div>
  </section>

  <section class="card">
    <h3 class="section-title">Our Core Values</h3>
    <div class="grid-4">
      <div><h4>1. Sustainability</h4><p>Supporting local farms, biodiversity and eco-friendly practices.</p></div>
      <div><h4>2. Quality</h4><p>Seed lot tested, certified organic, premium germination.</p></div>
      <div><h4>3. Community</h4><p>Growing together through education and customer care.</p></div>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>