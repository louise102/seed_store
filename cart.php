<?php
session_start();
require 'backend/db.php';
// Basic cart logic, assuming cart in session
if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
if (isset($_GET['add'])) {
    if (!isset($_SESSION['user'])) {
        header('Location: login.php?error=' . urlencode('Please log in or register first to add items to the cart.'));
        exit;
    }
    $id = (int)$_GET['add'];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + 1;
    header('Location: product.php?added=1');
    exit;
}
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    unset($_SESSION['cart'][$id]);
}
$cart_items = [];
$total = 0;
if ($_SESSION['cart']) {
    $ids = array_keys($_SESSION['cart']);
    $placeholders = str_repeat('?,', count($ids) - 1) . '?';
    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
    $stmt->execute($ids);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach ($products as $p) {
        $item_total = $p['price'] * $_SESSION['cart'][$p['id']];
        $total += $item_total;
        $cart_items[] = $p + ['qty' => $_SESSION['cart'][$p['id']], 'item_total' => $item_total];
    }
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Shopping Cart - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card">
    <h1>Your Shopping Cart</h1>
    <p class="auth-subtitle">Review your selected seeds</p>
    
    <?php if ($cart_items): ?>
      <div style="overflow-x: auto; margin-top: 20px;">
        <table style="width: 100%; border-collapse: collapse;">
          <thead>
            <tr style="background: var(--panel);">
              <th style="padding: 16px 12px; text-align: left; border-bottom: 2px solid var(--accent2); font-weight: 700;">Product</th>
              <th style="padding: 16px 12px; text-align: center; border-bottom: 2px solid var(--accent2); font-weight: 700;">Price</th>
              <th style="padding: 16px 12px; text-align: center; border-bottom: 2px solid var(--accent2); font-weight: 700;>Quantity</th>
              <th style="padding: 16px 12px; text-align: center; border-bottom: 2px solid var(--accent2); font-weight: 700;">Total</th>
              <th style="padding: 16px 12px; text-align: center; border-bottom: 2px solid var(--accent2); font-weight: 700;">Action</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($cart_items as $item): ?>
            <tr style="border-bottom: 1px solid #eee;">
              <td style="padding: 16px 12px;"><?php echo htmlspecialchars($item['name']); ?></td>
              <td style="padding: 16px 12px; text-align: center;">₱<?=$item['price']?></td>
              <td style="padding: 16px 12px; text-align: center; font-weight: 600; color: var(--accent2);">₱<?=$item['item_total']?></td>
              <td style="padding: 16px 12px; text-align: center;">
                <a href="?remove=<?=$item['id']?>" class="btn" style="padding: 8px 16px; background: #dc3545; color: white; text-decoration: none; border-radius: 6px; font-size: 0.9rem;">Remove</a>
              </td>
            </tr>
            <?php endforeach; ?>
          </tbody>
          <tfoot>
            <tr style="background: var(--accent); color: white; font-weight: 700;">
              <td colspan="3" style="padding: 16px;"><strong>Grand Total</strong></td>
              <td style="padding: 16px; text-align: center;"><strong>₱<?=$total?></strong></td>
              <td style="padding: 16px; text-align: center;">
                <a href="checkout.php" class="btn" style="background: var(--card); color: var(--accent); border: 1px solid var(--accent); padding: 12px 24px; text-decoration: none; border-radius: 8px; font-weight: 700;">Proceed to Checkout</a>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    <?php else: ?>
      <div style="text-align: center; padding: 60px 20px; color: var(--muted);">
        <h2>Your cart is empty</h2>
        <p>Explore our <a href="product.php">products</a> to get started.</p>
      </div>
    <?php endif; ?>
  </section>
</main>
<?php include 'footer.php'; ?>
</body>
</html>
