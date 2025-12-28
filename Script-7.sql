/*USE hotel_splendid_db;*/

CREATE TABLE guests (
  id INT AUTO_INCREMENT PRIMARY KEY,
  first_name VARCHAR(100) NOT NULL,
  last_name VARCHAR(100) NOT NULL,
  dob DATE,
  gender ENUM('male', 'female', 'prefer_not_to_say'),
  email VARCHAR(150) NOT NULL UNIQUE,
  username VARCHAR(100) NOT NULL UNIQUE,
  password VARCHAR(225) NOT NULL,
  tel_num VARCHAR(20),
  country VARCHAR(100),
  city VARCHAR(100),
  address VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  room_number VARCHAR(10) NOT NULL UNIQUE,
  floor INT,
  type VARCHAR(50),
  base_price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  description TEXT,
  status VARCHAR(20) DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;	

CREATE INDEX idx_rooms_type ON rooms(type);

ALTER TABLE rooms
MODIFY COLUMN type ENUM('single','double','suite','deluxe','family') DEFAULT 'single';

CREATE TABLE bookings (
  id INT AUTO_INCREMENT PRIMARY KEY,
  guest_id INT NOT NULL,
  check_in_date DATE NOT NULL,
  check_out_date DATE NOT NULL,
  num_of_guests INT DEFAULT 1,
  num_of_children INT DEFAULT 0,
  type ENUM('standard','nonrefundable','corporate','agency','other') DEFAULT 'standard',
  total_price DECIMAL(10,2) DEFAULT 0.00,
  status ENUM('pending','confirmed','cancelled','completed') DEFAULT 'pending',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  
  CONSTRAINT chk_booking_dates CHECK (check_out_date > check_in_date),
  CONSTRAINT fk_bookings_guest
    FOREIGN KEY (guest_id) REFERENCES guests(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_bookings_guest ON bookings(guest_id);
CREATE INDEX idx_bookings_dates ON bookings(check_in_date, check_out_date);

CREATE TABLE booking_rooms (
  id INT AUTO_INCREMENT PRIMARY KEY,
  booking_id INT NOT NULL,
  room_id INT NOT NULL,
  nightly_rate DECIMAL(10,2) DEFAULT 0.00,
  CONSTRAINT fk_booking_rooms_booking
    FOREIGN KEY (booking_id) REFERENCES bookings(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE,
  CONSTRAINT fk_booking_rooms_room
    FOREIGN KEY (room_id) REFERENCES rooms(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  UNIQUE (booking_id, room_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE INDEX idx_booking_rooms_room ON booking_rooms(room_id);

CREATE TABLE reviews (
  id INT AUTO_INCREMENT PRIMARY KEY,
  guest_id INT NOT NULL,
  rating INT NOT NULL,
  title VARCHAR(150),
  comment TEXT,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
  CONSTRAINT fk_reviews_guest
    FOREIGN KEY (guest_id) REFERENCES guests(id)
    ON UPDATE CASCADE
    ON DELETE RESTRICT,
  CONSTRAINT chk_review_rating CHECK (rating BETWEEN 1 AND 5)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO guests (first_name, last_name, dob, gender, email, username, password, tel_num, country, city, address)
VALUES
('Danin', 'Mangafic', '1995-03-12', 'male', 'danin.mangafic@example.com', 'daninm', 'password123', '+38761111222', 'Bosnia and Herzegovina', 'Sarajevo', 'Titova 5'),
('Davud', 'Mahmutovic', '1996-07-21', 'male', 'davud.mahmutovic@example.com', 'davudm', 'password', '+38761222333', 'Bosnia and Herzegovina', 'Sarajevo', 'Zmaja od Bosne 12'),
('Muhamed', 'Sarajlic', '1997-02-15', 'male', 'muhamed.sarajlic@example.com', 'muhameds', 'pass123', '+38761333444', 'Bosnia and Herzegovina', 'Tuzla', 'Alije Izetbegovica 8'),
('Rijad', 'Pleho', '1994-11-09', 'male', 'rijad.pleho@example.com', 'rijadp', 'sifra123', '+38761444555', 'Bosnia and Herzegovina', 'Mostar', 'Marsala Tita 16'),
('Eldar', 'Musovic', '1995-05-30', 'male', 'eldar.musovic@example.com', 'eldarm', 'sifrasifra', '+38761555666', 'Bosnia and Herzegovina', 'Zenica', 'Kralja Tvrtka 7');

INSERT INTO rooms (room_number, floor, type, base_price, description, status)
VALUES
('101', 1, 'single', 80.00, 'Single room with balcony', 'available'),
('102', 1, 'double', 120.00, 'Double room with city view', 'available'),
('201', 2, 'suite', 200.00, 'Luxury suite with living area', 'occupied'),
('202', 2, 'deluxe', 160.00, 'Deluxe room with modern decor', 'available'),
('301', 3, 'family', 180.00, 'Family room with two bedrooms', 'maintenance');

INSERT INTO bookings (guest_id, check_in_date, check_out_date, num_of_guests, num_of_children, type, total_price, status)
VALUES
(1, '2025-11-01', '2025-11-05', 1, 0, 'standard', 320.00, 'confirmed'),
(2, '2025-11-03', '2025-11-04', 2, 0, 'nonrefundable', 120.00, 'completed'),
(3, '2025-11-10', '2025-11-13', 2, 1, 'corporate', 480.00, 'pending'),
(4, '2025-12-01', '2025-12-07', 3, 1, 'agency', 960.00, 'confirmed'),
(5, '2025-12-10', '2025-12-12', 2, 0, 'standard', 240.00, 'cancelled');

INSERT INTO booking_rooms (booking_id, room_id, nightly_rate)
VALUES
(1, 1, 80.00),
(2, 2, 120.00),
(3, 3, 160.00),
(4, 4, 160.00),
(5, 5, 120.00);

INSERT INTO reviews (guest_id, rating, title, comment)
VALUES
(1, 5, 'Fantastic stay', 'Everything was perfect, clean and quiet.'),
(2, 4, 'Good experience', 'Nice staff, great breakfast.'),
(3, 3, 'Average', 'Room was fine, but Wi-Fi was weak.'),
(4, 5, 'Loved it', 'Spacious room and friendly reception.'),
(5, 2, 'Could be better', 'Room was cold and needs renovation.');

CREATE TABLE `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(150) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_email` (`email`),
  UNIQUE KEY `unique_username` (`username`),
  KEY `idx_role` (`role`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

ALTER TABLE `guests` 
ADD COLUMN `user_id` int(11) NULL AFTER `id`,
ADD CONSTRAINT `fk_guests_users` 
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) 
    ON DELETE CASCADE,
ADD UNIQUE KEY `unique_user_id` (`user_id`);

INSERT INTO `users` (`email`, `username`, `password`, `role`) VALUES
('danin.mangafic@example.com', 'daninm', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'admin'),
('davud.mahmutovic@example.com', 'davudm', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'user'),
('muhamed.sarajlic@example.com', 'muhameds', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'user'),
('rijad.pleho@example.com', 'rijadp', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'user'),
('eldar.musovic@example.com', 'eldarm', '$2y$10$abcdefghijklmnopqrstuvwxyz123456', 'user');

UPDATE `guests` SET `user_id` = 1 WHERE `id` = 1;
UPDATE `guests` SET `user_id` = 2 WHERE `id` = 2;
UPDATE `guests` SET `user_id` = 3 WHERE `id` = 3;
UPDATE `guests` SET `user_id` = 4 WHERE `id` = 4;
UPDATE `guests` SET `user_id` = 5 WHERE `id` = 5;

INSERT INTO `users` (`id`, `email`, `username`, `password`, `role`) VALUES
(6, 'michael.stone@example.com', 'MichStone', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(10, 'johny.dolk@example.com', 'johnnydolk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(12, 'michael.dolk@example.com', 'michaeldolk', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user'),
(13, 'john.jackson@example.com', 'johnjackson', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'user');

UPDATE `guests` SET `user_id` = 6 WHERE `id` = 6;
UPDATE `guests` SET `user_id` = 10 WHERE `id` = 10;
UPDATE `guests` SET `user_id` = 12 WHERE `id` = 12;
UPDATE `guests` SET `user_id` = 13 WHERE `id` = 13;

SELECT '=== USERS TABLE ===' as '';
SELECT id, email, username, role FROM users ORDER BY id;

SELECT '=== GUESTS WITH USER_ID ===' as '';
SELECT id, user_id, first_name, last_name, email FROM guests ORDER BY id;

SELECT '=== LINKED DATA ===' as '';
SELECT 
    u.id as user_id,
    u.username,
    u.role,
    g.id as guest_id,
    g.first_name,
    g.last_name,
    g.email as guest_email
FROM users u
JOIN guests g ON u.id = g.user_id
ORDER BY u.id;

ALTER TABLE `guests` 
DROP COLUMN `username`,
DROP COLUMN `password`;

DESCRIBE guests;

SELECT u.id as user_id, u.username, g.id as guest_id, g.first_name 
FROM users u 
LEFT JOIN guests g ON g.user_id = u.id;

ALTER TABLE bookings 
DROP FOREIGN KEY fk_bookings_guest;

ALTER TABLE bookings 
ADD CONSTRAINT fk_bookings_user 
FOREIGN KEY (guest_id) REFERENCES users (id) ON UPDATE CASCADE;

ALTER TABLE guests ADD COLUMN username VARCHAR(255) AFTER email;
ALTER TABLE guests ADD COLUMN password VARCHAR(255) AFTER username;