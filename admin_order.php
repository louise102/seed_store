<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: admin.login.php'); 
    exit; 
}
if (!isset($_GET['id'])) {
    header('Location: dashboard.php');
    exit;
}
$order_id = (int)$_GET['id'];
require 'backend/db.php';
$stmt = $pdo->prepare("SELECT o.*, c.name as username, c.email FROM orders o JOIN customers c ON o.user_id = c.id WHERE o.id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();
if (!$order) {
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
  <title>Order Details - Admin</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h1>Order #<?=$order['id']?> Details</h1>
    <p class="auth-subtitle">Customer: <?php echo htmlspecialchars($order['username']); ?> (<?php echo htmlspecialchars($order['email']); ?>)</p>
    
    <div style="background: #f0f9f0; border: 2px solid #1b5e20; border-radius: 16px; padding: 24px; margin: 24px 0; text-align: center;">
      <p style="font-size: 2.2rem; color: var(--accent2); margin: 8px 0;">₱<?=$order['total']?></p>
      <p>Status: <strong><?php echo ucfirst($order['status']); ?></strong></p>
      <p>Payment Method: <?php echo htmlspecialchars($order['payment_method'] ?? 'N/A'); ?></p>
      <?php if ($order['payment_method'] === 'GCash' && $order['gcash_number']): ?>
        <p>GCash Number: <?php echo htmlspecialchars($order['gcash_number']); ?></p>
      <?php endif; ?>
      <p>Tracking Number: <?php echo htmlspecialchars($order['tracking_number'] ?? 'N/A'); ?></p>
      <p>Placed on <?php echo date('M j, Y \\a\\t g:i A', strtotime($order['created_at'])); ?></p>
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
      <a class="btn btn-secondary" href="dashboard.php">Back to Dashboard</a>
      <?php if ($order['status'] == 'pending'): ?>
        <a href="dashboard.php?confirm=<?=$order['id']?>" class="btn btn-primary">Confirm Order</a>
      <?php elseif ($order['status'] == 'confirmed'): ?>
        <a href="dashboard.php?deliver=<?=$order['id']?>" class="btn btn-primary">Mark as Delivered</a>
      <?php endif; ?>
    </div>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>