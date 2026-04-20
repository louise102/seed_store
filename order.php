<?php
session_start();
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit; 
}
require 'backend/db.php';
$user_id = $_SESSION['user']['id'];
$cart = $_SESSION['cart'] ?? [];
if (!$cart) { 
    header('Location: cart.php'); 
    exit; 
}

// Calculate total
$total = 0;
foreach ($cart as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $price = $stmt->fetchColumn();
    $total += $price * $qty;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
    $stmt->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();
    foreach ($cart as $id => $qty) {
        $pdo->prepare("INSERT INTO order_items (order_id, product_id, qty) VALUES (?, ?, ?)")
            ->execute([$order_id, $id, $qty]);
    }
    unset($_SESSION['cart']);
    header("Location: order-confirmation.php?order_id=$order_id");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Confirm Order - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h1>Confirm Your Order</h1>
    <p class="auth-subtitle">Final step before your seeds ship</p>
    
    <div class="grid-4" style="margin: 24px 0;">
      <div style="text-align: center; padding: 16px;">
        <h3 style="color: var(--accent2);"><?php echo count($cart); ?> Items</h3>
      </div>
      <div style="text-align: center; padding: 16px;">
        <h3>Free Shipping</h3>
      </div>
      <div style="text-align: center; padding: 16px;">
        <h3>2-5 Days Delivery</h3>
      </div>
      <div style="text-align: center; padding: 16px;">
        <h3 style="color: var(--accent2); font-size: 1.5rem;">₱<?=$total?></h3>
        <p>Total</p>
      </div>
    </div>
    
    <form method="post" class="auth-form" style="text-align: center;">
      <p style="color: var(--muted); font-size: 1.1rem;">
        By placing this order you agree to our <a href="#">terms</a> and <a href="#">privacy policy</a>
      </p>
      <button type="submit" class="btn btn-primary auth-submit" style="font-size: 1.2rem; padding: 16px 40px;">
        Place Order Now - ₱<?=$total?>
      </button>
    </form>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
