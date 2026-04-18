<header class="site-header">
  <div class="container">
    <h1>Seed Store</h1>
    <nav class="site-nav">
      <a href="index.php">Home</a>
      <a href="product.php">Products</a>
      <a href="categories.php">Categories</a>
      <a href="aboutus.php">About</a>
      <a href="contact.php">Contact</a>
      <a class="btn btn-primary" href="cart.php">Cart</a>
      <form action="product.php" method="get" style="display: inline; margin-left: 12px;">
        <input type="text" name="search" placeholder="Search products..." style="padding: 0.5rem; border: 1px solid #ccc; border-radius: 0.5rem; width: 150px;">
        <button type="submit" class="btn btn-secondary" style="margin-left: 4px; padding: 0.5rem 1rem;">Search</button>
      </form>
      <?php
      if (session_status() === PHP_SESSION_NONE) {
          session_start();
      }
      if (isset($_SESSION['admin'])): ?>
        <a href="dashboard.php" style="background: var(--accent2); color: white; padding: 0.5rem 1rem; border-radius: 0.5rem; font-weight: 700; text-decoration: none; margin-left: 12px;">Admin Dashboard</a>
        <a href="admin.logout.php">Admin Logout</a>
      <?php elseif (isset($_SESSION['user'])): ?>
        <a href="profile.php">Profile</a>
        <a href="logout.php">Logout</a>
      <?php else: ?>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
