DROP TABLE IF EXISTS listings;
CREATE TABLE listings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    city VARCHAR(100) NOT NULL,
    price DECIMAL(10, 2) NOT NULL,
    image_url TEXT,
    is_active BOOLEAN DEFAULT TRUE
) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
INSERT INTO listings (title, city, price, image_url) VALUES 
('Casa cu grădină', 'Brașov', 220, 'https://www.romanian-adventures.ro/uploads/images/casa_clementina_brasov_2.jpeg'),
('Apartament Modern', 'București', 320, 'https://www.nobili-interior-design.ro/storage/posts/418/900_design_interior_apartament_modern_in_bucuresti_2.jpg'),
('Garsoniera Centrală', 'Cluj', 280, 'https://hotnews.ro/wp-content/uploads/2024/04/image-2022-02-18-25374024-41-garsoniera-11.jpg'),
('Cabana Rustică', 'Brașov', 450, 'https://iturist.ro/wp-content/uploads/2024/08/Wooden-Chalet-Retezat.jpg');


Select * FROM listings;