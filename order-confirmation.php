<?php
session_start();
if (!isset($_GET['order_id'])) {
    header('Location: cart.php');
    exit;
}
$order_id = (int)$_GET['order_id'];
require 'backend/db.php';
$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order || $order['user_id'] != ($_SESSION['user']['id'] ?? 0)) {
    die('Order not found');
}

// Fetch order items
$stmt = $pdo->prepare("
    SELECT oi.*, p.name, p.price 
    FROM order_items oi 
    JOIN products p ON oi.product_id = p.id 
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Order Confirmed - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h1 style="color: var(--accent2);">✅ Order Confirmed</h1>
    <p class="auth-subtitle">Thank you for your purchase! Your seeds are on the way.</p>
    
    <div style="background: #f0f9f0; border: 2px solid #1b5e20; border-radius: 16px; padding: 24px; margin: 24px 0; text-align: center;">
      <h2>Order #<?=$order['id']?></h2>
      <p style="font-size: 2.2rem; color: var(--accent2); margin: 8px 0;">₱<?=$order['total']?></p>
      <p>Placed on <?php echo date('M j, Y \\a\\t g:i A', strtotime($order['created_at'])); ?></p>
      <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
      <?php if ($order['payment_method'] === 'GCash' && $order['gcash_number']): ?>
        <p><strong>GCash Number:</strong> <?php echo htmlspecialchars($order['gcash_number']); ?></p>
      <?php endif; ?>
      <p><strong>Tracking Number:</strong> <?php echo htmlspecialchars($order['tracking_number']); ?></p>
    </div>
    
    <h3>Order Items</h3>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--panel);">
            <th style="padding: 12px;">Product</th>
            <th style="padding: 12px; text-align: center;">Qty</th>
            <th style="padding: 12px; text-align: right;">Price</th>
            <th style="padding: 12px; text-align: right;">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($items as $item): ?>
          <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 12px;"><?php echo htmlspecialchars($item['name']); ?></td>
            <td style="padding: 12px; text-align: center;"><?=$item['qty']?></td>
            <td style="padding: 12px; text-align: right;">₱<?=$item['price']?></td>
            <td style="padding: 12px; text-align: right; font-weight: 600;">₱<?=$item['price'] * $item['qty']?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
    
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-top: 32px;">
      <a class="btn btn-primary" href="product.php">Continue Shopping</a>
      <a class="btn btn-secondary" href="my-orders.php">View Orders</a>
    </div>
    
    <div style="background: var(--panel); padding: 20px; border-radius: 12px; margin-top: 32px; text-align: center; color: var(--muted);">
      <p>⏱️ Seeds ship within 2-5 business days | 📦 Track your order anytime</p>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
