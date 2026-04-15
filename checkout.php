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
    $payment_method = $_POST['payment_method'] ?? 'Cash on Delivery';
    $gcash_ref = $_POST['gcash_ref'] ?? '';
    if ($cart) {
        $total = 0;
        foreach ($cart as $id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $price = $stmt->fetchColumn();
            $total += $price * $qty;
        }
        // Generate tracking number
        $tracking_number = 'TRK' . strtoupper(substr(md5(uniqid()), 0, 8));
        $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, payment_method, tracking_number, gcash_ref) VALUES (?, ?, 'pending', ?, ?, ?)");
        $stmt->execute([$user_id, $total, $payment_method, $tracking_number, $gcash_ref]);
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
        <h3>Payment Method</h3>
        <p>Choose your payment method.</p>
        <form method="post" class="auth-form">
          <div style="margin-bottom: 16px;">
            <label style="display: block; margin-bottom: 8px;">
              <input type="radio" name="payment_method" value="Cash on Delivery" checked> Cash on Delivery
            </label>
            <label style="display: block; margin-bottom: 8px;">
              <input type="radio" name="payment_method" value="GCash"> GCash
            </label>
          </div>
          <div id="gcash-fields" style="display: none;">
            <label>GCash Reference Number</label>
            <input type="text" name="gcash_ref" placeholder="Enter GCash reference number">
          </div>
          <script>
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
              radio.addEventListener('change', function() {
                const gcashFields = document.getElementById('gcash-fields');
                if (this.value === 'GCash') {
                  gcashFields.style.display = 'block';
                } else {
                  gcashFields.style.display = 'none';
                }
              });
            });
          </script>
          <button type="submit" class="btn btn-primary auth-submit">Place Order - ₱<?=$total?></button>
        </form>
      </div>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
