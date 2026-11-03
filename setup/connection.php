<?php
/**
 * Database Connection & Auto-Setup
 * Mendukung koneksi lokal (Laragon) dan Cloud (Aiven MySQL, Railway, Render)
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Konfigurasi Database (Mendukung Environment Variables untuk Deployment Cloud)
$dbHost = getenv('DB_HOST') ?: 'localhost';
$dbPort = getenv('DB_PORT') ?: '3306';
$dbName = getenv('DB_NAME') ?: 'bunga_tulip';
$dbUser = getenv('DB_USER') ?: 'root';
$dbPass = getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('DB_PASS') ?: '');

// Jika menggunakan URL koneksi tunggal seperti mysql://user:pass@host:port/dbname (Railway / Aiven / Render)
$databaseUrl = getenv('DATABASE_URL') ?: getenv('MYSQL_URL');
if (!empty($databaseUrl)) {
    $parsedUrl = parse_url($databaseUrl);
    if ($parsedUrl) {
        $dbHost = $parsedUrl['host'] ?? $dbHost;
        $dbPort = $parsedUrl['port'] ?? $dbPort;
        $dbUser = $parsedUrl['user'] ?? $dbUser;
        $dbPass = $parsedUrl['pass'] ?? $dbPass;
        $dbName = ltrim($parsedUrl['path'] ?? '', '/') ?: $dbName;
    }
}

$pdo = null;

try {
    // 1. Coba koneksi ke database yang ditentukan
    $dsn = "mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    // Dukungan SSL untuk Aiven MySQL (jika diaktifkan)
    if (getenv('DB_SSL_CA')) {
        $options[PDO::MYSQL_ATTR_SSL_CA] = getenv('DB_SSL_CA');
    }

    $pdo = new PDO($dsn, $dbUser, $dbPass, $options);

} catch (PDOException $e) {
    // Jika database belum ada di localhost, coba buat otomatis (hanya untuk localhost)
    if ($dbHost === 'localhost' || $dbHost === '127.0.0.1') {
        try {
            $rootPdo = new PDO("mysql:host=$dbHost;port=$dbPort;charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);
            $rootPdo->exec("CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");
            
            // Reconnect ke database baru
            $pdo = new PDO("mysql:host=$dbHost;port=$dbPort;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $ex) {
            die("<div style='font-family: sans-serif; padding: 20px; background: #ffebee; border: 1px solid #ef5350; border-radius: 8px; margin: 20px;'>
                <h3 style='color: #c62828;'>Gagal Terhubung ke Database!</h3>
                <p>Pesan Error: " . htmlspecialchars($ex->getMessage()) . "</p>
                <p>Pastikan MySQL di Laragon sudah aktif (Klik 'Start All' di Laragon).</p>
            </div>");
        }
    } else {
        die("<div style='font-family: sans-serif; padding: 20px; background: #ffebee; border: 1px solid #ef5350; border-radius: 8px; margin: 20px;'>
            <h3 style='color: #c62828;'>Gagal Terhubung ke Database Cloud!</h3>
            <p>Pesan Error: " . htmlspecialchars($e->getMessage()) . "</p>
            <p>Periksa kembali konfigurasi DB_HOST, DB_NAME, DB_USER, DB_PASSWORD di environment variables Anda.</p>
        </div>");
    }
}

// 2. Auto-Migration & Seed Data jika tabel belum dibuat
try {
    $checkTable = $pdo->query("SHOW TABLES LIKE 'tulips'");
    if ($checkTable->rowCount() === 0) {
        // Buat tabel users
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `users` (
                `id` INT AUTO_INCREMENT PRIMARY KEY,
                `name` VARCHAR(100) NOT NULL,
                `email` VARCHAR(100) NOT NULL UNIQUE,
                `username` VARCHAR(50) NOT NULL UNIQUE,
                `password` VARCHAR(255) NOT NULL,
                `role` ENUM('admin', 'user') DEFAULT 'user',
                `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
        ");

        // Buat tabel tulips
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `tulips` (
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
        ");

        // Buat tabel orders
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS `orders` (
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
        ");

        // Seed Admin & Demo User
        $adminPass = password_hash('admin123', PASSWORD_DEFAULT);
        $userPass = password_hash('user123', PASSWORD_DEFAULT);

        $stmtUser = $pdo->prepare("INSERT INTO `users` (`name`, `email`, `username`, `password`, `role`) VALUES (?, ?, ?, ?, ?)");
        $stmtUser->execute(['Administrator Tulip', 'admin@tuliphaven.id', 'admin', $adminPass, 'admin']);
        $stmtUser->execute(['Mina Pembeli', 'mina@student.unika.ac.id', 'mina', $userPass, 'user']);

        // Seed Data Tulip
        $sampleTulips = [
            [
                'name' => 'Red Impression Tulip',
                'scientific_name' => 'Tulipa \'Red Impression\'',
                'color' => 'Merah',
                'category' => 'Darwin Hybrid',
                'meaning' => 'Simbol cinta abadi, gairah mendalam, dan ketulusan hati sejati.',
                'price' => 35000,
                'stock' => 25,
                'image_url' => 'https://images.unsplash.com/photo-1520763185298-1b434c919102?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Siram saat tanah bagian atas mulai kering (2-3 hari sekali). Letakkan di tempat dengan pencahayaan sinar matahari tidak langsung.'
            ],
            [
                'name' => 'Golden Apeldoorn Tulip',
                'scientific_name' => 'Tulipa \'Golden Apeldoorn\'',
                'color' => 'Kuning',
                'category' => 'Darwin Hybrid',
                'meaning' => 'Simbol kebahagiaan, keceriaan, persahabatan hangat, dan senyuman yang menyinari hari.',
                'price' => 38000,
                'stock' => 18,
                'image_url' => 'https://images.unsplash.com/photo-1588628566587-dbd176de94b4?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Membutuhkan suhu sejuk berkisar 15-20°C. Gunakan pot dengan drainase air yang lancar agar umbi tidak membusuk.'
            ],
            [
                'name' => 'White Dream Tulip',
                'scientific_name' => 'Tulipa \'White Dream\'',
                'color' => 'Putih',
                'category' => 'Triumph',
                'meaning' => 'Simbol kesucian, permintaan maaf yang tulus, kehormatan, dan awal yang baru.',
                'price' => 40000,
                'stock' => 20,
                'image_url' => 'https://images.unsplash.com/photo-1558693165-27471249b52a?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Hindari membasahi kelopak bunga langsung saat menyiram untuk menjaga kebersihan warna putih alaminya.'
            ],
            [
                'name' => 'Purple Prince Tulip',
                'scientific_name' => 'Tulipa \'Purple Prince\'',
                'color' => 'Ungu',
                'category' => 'Single Early',
                'meaning' => 'Simbol keagungan, martabat keluarga kerajaan, kekaguman, dan kemewahan yang anggun.',
                'price' => 45000,
                'stock' => 15,
                'image_url' => 'https://images.unsplash.com/photo-1525310072745-f49212b5ac6d?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Beri pupuk berimbang khusus tanaman berbunga sebulan sekali pada masa pertumbuhan aktif.'
            ],
            [
                'name' => 'Pink Diamond Tulip',
                'scientific_name' => 'Tulipa \'Pink Diamond\'',
                'color' => 'Pink',
                'category' => 'Single Late',
                'meaning' => 'Simbol kasih sayang yang manis, rasa kepedulian yang lembut, dan harapan baik.',
                'price' => 36000,
                'stock' => 30,
                'image_url' => 'https://images.unsplash.com/photo-1490750967868-88aa4486c946?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Sangat cocok untuk buket meja dalam ruangan. Ganti air vas tiap 2 hari dan potong sedikit tangkai bawah secara miring.'
            ],
            [
                'name' => 'Queen of Night Tulip',
                'scientific_name' => 'Tulipa \'Queen of Night\'',
                'color' => 'Hitam/Ungu Gelap',
                'category' => 'Single Late',
                'meaning' => 'Simbol keunikan misterius, kekuatan kepribadian, keteguhan, dan pesona malam.',
                'price' => 50000,
                'stock' => 12,
                'image_url' => 'https://images.unsplash.com/photo-1527061011665-3652c757a4d4?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Salah satu varietas tulip terlangka. Simpan umbi di tempat kering dan sejuk sebelum masa tanam musim semi.'
            ],
            [
                'name' => 'Orange Princess Tulip',
                'scientific_name' => 'Tulipa \'Orange Princess\'',
                'color' => 'Oranye',
                'category' => 'Double Late',
                'meaning' => 'Simbol energi, antusiasme, rasa percaya diri tinggi, dan sambutan yang hangat.',
                'price' => 42000,
                'stock' => 14,
                'image_url' => 'https://images.unsplash.com/photo-1554631221-f9603e6808be?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Bunga bertumpuk (peony-flowered) memiliki bobot bunga yang cukup berat, gunakan penyangga tangkai jika dibutuhkan.'
            ],
            [
                'name' => 'Estella Rijnveld (Parrot)',
                'scientific_name' => 'Tulipa \'Estella Rijnveld\'',
                'color' => 'Campuran (Merah-Putih)',
                'category' => 'Parrot Tulip',
                'meaning' => 'Simbol kebebasan berekspresi, kreativitas seni, dan keindahan alam yang tak terbatas.',
                'price' => 48000,
                'stock' => 10,
                'image_url' => 'https://images.unsplash.com/photo-1589244159943-460088ed5c92?auto=format&fit=crop&w=800&q=80',
                'care_tips' => 'Kelopak bergelombang seperti bulu burung nuri. Lindungi dari hembusan angin kencang secara langsung.'
            ]
        ];

        $stmtTulip = $pdo->prepare("
            INSERT INTO `tulips` (`name`, `scientific_name`, `color`, `category`, `meaning`, `price`, `stock`, `image_url`, `care_tips`) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
        ");

        foreach ($sampleTulips as $t) {
            $stmtTulip->execute([
                $t['name'],
                $t['scientific_name'],
                $t['color'],
                $t['category'],
                $t['meaning'],
                $t['price'],
                $t['stock'],
                $t['image_url'],
                $t['care_tips']
            ]);
        }

        // Seed Sample Order
        $stmtOrder = $pdo->prepare("
            INSERT INTO `orders` (`customer_name`, `customer_email`, `customer_phone`, `tulip_id`, `quantity`, `message`, `status`) 
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ");
        $stmtOrder->execute([
            'Siti Rahma',
            'siti.rahma@gmail.com',
            '081234567890',
            1,
            3,
            'Mohon dipaketkan dengan pita satin warna emas untuk kado ulang tahun ibu saya.',
            'diproses'
        ]);
    }
} catch (PDOException $e) {
    // Abaikan jika tabel sudah ada atau ada error minor saat verifikasi skema
}
