# Seed Store Deployment

## Local (XAMPP)
- Start Apache/MySQL
- Access http://localhost:8080/1034/
- Admin: admin.login.php (no pass set)

## Production
1. PHP 8+ MySQL hosting (Hostinger, InfinityFree free).
2. Create DB 'seed_store', user w/ all privs.
3. Edit backend/.env:
   ```
   DB_HOST=localhost  # or provider host
   DB_NAME=seed_store
   DB_USER=youruser
   DB_PASS=yourpass
   ```
4. Upload all files to public_html/1034/ via FTP/cPanel.
5. Import DB schema (run SQL or create tables manually: products, customers, orders, order_items).
6. Visit yourdomain.com/1034/

## Tables SQL
```sql
CREATE TABLE products (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), category VARCHAR(100), price DECIMAL(10,2), qty INT, image_url TEXT, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE customers (id INT PRIMARY KEY AUTO_INCREMENT, name VARCHAR(255), email VARCHAR(255) UNIQUE, password VARCHAR(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE orders (id INT PRIMARY KEY AUTO_INCREMENT, user_id INT, total DECIMAL(10,2), status VARCHAR(50), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE order_items (id INT PRIMARY KEY AUTO_INCREMENT, order_id INT, product_id INT, qty INT);
```

## Security
- Change admin pass
- Use HTTPS
- .htaccess deny backend/
- Validate/sanitize inputs
