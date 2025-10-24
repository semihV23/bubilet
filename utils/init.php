<?php

// 1. Veritabanı Dosyasının Yolunu Belirleme
// __DIR__ bu script'in bulunduğu dizini temsil eder.
// Veritabanı, bu dizin içindeki 'database' klasörüne oluşturulacak.
$dbFile = __DIR__ . '/../database/database.db';

// Önce klasörün var olup olmadığını kontrol et ve yoksa oluştur.
$dbDir = dirname($dbFile);
if (!is_dir($dbDir)) {
    // Klasör oluşturulamazsa hata vererek script'i durdur.
    if (!mkdir($dbDir, 0775, true)) {
        die("❌ Hata: '{$dbDir}' klasörü oluşturulamadı. Lütfen izinleri kontrol edin.");
    }
}

$db = null; // Veritabanı nesnesini dışarıda tanımla
try {
    // 2. SQLite3 sınıfı ile veritabanına bağlanma
    // Dosya yoksa, SQLite3 bu dosyayı otomatik olarak oluşturacaktır.
    // Bağlantı hatası olursa bir istisna (Exception) fırlatır.
    $db = new SQLite3($dbFile);

    echo "Veritabanı bağlantısı başarıyla sağlandı: '{$dbFile}'<br><hr>";

    // 3. Tablo oluşturma sorgularını bir dizi içinde tanımlama
    $tables = [
        'Bus_Company' => "CREATE TABLE IF NOT EXISTS Bus_Company (
            id TEXT PRIMARY KEY NOT NULL,
            name TEXT UNIQUE NOT NULL,
            logo_path TEXT,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP
        );",

        'User' => "CREATE TABLE IF NOT EXISTS User (
            id TEXT PRIMARY KEY NOT NULL,
            full_name TEXT NOT NULL,
            email TEXT UNIQUE NOT NULL,
            role TEXT NOT NULL,
            password TEXT NOT NULL,
            company_id TEXT,
            balance INTEGER NOT NULL DEFAULT 800,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES Bus_Company(id)
        );",

        'Coupons' => "CREATE TABLE IF NOT EXISTS Coupons (
            id TEXT PRIMARY KEY NOT NULL,
            code TEXT NOT NULL,
            discount REAL NOT NULL,
            company_id TEXT,
            usage_limit INTEGER NOT NULL,
            expire_date TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES Bus_Company(id)
        );",

        'User_Coupons' => "CREATE TABLE IF NOT EXISTS User_Coupons (
            id TEXT PRIMARY KEY NOT NULL,
            coupon_id TEXT NOT NULL,
            user_id TEXT NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (coupon_id) REFERENCES Coupons(id),
            FOREIGN KEY (user_id) REFERENCES User(id)
        );",

        'Trips' => "CREATE TABLE IF NOT EXISTS Trips (
            id TEXT PRIMARY KEY NOT NULL,
            company_id TEXT NOT NULL,
            destination_city TEXT NOT NULL,
            arrival_time TEXT NOT NULL,
            departure_time TEXT NOT NULL,
            departure_city TEXT NOT NULL,
            price INTEGER NOT NULL,
            capacity INTEGER NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (company_id) REFERENCES Bus_Company(id)
        );",

        'Tickets' => "CREATE TABLE IF NOT EXISTS Tickets (
            id TEXT PRIMARY KEY NOT NULL,
            trip_id TEXT NOT NULL,
            user_id TEXT NOT NULL,
            status TEXT NOT NULL DEFAULT 'ACTIVE',
            total_price INTEGER NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (trip_id) REFERENCES Trips(id),
            FOREIGN KEY (user_id) REFERENCES User(id)
        );",

        'Booked_Seats' => "CREATE TABLE IF NOT EXISTS Booked_Seats (
            id TEXT PRIMARY KEY NOT NULL,
            ticket_id TEXT NOT NULL,
            seat_number INTEGER NOT NULL,
            created_at TEXT NOT NULL DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (ticket_id) REFERENCES Tickets(id)
        );"
    ];

    // 4. Dizideki her bir sorguyu döngü ile çalıştırma
    foreach ($tables as $tableName => $sql) {
        // exec() metodu başarılı olursa true, olmazsa false döner.
        if ($db->exec($sql)) {
            echo "✅ Tablo '<strong>{$tableName}</strong>' başarıyla oluşturuldu veya zaten mevcut.<br>";
        } else {
            // Hata durumunda, hatayı ekrana yazdır.
            echo "❌ '<strong>{$tableName}</strong>' tablosu oluşturulurken hata oluştu: " . $db->lastErrorMsg() . "<br>";
        }
    }

    echo "<hr>Tüm tabloların oluşturulma işlemi tamamlandı.";

} catch (Exception $e) {
    // Bağlantı sırasında bir hata oluşursa yakala ve göster
    die("❌ Veritabanı bağlantısı kurulamadı: " . $e->getMessage());
} finally {
    // Bağlantı nesnesi oluşturulmuşsa, bağlantıyı kapat
    if ($db) {
        $db->close();
    }
}

?>