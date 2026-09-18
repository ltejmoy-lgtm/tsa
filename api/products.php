<?php

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../config/database.php";

if (!$db_connected || !$pdo) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection unavailable.",
        "products" => []
    ]);
    exit;
}

try {
    $category = trim($_GET["category"] ?? "");
    $search = trim($_GET["search"] ?? "");

    $conditions = ["p.status = 'active'"];
    $params = [];

    if ($category !== "" && strtolower($category) !== "top offers" && strtolower($category) !== "all") {
        $conditions[] = "(c.name = ? OR LOWER(REPLACE(c.name, ' ', '-')) = ?)";
        $params[] = $category;
        $params[] = strtolower($category);
    }

    if ($search !== "") {
        $conditions[] = "(p.name LIKE ? OR p.brand LIKE ? OR p.description LIKE ?)";
        $wildcard = "%{$search}%";
        $params[] = $wildcard;
        $params[] = $wildcard;
        $params[] = $wildcard;
    }

    $whereClause = implode(" AND ", $conditions);

    $sql = "
        SELECT
            p.id,
            p.name,
            p.description,
            p.category_id,
            p.brand,
            p.price,
            p.mrp,
            p.discount,
            p.stock,
            p.image,
            p.rating,
            p.status,
            COALESCE(c.name, 'Other') AS category_name
        FROM products p
        LEFT JOIN categories c
            ON p.category_id = c.id
        WHERE {$whereClause}
        ORDER BY p.id DESC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $products = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "count" => count($products),
        "products" => $products
    ]);

} catch (PDOException $e) {
    echo json_encode([
        "success" => false,
        "message" => "Catalog query error: " . $e->getMessage(),
        "products" => []
    ]);
}