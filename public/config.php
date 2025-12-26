<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'library_db';
$portsToTry = ['3307', '3306'];
$pdo = null;
$lastException = null;
foreach ($portsToTry as $DB_PORT) {
    try {
        $pdo = new PDO(
            "mysql:host=$DB_HOST;port=$DB_PORT;dbname=$DB_NAME;charset=utf8mb4",
            $DB_USER,
            $DB_PASS,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]
        );
        break; // success
    } catch (PDOException $e) {
        $lastException = $e;
    }
}

if (!$pdo) {
    $tried = implode(', ', $portsToTry);
    $err = $lastException ? $lastException->getMessage() : 'unknown error';
    die("Database connection failed. Tried ports: $tried. Error: $err");
}
?>
