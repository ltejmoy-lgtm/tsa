-- ==========================================================
-- TSA SHOP - Database Schema & Seed Data
-- Database Name: tsa_shop
-- ==========================================================

CREATE DATABASE IF NOT EXISTS `tsa_shop` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `tsa_shop`;

-- --------------------------------------------------------
-- Table: users
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(150) NOT NULL UNIQUE,
  `phone` VARCHAR(20) DEFAULT NULL,
  `password` VARCHAR(255) NOT NULL,
  `role` VARCHAR(30) NOT NULL DEFAULT 'customer',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: categories
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `categories` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL UNIQUE,
  `description` VARCHAR(255) DEFAULT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Table: products
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `products` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `category_id` INT DEFAULT NULL,
  `name` VARCHAR(255) NOT NULL,
  `description` TEXT DEFAULT NULL,
  `brand` VARCHAR(100) DEFAULT 'TSA',
  `price` DECIMAL(10,2) NOT NULL,
  `mrp` DECIMAL(10,2) NOT NULL,
  `discount` INT DEFAULT 0,
  `stock` INT NOT NULL DEFAULT 50,
  `image` VARCHAR(500) DEFAULT NULL,
  `rating` DECIMAL(2,1) DEFAULT 4.2,
  `status` ENUM('active', 'inactive') DEFAULT 'active',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`category_id`) REFERENCES `categories`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Initial Categories
