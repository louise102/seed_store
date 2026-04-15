<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: admin.login.php'); 
    exit; 
}
require 'backend/db.php';
if (isset($_GET['confirm'])) {
    $order_id = (int)$_GET['confirm'];
    $stmt = $pdo->prepare("UPDATE orders SET status = 'confirmed' WHERE id = ?");
    $stmt->execute([$order_id]);
    header('Location: dashboard.php');
    exit;
}
if (isset($_GET['deliver'])) {
    $order_id = (int)$_GET['deliver'];
    $stmt = $pdo->prepare("UPDATE orders SET status = 'delivered' WHERE id = ?");
    $stmt->execute([$order_id]);
    header('Location: dashboard.php');
    exit;
}
$products = $pdo->query("SELECT * FROM products ORDER BY category, name")->fetchAll();
$orders = $pdo->query("SELECT o.*, u.username FROM orders o JOIN users u ON o.user_id = u.id ORDER BY o.created_at DESC")->fetchAll();

// Group products by category
$products_by_category = [];
foreach ($products as $p) {
    $products_by_category[$p['category']][] = $p;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Admin Dashboard - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <div class="card" style="margin-bottom: 24px;">
    <h1>Admin Dashboard</h1>
    <a class="btn btn-primary" href="addproduct.php" style="display: inline-block; margin-bottom: 20px;">+ Add Product</a>
  </div>

  <div class="card">
    <h3 style="margin-top: 0;">Products by Category</h3>
    <?php foreach ($products_by_category as $category => $cat_products): ?>
      <h4><?php echo htmlspecialchars($category); ?> Seeds (<?php echo count($cat_products); ?>)</h4>
      <div style="overflow-x: auto; margin-bottom: 32px;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: var(--panel);">
              <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">ID</th>
              <th style="padding: 12px; text-align: center; border-bottom: 2px solid var(--accent2);">Image</th>
              <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Name</th>
              <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Price</th>
              <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Qty</th>
              <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cat_products as $p): ?>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 12px;"><?=$p['id']?></td>
              <td style="padding: 8px;">
                <?php if ($p['image_url']): ?>
                  <img src="<?=htmlspecialchars($p['image_url'])?>" alt="Product image" style="width: 50px; height: 50px; object-fit: cover; border-radius: 4px;" onerror="this.style.display='none';">
                <?php else: ?>
                  <span>No image</span>
                <?php endif; ?>
              </td>
              <td style="padding: 12px;"><?php echo htmlspecialchars($p['name']); ?></td>
              <td style="padding: 12px;">₱<?=$p['price']?></td>
              <td style="padding: 12px;"><?=$p['qty']?></td>
              <td style="padding: 12px;">
                <a href="edit.php?id=<?=$p['id']?>" class="btn" style="padding: 6px 12px; font-size: 0.9rem; background: var(--accent); color: white; text-decoration: none; border-radius: 6px;">Edit</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    <?php endforeach; ?>
  </div>

  <div class="card">
    <h3 style="margin-top: 0;">Orders (Total: <?php echo count($orders); ?>)</h3>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
          <tr style="background: var(--panel);">
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Order ID</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Customer</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Total</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Status</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Payment Method</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Date</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($orders as $o): ?>
          <tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 12px;"><?=$o['id']?></td>
            <td style="padding: 12px;"><?php echo htmlspecialchars($o['username']); ?></td>
            <td style="padding: 12px;">₱<?=$o['total']?></td>
            <td style="padding: 12px;">
              <span style="padding: 4px 8px; border-radius: 4px; font-size: 0.9rem; 
                <?php if ($o['status'] == 'pending') echo 'background: #fff3cd; color: #856404;'; 
                      elseif ($o['status'] == 'confirmed') echo 'background: #d1ecf1; color: #0c5460;'; 
                      elseif ($o['status'] == 'delivered') echo 'background: #d4edda; color: #155724;'; 
                      else echo 'background: #f8d7da; color: #721c24;'; ?>">
                <?php echo ucfirst($o['status']); ?>
              </span>
            </td>
            <td style="padding: 12px;"><?php echo htmlspecialchars($o['payment_method'] ?? 'N/A'); ?></td>
            <td style="padding: 12px;"><?php echo date('M j, Y', strtotime($o['created_at'])); ?></td>
            <td style="padding: 12px;">
              <a href="admin_order.php?id=<?=$o['id']?>" class="btn" style="padding: 6px 12px; font-size: 0.9rem; background: var(--accent); color: white; text-decoration: none; border-radius: 6px;">View</a>
              <?php if ($o['status'] == 'pending'): ?>
                <a href="?confirm=<?=$o['id']?>" class="btn" style="padding: 6px 12px; font-size: 0.9rem; background: #28a745; color: white; text-decoration: none; border-radius: 6px; margin-left: 8px;">Confirm</a>
              <?php elseif ($o['status'] == 'confirmed'): ?>
                <a href="?deliver=<?=$o['id']?>" class="btn" style="padding: 6px 12px; font-size: 0.9rem; background: #007bff; color: white; text-decoration: none; border-radius: 6px; margin-left: 8px;">Deliver</a>
              <?php endif; ?>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
<a class="btn btn-secondary" href="admin.logout.php" style="position: fixed; bottom: 20px; right: 20px; z-index: 100; padding: 12px 24px;">Logout</a>
<?php include 'footer.php'; ?>
</body>
</html>
