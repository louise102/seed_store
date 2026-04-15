<?php
require 'backend/db.php';
session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: admin.login.php'); 
    exit; 
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
$qty = intval($_POST['qty']);
    $image_url = trim($_POST['image_url'] ?? '');
    if ($name && $category) {
$stmt = $pdo->prepare("INSERT INTO products (name, category, price, qty, image_url) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $qty, $image_url]);
        $success = "Product added successfully!";
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Please fill name and category.";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Add Product - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="auth-card" style="max-width: 520px; margin: 0 auto;">
    <a class="auth-back" href="dashboard.php">&amp;larr; Back to Dashboard</a>
    <h1>Add New Product</h1>
    <p class="auth-subtitle">Add a new product to the Seed Store catalog</p>

    <?php if (isset($error)): ?>
      <div class="alert-error"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <?php if (isset($success)): ?>
      <div style="background: #e6ffe6; color: #1b5e20; padding: 12px; border-radius: 8px; margin-bottom: 20px;">
        <?php echo htmlspecialchars($success); ?>
      </div>
    <?php endif; ?>

    <form method="post" class="auth-form">
      <label>Product Name</label>
      <input name="name" placeholder="e.g. Organic Tomato Seeds" required>

      <label>Category</label>
      <input name="category" placeholder="e.g. Vegetables" required>

      <label>Price (₱)</label>
      <input name="price" type="number" step="0.01" placeholder="0.00" required>

<label>Quantity in Stock</label>
      <input name="qty" type="number" placeholder="0" required>

<label>Image URL (optional)</label>
      <input name="image_url" type="url" placeholder="https://example.com/product.jpg">


      <button type="submit" class="btn btn-primary auth-submit">Add Product</button>
    </form>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
