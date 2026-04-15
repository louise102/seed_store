<?php
require 'backend/db.php';
$customers = $pdo->query("SELECT * FROM customers")->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Customers</title></head>
<body>
<?php include 'header.php'; ?>
<h1>Customers</h1>
<table border="1">
<tr><th>ID</th><th>Name</th><th>Email</th></tr>
<?php foreach ($customers as $c): ?>
<tr><td><?=$c['id']?></td><td><?=$c['name']?></td><td><?=$c['email']?></td></tr>
<?php endforeach; ?>
</table>
<?php include 'footer.php'; ?>
</body>
</html>