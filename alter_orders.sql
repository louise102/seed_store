-- Add new columns to orders table for payment method and tracking
ALTER TABLE orders ADD COLUMN payment_method VARCHAR(50) DEFAULT 'Cash on Delivery';
ALTER TABLE orders ADD COLUMN tracking_number VARCHAR(20);
ALTER TABLE orders ADD COLUMN gcash_ref VARCHAR(100);