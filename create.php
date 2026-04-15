<?php
require 'backend/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $category = trim($_POST['category']);
    $price = floatval($_POST['price']);
    $qty = intval($_POST['qty']);
    if ($name !== '' && $category !== '') {
        $stmt = $pdo->prepare("INSERT INTO products (name, category, price, qty) VALUES (?, ?, ?, ?)");
        $stmt->execute([$name, $category, $price, $qty]);
        header("Location: list.php"); exit;
    }
}
?>
<!DOCTYPE html>
<html>
<head><title>Create Product</title></head>
<body>
<h1>Create Product</h1>
<form method="post">
  Name: <input name="name" required><br>
  Category: <input name="category" required><br>
  Price: <input name="price" type="number" step="0.01" required><br>
  Qty: <input name="qty" type="number" required><br>
  <button type="submit">Save</button>
</form>
<a href="list.php">View Products</a>
</body>
</html>