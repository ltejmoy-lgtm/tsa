<?php

header("Content-Type: application/json");

require_once "../config/database.php";

try {

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
            c.name AS category_name
        FROM products p
        LEFT JOIN categories c
            ON p.category_id = c.id
        WHERE p.status = 'active'
        ORDER BY p.id DESC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute();

    $products = $stmt->fetchAll();

    echo json_encode([
        "success" => true,
        "products" => $products
    ]);

} catch (PDOException $e) {

    echo json_encode([
        "success" => false,
        "message" => $e->getMessage()
    ]);

}

?>