-- --------------------------------------------------------
INSERT INTO `categories` (`id`, `name`, `slug`, `image`) VALUES
(1, 'Mobiles', 'mobiles', 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=500&q=80'),
(2, 'Electronics', 'electronics', 'https://images.unsplash.com/photo-1498049794561-7780e7231661?auto=format&fit=crop&w=500&q=80'),
(3, 'Fashion', 'fashion', 'https://images.unsplash.com/photo-1445205170230-053b83016050?auto=format&fit=crop&w=500&q=80'),
(4, 'Footwear', 'footwear', 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=500&q=80'),
(5, 'Home', 'home', 'https://images.unsplash.com/photo-1484101403633-562f891dc89a?auto=format&fit=crop&w=500&q=80'),
(6, 'Beauty', 'beauty', 'https://images.unsplash.com/photo-1596462502278-27bfdc403348?auto=format&fit=crop&w=500&q=80'),
(7, 'Appliances', 'appliances', 'https://images.unsplash.com/photo-1588854337236-6889d631faa8?auto=format&fit=crop&w=500&q=80'),
(8, 'Grocery', 'grocery', 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=500&q=80'),
(9, 'Sports', 'sports', 'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?auto=format&fit=crop&w=500&q=80')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);

-- --------------------------------------------------------
-- Tables: addresses, orders and order_items
-- --------------------------------------------------------
CREATE TABLE IF NOT EXISTS `addresses` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `full_name` VARCHAR(100) NOT NULL,
  `phone` VARCHAR(20) NOT NULL,
  `house_no` VARCHAR(150) DEFAULT NULL,
  `street` VARCHAR(255) DEFAULT NULL,
  `city` VARCHAR(100) NOT NULL,
  `state` VARCHAR(100) NOT NULL,
  `pincode` VARCHAR(10) NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `user_id` INT DEFAULT NULL,
  `address_id` INT NOT NULL,
  `order_number` VARCHAR(40) NOT NULL UNIQUE,
  `subtotal` DECIMAL(10,2) NOT NULL,
  `delivery_charge` DECIMAL(10,2) NOT NULL DEFAULT 0,
  `total_amount` DECIMAL(10,2) NOT NULL,
  `payment_method` VARCHAR(50) NOT NULL,
  `payment_status` VARCHAR(30) NOT NULL DEFAULT 'pending',
  `order_status` VARCHAR(30) NOT NULL DEFAULT 'confirmed',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
  FOREIGN KEY (`address_id`) REFERENCES `addresses`(`id`) ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS `order_items` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `order_id` INT NOT NULL,
  `product_id` INT DEFAULT NULL,
  `product_name` VARCHAR(255) NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `quantity` INT NOT NULL,
  `subtotal` DECIMAL(10,2) NOT NULL,
  FOREIGN KEY (`order_id`) REFERENCES `orders`(`id`) ON DELETE CASCADE,
  FOREIGN KEY (`product_id`) REFERENCES `products`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------
-- Initial Products
-- --------------------------------------------------------
INSERT INTO `products` (`category_id`, `name`, `description`, `brand`, `price`, `mrp`, `discount`, `stock`, `image`, `rating`, `status`) VALUES
(1, '5G Smartphone (8GB RAM, 128GB Storage)', 'Next-gen 5G processing, 6.7-inch AMOLED 120Hz display, 5000mAh battery with 67W Turbo charging and 64MP AI triple camera.', 'Apex', 18999.00, 24999.00, 24, 45, 'https://images.unsplash.com/photo-1511707171634-5f897ff02aa9?auto=format&fit=crop&w=600&q=80', 4.6, 'active'),
(1, 'Ultra Flagship Smartphone (12GB RAM, 256GB)', 'Premium curved display, 108MP OIS camera, cinematic night mode, 5000mAh battery and fast wireless charging support.', 'Zenith', 34999.00, 44999.00, 22, 30, 'https://images.unsplash.com/photo-1592899677977-9c10ca588bbd?auto=format&fit=crop&w=600&q=80', 4.8, 'active'),
(2, 'Active Noise Cancelling Wireless Headphones', '40mm titanium drivers, 40 hours battery life, plush memory foam ear cushions, multipoint Bluetooth 5.3 connection.', 'SonicPro', 3499.00, 7999.00, 56, 60, 'https://images.unsplash.com/photo-1505740420928-5e560c06d30e?auto=format&fit=crop&w=600&q=80', 4.7, 'active'),
(2, 'True Wireless Earbuds with Dual Mic ANC', '36-hour total playback with sleek charging case, IPX5 sweat resistance, low latency gaming mode and deep bass profile.', 'EchoBeats', 1699.00, 3999.00, 57, 85, 'https://images.unsplash.com/photo-1590658268037-6bf12165a8df?auto=format&fit=crop&w=600&q=80', 4.4, 'active'),
(2, '1.4-inch AMOLED Smartwatch with Bluetooth Calling', 'Always-On display, continuous heart rate and SpO2 tracker, 100+ sports modes, 7-day battery life, zinc alloy metallic frame.', 'Kronos', 2299.00, 5499.00, 58, 70, 'https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=600&q=80', 4.5, 'active'),
(3, 'Men Slim Fit Oxford Cotton Casual Shirt', '100% breathable combed cotton, button-down collar, curved hemline, versatile for work or weekend styling.', 'UrbanThread', 899.00, 1999.00, 55, 120, 'https://images.unsplash.com/photo-1602810318383-e386cc2a3ccf?auto=format&fit=crop&w=600&q=80', 4.3, 'active'),
(3, 'Women Embroidered Floral Anarkali Kurta', 'Graceful rayon blend fabric, fine zari embroidery detailing, flared silhouette, paired with matching dupatta.', 'VogueVedic', 1299.00, 2899.00, 55, 90, 'https://images.unsplash.com/photo-1583391733956-3750e0ff4e8b?auto=format&fit=crop&w=600&q=80', 4.6, 'active'),
(4, 'Men Lightweight Cushion Running Shoes', 'Breathable knit mesh upper, shock-absorbing responsive EVA sole, high traction anti-slip grip for running and training.', 'AeroStride', 1499.00, 3299.00, 54, 80, 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?auto=format&fit=crop&w=600&q=80', 4.4, 'active'),
(5, 'Modern Minimalist Wooden Bedside Table', 'Engineered solid wood with water-resistant walnut finish, smooth slide drawer and open bottom storage compartment.', 'HearthCraft', 2199.00, 4500.00, 51, 35, 'https://images.unsplash.com/photo-1532372320572-cda25653a26d?auto=format&fit=crop&w=600&q=80', 4.5, 'active'),
(5, 'Ultra Soft 100% Microfiber Queen Bed Sheet', '300 TC breathable, wrinkle-resistant microfiber bedsheet with two matching envelope pillowcases.', 'ComfortNest', 699.00, 1599.00, 56, 110, 'https://images.unsplash.com/photo-1584100936595-c0654b55a2e2?auto=format&fit=crop&w=600&q=80', 4.3, 'active'),
(6, 'Vitamin C + Hyaluronic Acid Brightening Serum', 'Dermatologist tested, 10% pure active Vitamin C, boosts natural radiance, reduces spots and deeply hydrates skin.', 'AuraGlow', 549.00, 1199.00, 54, 150, 'https://images.unsplash.com/photo-1620916566398-39f1143ab7be?auto=format&fit=crop&w=600&q=80', 4.7, 'active'),
(7, '750W 3-Jar Stainless Steel Mixer Grinder', 'Heavy-duty copper motor, 3 multi-purpose stainless steel jars with flow breakers, overload protection switch.', 'KitchenStar', 2499.00, 4999.00, 50, 40, 'https://images.unsplash.com/photo-1588854337236-6889d631faa8?auto=format&fit=crop&w=600&q=80', 4.5, 'active'),
(8, 'Organic Cold Pressed Virgin Coconut Oil (1L)', '100% pure and unrefined, extracted from fresh coconuts, ideal for healthy cooking, baking and skin nourishment.', 'NaturePure', 429.00, 650.00, 34, 100, 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&w=600&q=80', 4.6, 'active'),
(9, 'High-Density Anti-Tear Yoga Mat (6mm)', 'Eco-friendly TPE material with textured non-slip grip, alignment marks, carry strap included for home and gym yoga.', 'FitPulse', 799.00, 1799.00, 55, 65, 'https://images.unsplash.com/photo-1517649763962-0c623266ddc0?auto=format&fit=crop&w=600&q=80', 4.4, 'active');

-- Sample Demo User (password: Password123)
INSERT INTO `users` (`name`, `email`, `phone`, `password`, `role`) VALUES
('TSA Demo User', 'demo@tsashop.local', '9876543210', '$2y$10$i2YdIqZ7J5p4kK7s1Lq7IeRjB/8tQ3Z0yv5uA9vE6tB9rO9a1pU1e', 'customer')
ON DUPLICATE KEY UPDATE `name` = VALUES(`name`);
