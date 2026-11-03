-- =======================================================
-- Database Skema: Website Bunga Tulip (Tulip Haven)
-- Dibuat untuk: Tugas IT / Pemrograman Web
-- =======================================================

CREATE DATABASE IF NOT EXISTS `bunga_tulip` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `bunga_tulip`;

-- 1. Tabel Users (Untuk login Admin & Pengguna)
DROP TABLE IF EXISTS `orders`;
DROP TABLE IF EXISTS `tulips`;
DROP TABLE IF EXISTS `users`;

CREATE TABLE `users` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `email` VARCHAR(100) NOT NULL UNIQUE,
  `username` VARCHAR(50) NOT NULL UNIQUE,
  `password` VARCHAR(255) NOT NULL,
  `role` ENUM('admin', 'user') DEFAULT 'user',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 2. Tabel Tulips (Katalog & Informasi Bunga Tulip)
CREATE TABLE `tulips` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `name` VARCHAR(100) NOT NULL,
  `scientific_name` VARCHAR(100) NOT NULL,
  `color` VARCHAR(50) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `meaning` TEXT NOT NULL,
  `price` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL DEFAULT 10,
  `image_url` VARCHAR(500) NOT NULL,
  `care_tips` TEXT NOT NULL,
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3. Tabel Orders / Pesan Pengunjung
CREATE TABLE `orders` (
  `id` INT AUTO_INCREMENT PRIMARY KEY,
  `customer_name` VARCHAR(100) NOT NULL,
  `customer_email` VARCHAR(100) NOT NULL,
  `customer_phone` VARCHAR(30) NOT NULL,
  `tulip_id` INT NULL,
  `quantity` INT NOT NULL DEFAULT 1,
  `message` TEXT NULL,
  `status` ENUM('pending', 'diproses', 'selesai', 'dibatalkan') DEFAULT 'pending',
  `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  CONSTRAINT `fk_orders_tulips` FOREIGN KEY (`tulip_id`) REFERENCES `tulips`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- =======================================================
-- DATA AWAL (SEED DATA)
-- Password admin: admin123
-- Password user : user123
-- =======================================================

INSERT INTO `users` (`name`, `email`, `username`, `password`, `role`) VALUES
('Administrator Tulip', 'admin@tuliphaven.id', 'admin', '$2y$10$O01o4V62mK7b0.7OQn6DweGz4g91fC/yYg0vH.a5wT8tE0i5s5fW2', 'admin'),
('Mina Pembeli', 'mina@student.unika.ac.id', 'mina', '$2y$10$tZzCvy7b5a/bL6h5iH7s8eF1Vn2pW9qR0s3k4l5m6n7o8p9q0r1s2', 'user');

INSERT INTO `tulips` (`name`, `scientific_name`, `color`, `category`, `meaning`, `price`, `stock`, `image_url`, `care_tips`) VALUES
('Red Impression Tulip', 'Tulipa \'Red Impression\'', 'Merah', 'Darwin Hybrid', 'Simbol cinta abadi, gairah mendalam, dan ketulusan hati sejati.', 35000.00, 25, 'https://images.unsplash.com/photo-1520763185298-1b434c919102?auto=format&fit=crop&w=800&q=80', 'Siram saat tanah bagian atas mulai kering (2-3 hari sekali). Letakkan di tempat dengan pencahayaan sinar matahari tidak langsung.'),
('Golden Apeldoorn Tulip', 'Tulipa \'Golden Apeldoorn\'', 'Kuning', 'Darwin Hybrid', 'Simbol kebahagiaan, keceriaan, persahabatan hangat, dan senyuman yang menyinari hari.', 38000.00, 18, 'https://images.unsplash.com/photo-1588628566587-dbd176de94b4?auto=format&fit=crop&w=800&q=80', 'Membutuhkan suhu sejuk berkisar 15-20°C. Gunakan pot dengan drainase air yang lancar agar umbi tidak membusuk.'),
('White Dream Tulip', 'Tulipa \'White Dream\'', 'Putih', 'Triumph', 'Simbol kesucian, permintaan maaf yang tulus, kehormatan, dan awal yang baru.', 40000.00, 20, 'https://images.unsplash.com/photo-1558693165-27471249b52a?auto=format&fit=crop&w=800&q=80', 'Hindari membasahi kelopak bunga langsung saat menyiram untuk menjaga kebersihan warna putih alaminya.'),
('Purple Prince Tulip', 'Tulipa \'Purple Prince\'', 'Ungu', 'Single Early', 'Simbol keagungan, martabat keluarga kerajaan, kekaguman, dan kemewahan yang anggun.', 45000.00, 15, 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&w=800&q=80', 'Beri pupuk berimbang khusus tanaman berbunga sebulan sekali pada masa pertumbuhan aktif.'),
('Pink Diamond Tulip', 'Tulipa \'Pink Diamond\'', 'Pink', 'Single Late', 'Simbol kasih sayang yang manis, rasa kepedulian yang lembut, dan harapan baik.', 36000.00, 30, 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80', 'Sangat cocok untuk buket meja dalam ruangan. Ganti air vas tiap 2 hari dan potong sedikit tangkai bawah secara miring.'),
('Queen of Night Tulip', 'Tulipa \'Queen of Night\'', 'Hitam/Ungu Gelap', 'Single Late', 'Simbol keunikan misterius, kekuatan kepribadian, keteguhan, dan pesona malam.', 50000.00, 12, 'https://images.unsplash.com/photo-1527061011665-3652c757a4d4?auto=format&fit=crop&w=800&q=80', 'Salah satu varietas tulip terlangka. Simpan umbi di tempat kering dan sejuk sebelum masa tanam musim semi.'),
('Orange Princess Tulip', 'Tulipa \'Orange Princess\'', 'Oranye', 'Double Late', 'Simbol energi, antusiasme, rasa percaya diri tinggi, dan sambutan yang hangat.', 42000.00, 14, 'https://images.unsplash.com/photo-1554631221-f9603e6808be?auto=format&fit=crop&w=800&q=80', 'Bunga bertumpuk (peony-flowered) memiliki bobot bunga yang cukup berat, gunakan penyangga tangkai jika dibutuhkan.'),
('Estella Rijnveld (Parrot)', 'Tulipa \'Estella Rijnveld\'', 'Campuran (Merah-Putih)', 'Parrot Tulip', 'Simbol kebebasan berekspresi, kreativitas seni, dan keindahan alam yang tak terbatas.', 48000.00, 10, 'https://images.unsplash.com/photo-1589244159943-460088ed5c92?auto=format&fit=crop&w=800&q=80', 'Kelopak bergelombang seperti bulu burung nuri. Lindungi dari hembusan angin kencang secara langsung.');

INSERT INTO `orders` (`customer_name`, `customer_email`, `customer_phone`, `tulip_id`, `quantity`, `message`, `status`) VALUES
('Siti Rahma', 'siti.rahma@gmail.com', '081234567890', 1, 3, 'Mohon dipaketkan dengan pita satin warna emas untuk kado ulang tahun ibu saya.', 'diproses');
