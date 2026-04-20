<?php
session_start();
require 'backend/db.php';

if (!isset($_SESSION['admin'])) {
    header('Location: admin.login.php');
    exit;
}

if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $pdo->prepare("DELETE FROM products WHERE id = ?")->execute([$id]);
    header("Location: list.php"); 
    exit;
}

$products = $pdo->query("SELECT * FROM products ORDER BY created_at DESC")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Product List - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <div class="card">
    <h1>Product Management</h1>
    <a class="btn btn-primary" href="addproduct.php" style="margin-bottom: 20px; display: inline-block;">+ Add New Product</a>
  </div>
  
  <div class="card">
    <h3>All Products (?<?php echo count($products); ?>)</h3>
    <div style="overflow-x: auto;">
      <table style="width: 100%; border-collapse: collapse;">
        <thead>
<tr style="background: var(--panel);">
            <th style="padding: 12px; text-align: left;">ID</th>
            <th style="padding: 12px; text-align: center;">Image</th>
            <th style="padding: 12px; text-align: left;">Name</th>
            <th style="padding: 12px; text-align: left;">Category</th>
            <th style="padding: 12px; text-align: right;">Price</th>
            <th style="padding: 12px; text-align: right;">Qty</th>
            <th style="padding: 12px; text-align: center;">Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($products as $p): ?>
<tr style="border-bottom: 1px solid #eee;">
            <td style="padding: 12px;"><?=$p['id']?></td>
            <td style="padding: 12px; text-align: center;">
              <?php if (!empty($p['image_url'])): ?>
                <img src="<?=htmlspecialchars($p['image_url'])?>" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:4px;" onerror="this.style.display='none';">
              <?php endif; ?>
            </td>
            <td style="padding: 12px;"><?php echo htmlspecialchars($p['name']);?></td>
            <td style="padding: 12px;"><?php echo htmlspecialchars($p['category']);?></td>
            <td style="padding: 12px; text-align: right;">₱<?php echo number_format($p['price'], 2);?></td>
            <td style="padding: 12px; text-align: right;"><?=$p['qty']?></td>
            <td style="padding: 12px; text-align: center;">
              <a href="edit.php?id=<?=$p['id']?>" class="btn" style="padding: 6px 12px; margin-right: 8px; background: var(--accent); color: white; text-decoration: none; border-radius: 4px;">Edit</a>
              <a href="?delete=<?=$p['id']?>" class="btn" style="padding: 6px 12px; background: #dc3545; color: white; text-decoration: none; border-radius: 4px;" 
                 onclick="return confirm('Delete this product?')">Delete</a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
