<?php
require 'backend/db.php';

if (!isset($_GET['id'])) { 
    die('Missing id'); 
}
$id = (int)$_GET['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $qty = intval($_POST['qty']);
    $image_url = trim($_POST['image_url'] ?? '');
    $stmt = $pdo->prepare("UPDATE products SET name=?, category=?, price=?, qty=?, image_url=? WHERE id=?");
    $stmt->execute([$name, $category, $price, $qty, $image_url, $id]);
    header('Location: dashboard.php'); 
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
$stmt->execute([$id]);
$product = $stmt->fetch();
if (!$product) { 
    die('Product not found'); 
}
session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: admin.login.php'); 
    exit; 
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Edit Product - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="auth-card" style="max-width: 520px; margin: 0 auto;">
    <a class="auth-back" href="dashboard.php">&amp;larr; Back to Dashboard</a>
    <h1>Edit Product</h1>
    <p class="auth-subtitle">Update <strong><?php echo htmlspecialchars($product['name']); ?></strong></p>

    <form method="post" class="auth-form">
      <label>Product Name</label>
      <input name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>

      <label>Category</label>
      <input name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>

      <label>Price (₱)</label>
      <input name="price" type="number" step="0.01" value="<?=$product['price']?>" required>

<label>Stock Quantity</label>
      <input name="qty" type="number" value="<?=$product['qty']?>" required>

      <label>Image URL (optional)</label>
      <input name="image_url" type="url" value="<?=htmlspecialchars($product['image_url'] ?? '')?>">

      <div style="display: flex; gap: 12px;">
        <button type="submit" class="btn btn-primary" style="flex: 1;">Update Product</button>
        <a href="dashboard.php" class="btn btn-secondary" style="flex: 1; text-align: center; line-height: 44px;">Cancel</a>
      </div>
    </form>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
