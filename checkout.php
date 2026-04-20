<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require 'backend/db.php';
if (!isset($_SESSION['user'])) { 
    header('Location: login.php'); 
    exit; 
}
function ensureOrderColumns(PDO $pdo) {
    try {
        $existing = [];
        $stmt = $pdo->query("SHOW COLUMNS FROM orders");
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $existing[] = $row['Field'];
        }
            if (!in_array('status', $existing, true)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN status VARCHAR(50) DEFAULT 'pending'");
        }
        if (!in_array('payment_method', $existing, true)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) DEFAULT 'Cash on Delivery'");
        }
        if (!in_array('tracking_number', $existing, true)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(20)");
        }
        if (!in_array('gcash_number', $existing, true)) {
            $pdo->exec("ALTER TABLE orders ADD COLUMN gcash_number VARCHAR(100)");
        }
    } catch (PDOException $e) {
        // Ignore schema updates when not allowed; the main insert will still show the real error.
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Simple checkout, assume payment success
    $user_id = $_SESSION['user']['id'];
    $cart = $_SESSION['cart'] ?? [];
    $payment_method = $_POST['payment_method'] ?? 'Cash on Delivery';
    $gcash_number = $_POST['gcash_number'] ?? '';
    if ($cart) {
        $total = 0;
        foreach ($cart as $id => $qty) {
            $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
            $stmt->execute([$id]);
            $price = $stmt->fetchColumn();
            $total += $price * $qty;
        }
        // Ensure required order columns exist before insert
        ensureOrderColumns($pdo);
        // Generate tracking number
        $tracking_number = 'TRK' . strtoupper(substr(md5(uniqid()), 0, 8));
        try {
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, status, payment_method, tracking_number, gcash_number) VALUES (?, ?, 'pending', ?, ?, ?)");
            $stmt->execute([$user_id, $total, $payment_method, $tracking_number, $gcash_number]);
        } catch (PDOException $e) {
            die('Checkout failed: ' . htmlspecialchars($e->getMessage()));
        }
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
            <label>GCash Number</label>
            <input type="text" name="gcash_number" placeholder="Enter your GCash number">
            <p style="margin-top: 16px;">Scan the QR code below to pay ₱<?=$total?> to the provided GCash number:</p>
            <div id="qr-code" style="text-align: center; margin-top: 16px;"></div>
          </div>
          <script>
            const gcashInput = document.querySelector('input[name="gcash_number"]');
            document.querySelectorAll('input[name="payment_method"]').forEach(radio => {
              radio.addEventListener('change', function() {
                const gcashFields = document.getElementById('gcash-fields');
                if (this.value === 'GCash') {
                  gcashFields.style.display = 'block';
                  gcashInput.required = true;
                } else {
                  gcashFields.style.display = 'none';
                  gcashInput.required = false;
                  gcashInput.value = '';
                  document.getElementById('qr-code').innerHTML = '';
                }
              });
            });
            gcashInput.addEventListener('input', function() {
              const number = this.value;
              const total = <?=$total?>;
              if (number) {
                const qrData = `GCash Payment: Number ${number}, Amount ₱${total}`;
                const qrUrl = `https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=${encodeURIComponent(qrData)}`;
                document.getElementById('qr-code').innerHTML = `<img src="${qrUrl}" alt="GCash QR Code">`;
              } else {
                document.getElementById('qr-code').innerHTML = '';
              }
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
