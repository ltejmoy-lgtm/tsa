<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../config/database.php";

if (!$db_connected || !$pdo) {
    echo json_encode([
        "success" => false,
        "message" => "Database offline",
        "orders" => []
    ]);
    exit;
}

$userId = $_SESSION["user_id"] ?? null;

try {
    if ($userId) {
        $stmt = $pdo->prepare("
            SELECT o.*, a.full_name, a.city, a.state
            FROM orders o
            LEFT JOIN addresses a ON o.address_id = a.id
            WHERE o.user_id = ?
            ORDER BY o.id DESC
        ");
        $stmt->execute([$userId]);
    } else {
        // Fallback to top recent orders
        $stmt = $pdo->query("
            SELECT o.*, a.full_name, a.city, a.state
            FROM orders o
            LEFT JOIN addresses a ON o.address_id = a.id
            ORDER BY o.id DESC
            LIMIT 10
        ");
    }

    $orders = $stmt->fetchAll();

    // Fetch items for each order
    $itemStmt = $pdo->prepare("
        SELECT oi.*, p.image
        FROM order_items oi
        LEFT JOIN products p ON oi.product_id = p.id
        WHERE oi.order_id = ?
    ");

    foreach ($orders as &$order) {
        $itemStmt->execute([$order["id"]]);
        $order["items"] = $itemStmt->fetchAll();
    }

    echo json_encode([
        "success" => true,
        "count" => count($orders),
        "orders" => $orders
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => $e->getMessage(),
        "orders" => []
    ]);
}
