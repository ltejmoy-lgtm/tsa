<?php

$host = "localhost";
$dbname = "tsa_shop";
$username = "root";
$password = "";

$pdo = null;
$db_connected = false;
$db_error = null;

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbname;charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ]
    );
    $db_connected = true;
} catch (PDOException $e) {
    $pdo = null;
    $db_connected = false;
    $db_error = $e->getMessage();
}

function get_db_connection() {
    global $pdo;
    return $pdo;
}