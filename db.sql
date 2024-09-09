-- -- Create tables

-- CREATE TABLE cities (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL
-- );

-- CREATE TABLE districts (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     city_id BIGINT UNSIGNED,
--     name VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL,
--     FOREIGN KEY (city_id) REFERENCES cities(id)
-- );

-- CREATE TABLE accounts (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     phone_number VARCHAR(20) NOT NULL UNIQUE,
--     name VARCHAR(255) NOT NULL,
--     email VARCHAR(255) NOT NULL UNIQUE,
--     city_id BIGINT UNSIGNED,
--     district_id BIGINT UNSIGNED,
--     address TEXT NOT NULL,
--     photo_url VARCHAR(255),
--     activated BOOLEAN NOT NULL,
--     role ENUM('user', 'admin') NOT NULL,
--     reference_code VARCHAR(50),
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL,
--     FOREIGN KEY (city_id) REFERENCES cities(id),
--     FOREIGN KEY (district_id) REFERENCES districts(id)
-- );

-- CREATE TABLE otps (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     phone_number VARCHAR(20) NOT NULL,
--     sms_code VARCHAR(10) NOT NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL
-- );

-- CREATE TABLE carts (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     account_id BIGINT UNSIGNED,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL,
--     FOREIGN KEY (account_id) REFERENCES accounts(id)
-- );

-- CREATE TABLE cart_addresses (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     cart_id BIGINT UNSIGNED,
--     address TEXT NOT NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL,
--     FOREIGN KEY (cart_id) REFERENCES carts(id)
-- );

-- CREATE TABLE cart_dates (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     cart_id BIGINT UNSIGNED,
--     date DATE NOT NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL,
--     FOREIGN KEY (cart_id) REFERENCES carts(id)
-- );

-- CREATE TABLE categories (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     name VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL
-- );

-- CREATE TABLE image_paths (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     url VARCHAR(255) NOT NULL,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL
-- );

-- CREATE TABLE likes (
--     id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
--     account_id BIGINT UNSIGNED,
--     category_id BIGINT UNSIGNED,
--     created_at TIMESTAMP NULL,
--     updated_at TIMESTAMP NULL,
--     FOREIGN KEY (account_id) REFERENCES accounts(id),
--     FOREIGN KEY (category_id) REFERENCES categories(id)
-- );

-- Insert dummy data

-- Cities
INSERT INTO cities (id, name, plateCode, created_at, updated_at) VALUES
(1, 'Istanbul', '34', NOW(), NOW()),
(2, 'Ankara', '06', NOW(), NOW()),
(3, 'Izmir', '35', NOW(), NOW()),
(4, 'Bursa', '16', NOW(), NOW()),
(5, 'Antalya', '07', NOW(), NOW()),
(6, 'Adana', '01', NOW(), NOW()),
(7, 'Konya', '42', NOW(), NOW()),
(8, 'Gaziantep', '27', NOW(), NOW()),
(9, 'Mersin', '33', NOW(), NOW()),
(10, 'Samsun', '55', NOW(), NOW());


-- Districts
INSERT INTO districts (id, name, postCode, city_id, created_at, updated_at) VALUES
(1, 'Greenwood', '10001', 1, NOW(), NOW()),
(2, 'Riverside', '20002', 2, NOW(), NOW()),
(3, 'Lakeside', '30003', 3, NOW(), NOW()),
(4, 'Hilltop', '40004', 4, NOW(), NOW()),
(5, 'Sunnyvale', '50005', 5, NOW(), NOW()),
(6, 'Maple Town', '60006', 6, NOW(), NOW()),
(7, 'Pine Valley', '70007', 7, NOW(), NOW()),
(8, 'Oceanview', '80008', 8, NOW(), NOW()),
(9, 'Woodland', '90009', 9, NOW(), NOW()),
(10, 'Rocky Ridge', '10010', 10, NOW(), NOW());


-- Accounts
INSERT INTO accounts (phoneNumber, name, email, city_id, district_id, address, photoURL, activated, role, referenceCode, created_at, updated_at) VALUES
('00905323627760', 'John Doe', 'john.doe@example.com', 1, 1, '123 Main Street', 'http://example.com/photo.jpg', TRUE, 'admin', 'REF123', NOW(), NOW()),
('00905323627761', 'Jane Smith', 'jane.smith@example.com', 2, 3, '456 Elm Street', 'http://example.com/photo2.jpg', TRUE, 'user', 'REF456', NOW(), NOW());

-- OTPS
INSERT INTO otps (phone_number, sms_code, created_at, updated_at) VALUES
('00905323627760', '123456', NOW(), NOW()),
('00905323627761', '654321', NOW(), NOW());

