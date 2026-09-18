<?php

session_start();
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../config/database.php";

if (!$db_connected || !$pdo) {
    echo json_encode(["success" => false, "message" => "Database offline."]);
    exit;
}

if (empty($_SESSION["user_id"])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Please sign in to cancel an order."]);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true) ?: [];
$orderId = filter_var($data["order_id"] ?? null, FILTER_VALIDATE_INT);

if (!$orderId) {
    http_response_code(422);
    echo json_encode(["success" => false, "message" => "A valid order is required."]);
    exit;
}

try {
    $pdo->beginTransaction();

    $orderStmt = $pdo->prepare("SELECT id, order_status FROM orders WHERE id = ? AND user_id = ? FOR UPDATE");
    $orderStmt->execute([$orderId, $_SESSION["user_id"]]);
    $order = $orderStmt->fetch();

    if (!$order) {
        $pdo->rollBack();
        http_response_code(404);
        echo json_encode(["success" => false, "message" => "Order not found."]);
        exit;
    }

    if (!in_array(strtolower($order["order_status"]), ["pending", "confirmed", "processing"], true)) {
        $pdo->rollBack();
        http_response_code(409);
        echo json_encode(["success" => false, "message" => "This order can no longer be cancelled."]);
        exit;
    }

    $itemsStmt = $pdo->prepare("SELECT product_id, quantity FROM order_items WHERE order_id = ? AND product_id IS NOT NULL");
    $itemsStmt->execute([$orderId]);
    $stockStmt = $pdo->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
    foreach ($itemsStmt->fetchAll() as $item) {
        $stockStmt->execute([(int)$item["quantity"], (int)$item["product_id"]]);
    }

    $updateStmt = $pdo->prepare("UPDATE orders SET order_status = 'cancelled', payment_status = CASE WHEN payment_status = 'paid' THEN 'refund_pending' ELSE payment_status END WHERE id = ?");
    $updateStmt->execute([$orderId]);
    $pdo->commit();

    echo json_encode(["success" => true, "message" => "Order cancelled successfully.", "order_id" => $orderId]);
} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    http_response_code(500);
    echo json_encode(["success" => false, "message" => "Could not cancel the order."]);
}