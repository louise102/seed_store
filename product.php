<?php
session_start();
require 'backend/db.php';
$isLoggedIn = isset($_SESSION['user']);
$successMessage = isset($_GET['added']) ? 'Product added to cart successfully.' : '';
$categoryFilter = trim($_GET['category'] ?? '');
if ($categoryFilter) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE category = ? ORDER BY name");
    $stmt->execute([$categoryFilter]);
    $products = $stmt->fetchAll();
} else {
    $products = $pdo->query("SELECT * FROM products")->fetchAll();
}
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?php echo $categoryFilter ? htmlspecialchars($categoryFilter . ' Seeds') : 'Products'; ?> - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h2><?php echo $categoryFilter ? htmlspecialchars($categoryFilter . ' Seeds') : 'Our Seed Collection'; ?></h2>
    <p><?php echo $categoryFilter ? 'Browse our selection of ' . htmlspecialchars($categoryFilter) . ' seeds.' : 'Explore our complete range of premium organic and heirloom seeds.'; ?></p>
  </section>
  <?php if ($successMessage): ?>
    <div class="alert-success"><?=htmlspecialchars($successMessage)?></div>
  <?php endif; ?>
  <?php if (!$products): ?>
    <div class="card" style="margin-top: 16px; text-align: center;">
      <p>No products found in this category yet.</p>
    </div>
  <?php else: ?>
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
  <?php endif; ?>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
