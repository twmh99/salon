<?php
/**
 * Database Configuration
 * Konfigurasi koneksi database untuk DBeauty Skincare & Day Spa
 */

// Database configuration (ubah DRIVER ke 'mysql' jika ingin memakai server MySQL)
define('DB_DRIVER', getenv('DB_DRIVER') ?: 'sqlite');
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'dbeauty_spa');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_CHARSET', 'utf8mb4');
define('DB_SQLITE_PATH', __DIR__ . '/../database/dbeauty.sqlite');

/**
 * Get database connection
 * @return PDO Database connection object
 * @throws PDOException If connection fails
 */
function getDBConnection() {
    static $pdo = null;

    if ($pdo === null) {
        try {
            if (DB_DRIVER === 'mysql') {
                $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
                $pdo = new PDO($dsn, DB_USER, DB_PASS, getPdoOptions());
            } else {
                if (!file_exists(DB_SQLITE_PATH)) {
                    touch(DB_SQLITE_PATH);
                }
                $dsn = 'sqlite:' . DB_SQLITE_PATH;
                $pdo = new PDO($dsn, null, null, getPdoOptions());
                initializeSqliteDatabase($pdo);
            }

        } catch (PDOException $e) {
            // Bungkus menjadi exception baru agar bisa ditangani global handler
            throw new RuntimeException(
                'Koneksi database gagal. Periksa konfigurasi di config/database.php dan pastikan driver/engine tersedia.',
                0,
                $e
            );
        }
    }

    return $pdo;
}

/**
 * Execute prepared statement with parameters
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return PDOStatement
 */
function executeQuery($sql, $params = []) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

/**
 * Get single row from query
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array|null
 */
function getSingleRow($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetch();
}

/**
 * Get multiple rows from query
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return array
 */
function getMultipleRows($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->fetchAll();
}

/**
 * Insert data and return last insert ID
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return int Last insert ID
 */
function insertData($sql, $params = []) {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $pdo->lastInsertId();
}

/**
 * Update data
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows
 */
function updateData($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->rowCount();
}

/**
 * Delete data
 * @param string $sql SQL query
 * @param array $params Parameters for prepared statement
 * @return int Number of affected rows
 */
function deleteData($sql, $params = []) {
    $stmt = executeQuery($sql, $params);
    return $stmt->rowCount();
}

function getPdoOptions(): array
{
    return [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
}

function initializeSqliteDatabase(PDO $pdo): void
{
    $pdo->exec('CREATE TABLE IF NOT EXISTS admin (
        admin_id INTEGER PRIMARY KEY AUTOINCREMENT,
        username TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        updated_at TEXT DEFAULT CURRENT_TIMESTAMP
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS user (
        customer_id INTEGER PRIMARY KEY AUTOINCREMENT,
        nama TEXT NOT NULL,
        nomor_hp TEXT UNIQUE NOT NULL,
        password TEXT NOT NULL,
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
        deleted_at TEXT NULL
    )');

    $pdo->exec('CREATE TABLE IF NOT EXISTS schedule (
        schedule_id INTEGER PRIMARY KEY AUTOINCREMENT,
        tanggal TEXT NOT NULL,
        waktu TEXT NOT NULL,
        slot INTEGER NOT NULL DEFAULT 1,
        jenis_treatment TEXT NOT NULL
    )');

    $pdo->exec("CREATE TABLE IF NOT EXISTS reservasi (
        reservation_id INTEGER PRIMARY KEY AUTOINCREMENT,
        customer_id INTEGER NOT NULL,
        schedule_id INTEGER NOT NULL,
        reservation_date TEXT DEFAULT CURRENT_TIMESTAMP,
        status TEXT DEFAULT 'pending',
        created_at TEXT DEFAULT CURRENT_TIMESTAMP,
        updated_at TEXT DEFAULT CURRENT_TIMESTAMP
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS verifikasi (
        verification_id INTEGER PRIMARY KEY AUTOINCREMENT,
        reservation_id INTEGER NOT NULL,
        admin_id INTEGER NOT NULL,
        tanggal_verifikasi TEXT DEFAULT CURRENT_TIMESTAMP,
        status TEXT NOT NULL,
        notes TEXT
    )");

    $stmt = $pdo->query("SELECT COUNT(*) as total FROM admin");
    $row = $stmt->fetch();
    if ((int)($row['total'] ?? 0) === 0) {
        $hash = password_hash('password', PASSWORD_BCRYPT);
        $pdo->prepare("INSERT INTO admin (username, password) VALUES (?, ?)")
            ->execute(['admin', $hash]);
    }

    $stmt = $pdo->query("SELECT COUNT(*) AS total FROM schedule");
    $row = $stmt->fetch();
    if ((int)($row['total'] ?? 0) === 0) {
        $seedSchedules = [
            ['2024-08-01', '09:00', 3, 'HC1'],
            ['2024-08-01', '11:00', 3, 'HC7'],
            ['2024-08-01', '13:00', 2, 'HC23'],
            ['2024-08-02', '10:00', 3, 'HC4'],
            ['2024-08-02', '14:00', 3, 'HC10'],
            ['2024-08-02', '16:00', 2, 'HC20'],
            ['2024-08-03', '09:00', 4, 'HC13'],
            ['2024-08-03', '11:30', 2, 'HC19'],
            ['2024-08-03', '15:00', 3, 'HC30'],
        ];
        $stmtInsert = $pdo->prepare("INSERT INTO schedule (tanggal, waktu, slot, jenis_treatment) VALUES (?, ?, ?, ?)");
        foreach ($seedSchedules as $schedule) {
            $stmtInsert->execute($schedule);
        }
    }
}
