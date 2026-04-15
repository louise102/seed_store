<?php
session_start();
require 'backend/db.php';
$isLoggedIn = isset($_SESSION['user']);
$successMessage = isset($_GET['added']) ? 'Product added to cart successfully.' : '';
$products = $pdo->query("SELECT * FROM products")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Products - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h2>Our Seed Collection</h2>
    <p>Explore our complete range of premium organic and heirloom seeds.</p>
  </section>
  <?php if ($successMessage): ?>
    <div class="alert-success"><?=htmlspecialchars($successMessage)?></div>
  <?php endif; ?>
  <section class="grid-4" style="margin-top:16px;">
    <?php foreach ($products as $p): ?>
      <article class="card">
        <?php if (!empty($p['image_url'])): ?>
          <img src="<?=htmlspecialchars($p['image_url'])?>" alt="<?=htmlspecialchars($p['name'])?>" style="width:100%; height:180px; object-fit:cover; border-radius:12px; margin-bottom:16px;" onerror="this.style.display='none';">
        <?php endif; ?>
        <h3><?=htmlspecialchars($p['name'])?></h3>
        <p><strong>Category:</strong> <?=htmlspecialchars($p['category'])?></p>
        <p><strong>Price:</strong> ₱<?=number_format($p['price'],2)?></p>
        <p><strong>Stock:</strong> <?=$p['qty']?></p>
        <?php if ($isLoggedIn): ?>
          <a class="btn btn-primary" href="cart.php?add=<?=$p['id']?>">Add to Cart</a>
        <?php else: ?>
          <a class="btn btn-primary" href="cart.php?add=<?=$p['id']?>">Add</a>
        <?php endif; ?>
      </article>
    <?php endforeach; ?>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
