<?php
session_start();
require 'backend/db.php';
$products = $pdo->query("SELECT * FROM products ORDER BY category, name")->fetchAll();
$categories = [];
foreach ($products as $p) {
    $category = trim($p['category']);
    if ($category === '') {
        continue;
    }
    if (!isset($categories[$category])) {
        $categories[$category] = 0;
    }
    $categories[$category]++;
}
$categoryMeta = [
    'Vegetable' => ['label' => 'Vegetable Seeds', 'description' => 'Tomatoes, peppers, lettuce, and garden favorites', 'icon' => '🥬', 'color' => 'linear-gradient(135deg, #b4d186 0%, #4e8b31 100%)'],
    'Vegetables' => ['label' => 'Vegetable Seeds', 'description' => 'Tomatoes, peppers, lettuce, and garden favorites', 'icon' => '🥬', 'color' => 'linear-gradient(135deg, #b4d186 0%, #4e8b31 100%)'],
    'Flower' => ['label' => 'Flower Seeds', 'description' => 'Sunflowers, wildflowers, and beautiful blooms', 'icon' => '🌼', 'color' => 'linear-gradient(135deg, #f9c0c0 0%, #d972b5 100%)'],
    'Flowers' => ['label' => 'Flower Seeds', 'description' => 'Sunflowers, wildflowers, and beautiful blooms', 'icon' => '🌼', 'color' => 'linear-gradient(135deg, #f9c0c0 0%, #d972b5 100%)'],
    'Herb' => ['label' => 'Herb Seeds', 'description' => 'Culinary herbs like basil, lavender, and more', 'icon' => '🌿', 'color' => 'linear-gradient(135deg, #d3f2c7 0%, #5aa451 100%)'],
    'Herbs' => ['label' => 'Herb Seeds', 'description' => 'Culinary herbs like basil, lavender, and more', 'icon' => '🌿', 'color' => 'linear-gradient(135deg, #d3f2c7 0%, #5aa451 100%)'],
    'Grain' => ['label' => 'Grain Seeds', 'description' => 'Wheat, corn, and other grain varieties', 'icon' => '🌾', 'color' => 'linear-gradient(135deg, #f5d086 0%, #c78420 100%)'],
    'Grains' => ['label' => 'Grain Seeds', 'description' => 'Wheat, corn, and other grain varieties', 'icon' => '🌾', 'color' => 'linear-gradient(135deg, #f5d086 0%, #c78420 100%)'],
    'Fruit' => ['label' => 'Fruit Seeds', 'description' => 'Fresh fruit seeds for a productive home garden', 'icon' => '🍓', 'color' => 'linear-gradient(135deg, #ffb4b4 0%, #e05663 100%)'],
    'Fruits' => ['label' => 'Fruit Seeds', 'description' => 'Fresh fruit seeds for a productive home garden', 'icon' => '🍓', 'color' => 'linear-gradient(135deg, #ffb4b4 0%, #e05663 100%)'],
];
function getCategoryMeta($category, $meta) {
    foreach ($meta as $key => $values) {
        if (strcasecmp($category, $key) === 0) {
            return $values;
        }
    }
    return ['label' => $category . ' Seeds', 'description' => 'Explore our best seed varieties in this category.', 'icon' => '🌱', 'color' => 'linear-gradient(135deg, #c9f2dc 0%, #6ba96f 100%)'];
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Categories - Seed Store</title>
  <link rel="stylesheet" href="style.css">
  <script src="script.js" defer></script>
</head>
<body>
<?php include 'header.php'; ?>
<main class="container">
  <section class="card category-hero">
    <div>
      <h1>Browse by Category</h1>
      <p>Find the perfect seeds for your garden, from vegetables to flowers.</p>
    </div>
  </section>

  <?php if ($categories): ?>
    <section class="category-grid">
      <?php foreach ($categories as $category => $count): ?>
        <?php $meta = getCategoryMeta($category, $categoryMeta); ?>
        <a class="category-card" style="background: <?php echo $meta['color']; ?>" href="product.php?category=<?php echo urlencode($category); ?>">
          <div class="category-card-top">
            <div class="category-card-icon"><?php echo $meta['icon']; ?></div>
            <div class="category-card-meta">
              <span class="category-card-label"><?php echo htmlspecialchars($meta['label']); ?></span>
              <span class="category-card-count"><?php echo $count; ?> items</span>
            </div>
          </div>
          <p class="category-card-description"><?php echo htmlspecialchars($meta['description']); ?></p>
        </a>
      <?php endforeach; ?>
    </section>
  <?php else: ?>
    <section class="card" style="text-align:center;">
      <p>No categories available yet.</p>
    </section>
  <?php endif; ?>
</main>
<?php include 'footer.php'; ?>
</body>
</html>