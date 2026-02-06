

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
-- Users Table
CREATE TABLE users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  username VARCHAR(50) NOT NULL UNIQUE,
  email VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('customer', 'admin') DEFAULT 'customer',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Products Table
CREATE TABLE products (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(100) NOT NULL,
  description TEXT,
  price DECIMAL(10,2) NOT NULL,
  category VARCHAR(50),
  image VARCHAR(255),
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO products (name, category, price, description, image) VALUES
-- WOMEN
('High-Waist Wide Leg Trousers', 'women', 1499.00, 'Elegant wide-leg trousers with a high-rise fit.', 'trousers.jpg'),
('Structured Blazer', 'women', 2299.00, 'Tailored camel blazer with notched lapel.', 'blazer.jpg'),
('Satin Cowl Neck Top', 'women', 1299.00, 'Champagne satin top with draped neckline.', 'cowl_top.jpg'),
('Pleated Maxi Skirt', 'women', 1599.00, 'Flowy pleated skirt with ankle-length hem.', 'maxi_skirt.jpg'),
('Cropped Knit Sweater', 'women', 1399.00, 'Soft cropped sweater with ribbed texture.', 'knit_sweater.jpg'),
('Classic White Shirt', 'women', 1199.00, 'Crisp white button-down with tailored fit.', 'white_shirt.jpg'),
('Faux Leather Pencil Skirt', 'women', 1499.00, 'Sleek pencil skirt in faux leather finish.', 'leather_skirt.jpg'),
('Linen Belted Jumpsuit', 'women', 1899.00, 'Breathable linen jumpsuit with waist belt.', 'jumpsuit.jpg'),
('Ribbed Tank Bodysuit', 'women', 999.00, 'Stretchy ribbed bodysuit with scoop neck.', 'bodysuit.jpg'),
('Oversized Denim Jacket', 'women', 1799.00, 'Relaxed-fit denim jacket with dropped shoulders.', 'denim_jacket.jpg'),
('Floral Wrap Blouse', 'women', 1299.00, 'Lightweight blouse with floral print and wrap front.', 'wrap_blouse.jpg'),
('Wool Blend Coat', 'women', 2499.00, 'Long wool coat with structured silhouette.', 'wool_coat.jpg'),
('Tiered Ruffle Dress', 'women', 1699.00, 'Romantic dress with layered ruffles.', 'ruffle_dress.jpg'),
('Minimalist Slide Sandals', 'women', 899.00, 'Simple slide sandals with cushioned sole.', 'slide_sandals.jpg'),

-- MEN
('Slim Fit Oxford Shirt', 'men', 1299.00, 'Tailored oxford shirt with button-down collar.', 'oxford_shirt.jpg'),
('Tapered Chino Pants', 'men', 1399.00, 'Stretch chinos with tapered leg.', 'chino_pants.jpg'),
('Wool Blend Overcoat', 'men', 2599.00, 'Classic overcoat in wool blend fabric.', 'overcoat.jpg'),
('Crew Neck Cashmere Sweater', 'men', 1999.00, 'Soft cashmere sweater with crew neckline.', 'cashmere_sweater.jpg'),
('Leather Chelsea Boots', 'men', 2999.00, 'Polished leather boots with elastic sides.', 'chelsea_boots.jpg'),
('Tailored Suit Jacket', 'men', 3499.00, 'Formal suit jacket with structured shoulders.', 'suit_jacket.jpg'),
('Cotton Henley Shirt', 'men', 1099.00, 'Casual henley shirt with button placket.', 'henley_shirt.jpg'),
('Slim Fit Jeans', 'men', 1499.00, 'Dark wash jeans with slim silhouette.', 'slim_jeans.jpg'),
('Linen Short Sleeve Shirt', 'men', 1199.00, 'Breathable linen shirt for warm weather.', 'linen_shirt.jpg'),
('Puffer Vest', 'men', 1599.00, 'Lightweight vest with quilted padding.', 'puffer_vest.jpg'),
('Athletic Joggers', 'men', 1299.00, 'Sporty joggers with tapered ankle.', 'joggers.jpg'),
('Suede Loafers', 'men', 1899.00, 'Slip-on loafers in soft suede.', 'loafers.jpg'),
('Graphic Tee', 'men', 899.00, 'Cotton tee with bold graphic print.', 'graphic_tee.jpg'),
('Bomber Jacket', 'men', 1799.00, 'Classic bomber with ribbed cuffs.', 'bomber_jacket.jpg'),
('Leather Belt', 'men', 799.00, 'Genuine leather belt with metal buckle.', 'leather_belt.jpg'),

-- KIDS
('Cotton Polo Shirt', 'kids', 699.00, 'Soft cotton polo with contrast collar.', 'polo_kids.jpg'),
('Denim Overalls', 'kids', 999.00, 'Classic overalls with adjustable straps.', 'overalls.jpg'),
('Printed Hoodie', 'kids', 899.00, 'Hoodie with playful print and kangaroo pocket.', 'hoodie_kids.jpg'),
('Canvas Sneakers', 'kids', 799.00, 'Durable sneakers with rubber sole.', 'sneakers_kids.jpg'),
('Striped T-Shirt', 'kids', 599.00, 'Striped tee in breathable cotton.', 'striped_tee.jpg'),
('Cargo Shorts', 'kids', 699.00, 'Utility shorts with side pockets.', 'cargo_shorts.jpg'),
('Rain Jacket', 'kids', 999.00, 'Waterproof jacket with hood.', 'rain_jacket.jpg'),
('Jogger Pants', 'kids', 799.00, 'Comfy joggers with elastic waistband.', 'jogger_kids.jpg'),
('Graphic Pajama Set', 'kids', 899.00, 'Two-piece pajama set with fun prints.', 'pajama_set.jpg'),
('Velcro Sandals', 'kids', 699.00, 'Easy-on sandals with velcro straps.', 'sandals_kids.jpg'),
('Knitted Beanie', 'kids', 499.00, 'Warm beanie with ribbed texture.', 'beanie.jpg'),
('Party Dress', 'kids', 1099.00, 'Festive dress with tulle skirt.', 'party_dress.jpg'),
('Basic Leggings', 'kids', 599.00, 'Stretch leggings for everyday wear.', 'leggings.jpg'),
('Denim Jacket', 'kids', 999.00, 'Mini denim jacket with snap buttons.', 'denim_kids.jpg'),
('Rain Boots', 'kids', 799.00, 'Colorful boots for rainy days.', 'rain_boots.jpg'),

-- SPORTS
('Performance T-Shirt', 'sports', 899.00, 'Moisture-wicking tee for workouts.', 'performance_tee.jpg'),
('Running Shorts', 'sports', 799.00, 'Lightweight shorts with inner lining.', 'running_shorts.jpg'),
('Training Hoodie', 'sports', 1299.00, 'Pullover hoodie with breathable panels.', 'training_hoodie.jpg'),
('Compression Leggings', 'sports', 999.00, 'Supportive leggings for training.', 'compression_leggings.jpg'),
('Gym Duffel Bag', 'sports', 1499.00, 'Spacious duffel with multiple compartments.', 'duffel_bag.jpg'),
('Athletic Socks (3-pack)', 'sports', 499.00, 'Pack of 3 cushioned socks.', 'socks_pack.jpg'),
('Sports Bra', 'sports', 999.00, 'Supportive bra with racerback design.', 'sports_bra.jpg'),
('Track Jacket', 'sports', 1299.00, 'Zip-up jacket with mesh lining.', 'track_jacket.jpg'),
('Yoga Mat', 'sports', 899.00, 'Non-slip mat for yoga and stretching.', 'yoga_mat.jpg'),
('Training Tank Top', 'sports', 799.00, 'Sleeveless top for high-intensity workouts.', 'tank_top.jpg'),
('Cross Trainers', 'sports', 1999.00, 'Versatile shoes for gym and outdoor.', 'cross_trainers.jpg'),
('Sweatband Set', 'sports', 399.00, 'Headband and wristbands combo.', 'sweatband_set.jpg'),
('Cycling Shorts', 'sports', 999.00, 'Padded shorts for long rides.', 'cycling_shorts.jpg'),
('Jump Rope', 'sports', 299.00, 'Speed rope with adjustable length.', 'jump_rope.jpg'),
('Hydration Bottle', 'sports', 499.00, 'Durable sports bottle with flip-top lid.', 'hydration_bottle.jpg');
-- Orders Table
CREATE TABLE orders (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  product_id INT,
  quantity INT DEFAULT 1,
  final_total DECIMAL(10,2),
  order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

-- Deliveries Table
CREATE TABLE deliveries (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  delivery_status ENUM('pending', 'shipped', 'delivered', 'cancelled') DEFAULT 'pending',
  delivery_date TIMESTAMP NULL DEFAULT NULL,
  tracking_number VARCHAR(100),
  courier VARCHAR(100),
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id)
);


CREATE TABLE cart_items (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  quantity INT DEFAULT 1,
  added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE payments (
  id INT AUTO_INCREMENT PRIMARY KEY,
  order_id INT NOT NULL,
  payment_method ENUM('cod', 'paypal', 'gcash', 'paymaya') NOT NULL,
  amount DECIMAL(10,2) NOT NULL,
  payment_status ENUM('pending', 'completed', 'failed', 'refunded') DEFAULT 'pending',
  paid_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (order_id) REFERENCES orders(id)
);

CREATE TABLE coupons (
  id INT AUTO_INCREMENT PRIMARY KEY,
  code VARCHAR(50) NOT NULL UNIQUE,
  discount_type ENUM('percentage', 'fixed') NOT NULL,
  discount_value DECIMAL(10,2) NOT NULL,
  expiry_date DATE,
  usage_limit INT DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE inventory (
  id INT AUTO_INCREMENT PRIMARY KEY,
  product_id INT NOT NULL,
  stock_quantity INT DEFAULT 0,
  last_updated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  product_id INT NOT NULL,
  rating INT CHECK (rating BETWEEN 1 AND 5),
  comment TEXT,
  review_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id),
  FOREIGN KEY (product_id) REFERENCES products(id)
);

CREATE TABLE contact_messages (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT,
  name VARCHAR(100),
  email VARCHAR(100),
  subject VARCHAR(150),
  message TEXT,
  sent_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE VIEW sales_summary AS
SELECT 
  p.name AS product_name,
  SUM(o.quantity) AS total_sold,
  SUM(o.quantity * p.price) AS total_revenue
FROM orders o
JOIN products p ON o.product_id = p.id
GROUP BY p.id;
