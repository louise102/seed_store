# Moving db.php to backend/db.php

## Steps:
- [x] Create backend/db.php with db content
- [x] Update require statements in all dependent files (create.php, customer.php, dashboard.php, list.php, my-orders.php, checkout.php, order.php, product.php, login.php, register.php, addproduct.php, edit.php, cart.php, order-confirmation.php)
- [x] Remove original db.php
- [ ] Test application pages (e.g., open list.php in browser)

## Database Update for Payment Methods
- [ ] Run the ALTER TABLE statements in alter_orders.sql in your database (phpMyAdmin or SQL console) to add payment_method, tracking_number, and gcash_number columns to the orders table.

