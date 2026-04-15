<?php
session_start();
if (!isset($_SESSION['admin'])) { 
    header('Location: admin.login.php'); 
    exit; 
}
require 'backend/db.php';
$products = $pdo->query("SELECT * FROM products")->fetchAll();
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
    <h3 style="margin-top: 0;">Products (Total: <?php echo count($products); ?>)</h3>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
<tr style="background: var(--panel);">
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">ID</th>
            <th style="padding: 12px; text-align: center; border-bottom: 2px solid var(--accent2);">Image</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Name</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Category</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Price</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Qty</th>
            <th style="padding: 12px; text-align: left; border-bottom: 2px solid var(--accent2);">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
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
            <td style="padding: 12px;"><?php echo htmlspecialchars($p['category']); ?></td>
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
  </div>
</main>
<a class="btn btn-secondary" href="admin.logout.php" style="position: fixed; bottom: 20px; right: 20px; z-index: 100; padding: 12px 24px;">Logout</a>
<?php include 'footer.php'; ?>
</body>
</html>