-- Carts
INSERT INTO carts (id, position, title, description, phone, map_lat, map_long, discount, menu, brand_like, `like`, created_at, updated_at) VALUES
(1, 1, 'Best Coffee Shop', 'A cozy coffee shop with a variety of drinks.', '123-456-7890', 40.7128, -74.0060, 10, 'Coffee, Tea, Snacks', 120, 50, NOW(), NOW()),
(2, 2, 'Gadget Store', 'Electronics and gadgets at great prices.', '234-567-8901', 34.0522, -118.2437, 15, 'Laptops, Phones, Accessories', 95, 40, NOW(), NOW()),
(3, 3, 'Italian Restaurant', 'Authentic Italian food with a modern twist.', '345-678-9012', 41.9028, 12.4964, 20, 'Pasta, Pizza, Salads', 150, 75, NOW(), NOW()),
(4, 4, 'Fitness Center', 'A modern gym with all the latest equipment.', '456-789-0123', 51.5074, -0.1278, 5, 'Gym, Classes, Sauna', 80, 35, NOW(), NOW()),
(5, 5, 'Bookstore', 'Wide collection of books and stationery.', '567-890-1234', 48.8566, 2.3522, 25, 'Books, Magazines, Stationery', 60, 25, NOW(), NOW()),
(6, 6, 'Fashion Boutique', 'Trendy clothing for all seasons.', '678-901-2345', 35.6895, 139.6917, 30, 'Dresses, Accessories, Shoes', 110, 55, NOW(), NOW()),
(7, 7, 'Pet Shop', 'Everything your pets need in one place.', '789-012-3456', 55.7558, 37.6173, 10, 'Food, Toys, Accessories', 45, 20, NOW(), NOW()),
(8, 8, 'Health & Beauty', 'Beauty and health products at affordable prices.', '890-123-4567', 37.7749, -122.4194, 35, 'Skincare, Supplements, Makeup', 130, 60, NOW(), NOW()),
(9, 9, 'Tech Hub', 'Latest tech gadgets and software solutions.', '901-234-5678', 52.5200, 13.4050, 18, 'Laptops, Phones, Software', 200, 85, NOW(), NOW()),
(10, 10, 'Grocery Store', 'Fresh groceries delivered to your door.', '012-345-6789', 39.9042, 116.4074, 12, 'Fruits, Vegetables, Snacks', 70, 30, NOW(), NOW());


-- Cart Addresses
INSERT INTO cart_addresses (id, description, low_description, city_id, district_id, cart_id, created_at, updated_at) VALUES
(1, 'Large shopping cart with multiple items', 'A big cart with many products', 1, 101, 1, NOW(), NOW()),
(2, 'Grocery cart for weekly shopping', 'A cart with groceries', 2, 102, 2, NOW(), NOW()),
(3, 'Electronics cart with latest gadgets', 'Cart with new electronics', 3, 103, 3, NOW(), NOW()),
(4, 'Clothing cart for summer collection', 'Summer clothes in cart', 4, 104, 4, NOW(), NOW()),
(5, 'Books and stationery shopping cart', 'Cart with books and pens', 5, 105, 5, NOW(), NOW()),
(6, 'Furniture shopping cart for home', 'Cart with furniture items', 6, 106, 6, NOW(), NOW()),
(7, 'Sporting goods shopping cart', 'Cart with sports equipment', 7, 107, 7, NOW(), NOW()),
(8, 'Cart with pet supplies', 'Supplies for pets in cart', 8, 108, 8, NOW(), NOW()),
(9, 'Home and kitchen cart with essentials', 'Cart with kitchen items', 9, 109, 9, NOW(), NOW()),
(10, 'Cart with health and beauty products', 'Health products in cart', 10, 110, 10, NOW(), NOW());
  

-- Cart Dates
INSERT INTO cart_dates (id, day, start_hour, end_hour, cart_id, created_at, updated_at) VALUES
(1, '2024-09-09', '08:00:00', '10:00:00', 1, NOW(), NOW()), 
(2, '2024-09-10', '09:00:00', '11:00:00', 2, NOW(), NOW()),
(3, '2024-09-11', '10:00:00', '12:00:00', 3, NOW(), NOW()),
(4, '2024-09-12', '11:00:00', '13:00:00', 4, NOW(), NOW()),
(5, '2024-09-13', '12:00:00', '14:00:00', 5, NOW(), NOW()),
(6, '2024-09-14', '13:00:00', '15:00:00', 6, NOW(), NOW()),
(7, '2024-09-15', '14:00:00', '16:00:00', 7, NOW(), NOW()),
(8, '2024-09-16', '15:00:00', '17:00:00', 8, NOW(), NOW()),
(9, '2024-09-17', '16:00:00', '18:00:00', 9, NOW(), NOW()),
(10, '2024-09-18', '17:00:00', '19:00:00', 10, NOW(), NOW());

-- Categories
INSERT INTO categories (name, created_at, updated_at) VALUES
('Electronics', NOW(), NOW()),
('Books', NOW(), NOW());

-- Image Paths
INSERT INTO image_paths (url, created_at, updated_at) VALUES
('http://example.com/image1.jpg', NOW(), NOW()),
('http://example.com/image2.jpg', NOW(), NOW());

-- Likes
INSERT INTO likes (account_id, card_id, created_at, updated_at, likes) VALUES
(1, 1, NOW(), NOW(), '1'),
(2, 2, NOW(), NOW(), '1');
