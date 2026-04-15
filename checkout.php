<?php
session_start();
require 'backend/db.php';
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit; 
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple checkout, assume payment success
    $user_id = $_SESSION['user']['id'];
    $cart = $_SESSION['cart'] ?? [];
    if ($cart) {
        $total = 0;
        foreach ($cart as $id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $price = $stmt->fetchColumn();
            $total += $price * $qty;
        }
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status) VALUES (?, ?, 'pending')");
        $stmt->execute([$user_id, $total]);
        $order_id = $pdo->lastInsertId();
        foreach ($cart as $id => $qty) {
            $pdo->prepare("INSERT INTO order_items (order_id, product_id, qty) VALUES (?, ?, ?)")->execute([$order_id, $id, $qty]);
        }
        unset($_SESSION['cart']);
        header("Location: order-confirmation.php?order_id=$order_id");
        exit;
    }
}

// Calculate cart total for display
$cart = $_SESSION['cart'] ?? [];
$total = 0;
foreach ($cart as $id => $qty) {
    $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
    $stmt->execute([$id]);
    $price = $stmt->fetchColumn();
    $total += $price * $qty;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Checkout - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h1>Secure Checkout</h1>
    <p class="auth-subtitle">Review your order and complete purchase</p>
    
    <div class="contact-grid">
      <div>
        <h3>Order Summary</h3>
        <p><strong>Total: ₱<?=$total?></strong></p>
        <p>Shipping: Free</p>
        <p class="auth-divider"><span>Seeds ship in 2-5 days</span></p>
      </div>
      <div>
        <h3>Payment</h3>
        <p>We accept all major cards. Secure checkout.</p>
        <form method="post" class="auth-form">
          <label>Card Number</label>
          <input type="text" placeholder="1234 5678 9012 3456" required>
          <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div>
              <label>Expiry</label>
              <input type="month" required>
            </div>
            <div>
              <label>CVV</label>
              <input type="text" placeholder="123" required>
            </div>
          </div>
          <button type="submit" class="btn btn-primary auth-submit">Pay ₱<?=$total?></button>
        </form>
      </div>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
