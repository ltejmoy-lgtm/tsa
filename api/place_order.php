<?php

session_start();

header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . "/../config/database.php";

if (!$db_connected || !$pdo) {
    echo json_encode([
        "success" => false,
        "message" => "Database connection unavailable. Order could not be saved to MySQL."
    ]);
    exit;
}

// Read raw JSON input
$inputRaw = file_get_contents("php://input");
$data = json_decode($inputRaw, true);

if (!$data) {
    echo json_encode([
        "success" => false,
        "message" => "Invalid JSON payload."
    ]);
    exit;
}

$customerName = trim($data["customerName"] ?? "");
$customerPhone = trim($data["customerPhone"] ?? "");
$customerPin = trim($data["customerPin"] ?? "");
$customerCity = trim($data["customerCity"] ?? "");
$customerState = trim($data["customerState"] ?? "");
$customerHouse = trim($data["customerHouse"] ?? "");
$customerAddress = trim($data["customerAddress"] ?? "");
$deliveryMethod = trim($data["deliveryMethod"] ?? "standard");
$paymentMethod = trim($data["paymentMethod"] ?? "Cash on Delivery");
$cartItems = $data["cart"] ?? [];

if (empty($customerName) || empty($customerPhone) || empty($cartItems)) {
    echo json_encode([
        "success" => false,
        "message" => "Please provide complete delivery details and at least one item."
    ]);
    exit;
}

try {
    $pdo->beginTransaction();

    // 1. Use the authenticated account when available; guest orders stay unassigned.
    $userId = $_SESSION["user_id"] ?? null;
    if (!$userId) {
        $uStmt = $pdo->prepare("SELECT id FROM users WHERE phone = ? LIMIT 1");
        $uStmt->execute([$customerPhone]);
        $existing = $uStmt->fetch();
        $userId = $existing ? $existing["id"] : null;
    }

    // 2. Insert delivery address
    $addrSql = "
        INSERT INTO addresses (user_id, full_name, phone, house_no, street, city, state, pincode)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ";
    $addrStmt = $pdo->prepare($addrSql);
    $addrStmt->execute([
        $userId,
        $customerName,
        $customerPhone,
        $customerHouse,
        $customerAddress,
        $customerCity,
        $customerState,
        $customerPin
    ]);
    $addressId = $pdo->lastInsertId();

    // 3. Compute totals
    $subtotal = 0.0;
    foreach ($cartItems as $item) {
        $price = (float)($item["price"] ?? 0);
        $qty = (int)($item["qty"] ?? 1);
        $subtotal += ($price * $qty);
    }

    $deliveryCharge = ($deliveryMethod === "fast") ? 99.00 : (($subtotal >= 499.00) ? 0.00 : 49.00);
    $totalAmount = $subtotal + $deliveryCharge;

    // 4. Generate unique order number
    $orderNumber = "TSA" . strtoupper(substr(uniqid(), -6)) . rand(10, 99);

    // 5. Insert order
    $orderSql = "
        INSERT INTO orders
        (user_id, address_id, order_number, subtotal, delivery_charge, total_amount, payment_method, payment_status, order_status)
        VALUES (?, ?, ?, ?, ?, ?, ?, 'pending', 'confirmed')
    ";
    $orderStmt = $pdo->prepare($orderSql);
    $orderStmt->execute([
        $userId,
        $addressId,
        $orderNumber,
        $subtotal,
        $deliveryCharge,
        $totalAmount,
        $paymentMethod
    ]);
    $orderId = $pdo->lastInsertId();

    // 6. Insert order items & adjust inventory
    $itemSql = "
        INSERT INTO order_items (order_id, product_id, product_name, price, quantity, subtotal)
        VALUES (?, ?, ?, ?, ?, ?)
    ";
    $itemStmt = $pdo->prepare($itemSql);

    $stockStmt = $pdo->prepare("UPDATE products SET stock = GREATEST(0, stock - ?) WHERE id = ?");

    foreach ($cartItems as $item) {
        $prodId = is_numeric($item["id"]) ? (int)$item["id"] : null;
        $prodName = $item["name"] ?? "Product";
        $price = (float)($item["price"] ?? 0);
        $qty = (int)($item["qty"] ?? 1);
        $itemSubtotal = $price * $qty;

        $itemStmt->execute([
            $orderId,
            $prodId,
            $prodName,
            $price,
            $qty,
            $itemSubtotal
        ]);

        if ($prodId) {
            $stockStmt->execute([$qty, $prodId]);
        }
    }

    $pdo->commit();

    $days = ($deliveryMethod === "fast") ? 2 : 5;
    $deliveryDate = new DateTime();
    $deliveryDate->modify("+{$days} days");

    echo json_encode([
        "success" => true,
        "order_id" => $orderId,
        "order_number" => $orderNumber,
        "total" => $totalAmount,
        "payment" => $paymentMethod,
        "delivery_date" => $deliveryDate->format("d M Y"),
        "message" => "Order recorded successfully in MySQL database!"
    ]);

} catch (Exception $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo json_encode([
        "success" => false,
        "message" => "Database order transaction error: " . $e->getMessage()
    ]);
}
