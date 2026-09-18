<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../config/database.php";

if (!$db_connected || !$pdo) {
    echo json_encode([
        "success" => false,
        "message" => "Database not connected",
        "categories" => []
    ]);
    exit;
}

try {
    $stmt = $pdo->query("SELECT id, name, description, image FROM categories ORDER BY id ASC");
    $categories = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "count" => count($categories),
        "categories" => $categories
    ]);
} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "categories" => []
    ]);
}
