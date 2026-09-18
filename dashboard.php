<?php

session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: auth/login.php");
    exit;
}

$userId = $_SESSION["user_id"];
$userName = $_SESSION["user_name"] ?? "Customer";
$userEmail = $_SESSION["user_email"] ?? "";
$userRole = $_SESSION["user_role"] ?? "customer";

// Get user initials for avatar
$initials = "U";
if (!empty($userName)) {
    $parts = explode(" ", trim($userName));
    $initials = strtoupper(substr($parts[0], 0, 1));
    if (isset($parts[1])) {
        $initials .= strtoupper(substr($parts[1], 0, 1));
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSA Shop — My Account</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f1f5f9;
            color: #1e293b;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        /* Navigation */
        .topbar {
            background: #2874f0;
            color: #fff;
            position: sticky;
            top: 0;
            z-index: 10;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
        }
        .nav-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 14px 20px;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .logo {
            color: white;
            text-decoration: none;
            font-size: 26px;
            font-weight: 800;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            gap: 2px;
        }
        .logo span { color: #ffd600; }
        .logo small {
            font-size: 11px;
            font-weight: 500;
            font-style: italic;
            opacity: 0.9;
            margin-left: 3px;
        }
        .nav-links {
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .nav-btn {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 600;
            transition: background 0.15s ease;
        }
        .nav-btn:hover { background: rgba(255, 255, 255, 0.25); }
        .nav-btn-logout {
            background: #ef4444;
        }
        .nav-btn-logout:hover {
            background: #dc2626;
        }

        /* Layout */
        .content-wrap {
            max-width: 1200px;
            margin: 30px auto;
            padding: 0 20px;
            width: 100%;
            flex: 1;
        }
        .dashboard-grid {
            display: grid;
            grid-template-columns: 320px 1fr;
            gap: 24px;
            align-items: start;
        }

        /* Cards */
        .card {
            background: #ffffff;
            border-radius: 12px;
            padding: 26px;
            box-shadow: 0 4px 16px rgba(15, 23, 42, 0.04);
            border: 1px solid #e2e8f0;
        }

        /* Profile Sidebar */
        .profile-card {
            text-align: center;
        }
        .avatar {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2874f0 0%, #1e40af 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            font-weight: 800;
            margin: 0 auto 16px;
            box-shadow: 0 6px 16px rgba(40, 116, 240, 0.3);
        }
        .user-name {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0 0 4px;
        }
        .user-email {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 14px;
            word-break: break-all;
        }
        .role-badge {
            display: inline-block;
            background: #e0f2fe;
            color: #0369a1;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .profile-meta {
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            text-align: left;
        }
        .meta-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            font-size: 13px;
        }
        .meta-label { color: #64748b; font-weight: 500; }
        .meta-val { color: #0f172a; font-weight: 600; }

        /* Orders & Content */
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 12px;
            border-bottom: 1px solid #e2e8f0;
        }
        .section-title {
            font-size: 18px;
            font-weight: 700;
            color: #0f172a;
            margin: 0;
        }
        .order-card {
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 16px;
            background: #fafcff;
            transition: box-shadow 0.15s ease;
        }
        .order-card:hover {
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
        }
        .order-header {
            display: flex;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 10px;
            border-bottom: 1px solid #edf2f7;
            padding-bottom: 12px;
            margin-bottom: 12px;
        }
        .order-id { font-weight: 700; color: #2874f0; font-size: 14px; }
        .order-date { font-size: 12px; color: #64748b; }
        .order-status {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #dcfce7;
            color: #15803d;
            font-size: 12px;
            font-weight: 700;
            padding: 3px 10px;
            border-radius: 20px;
        }
        .order-items {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .order-item-row {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        .order-item-img {
            width: 48px;
            height: 48px;
            border-radius: 6px;
            object-fit: cover;
            background: #e2e8f0;
            border: 1px solid #cbd5e1;
        }
        .order-item-details {
            flex: 1;
        }
        .order-item-name { font-size: 13px; font-weight: 600; color: #1e293b; }
        .order-item-qty { font-size: 12px; color: #64748b; }
        .order-item-price { font-size: 13px; font-weight: 700; color: #0f172a; }
        .order-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 14px;
            padding-top: 12px;
            border-top: 1px dashed #cbd5e1;
            font-size: 13px;
        }
        .order-total { font-size: 15px; font-weight: 800; color: #0f172a; }

        .empty-orders {
            text-align: center;
            padding: 48px 20px;
            color: #64748b;
        }
        .empty-icon {
            font-size: 44px;
            margin-bottom: 12px;
        }
        .btn-shop {
            display: inline-block;
            background: #2874f0;
            color: white;
            text-decoration: none;
            padding: 10px 22px;
            border-radius: 6px;
            font-size: 13px;
            font-weight: 700;
            margin-top: 16px;
            transition: background 0.2s ease;
        }
        .btn-shop:hover { background: #1c5ecc; }

        /* Footer */
        footer {
            background: #1e293b;
            color: #94a3b8;
            text-align: center;
            padding: 20px;
            font-size: 12px;
            margin-top: auto;
        }

        @media (max-width: 860px) {
            .dashboard-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header class="topbar">
        <div class="nav-container">
            <a href="index.php" class="logo">TSA<span>.</span><small>Shop</small></a>
            <div class="nav-links">
                <a href="index.php" class="nav-btn">🛒 Storefront</a>
                <a href="auth/logout.php" class="nav-btn nav-btn-logout">Logout</a>
            </div>
        </div>
    </header>

    <main class="content-wrap">
        <div class="dashboard-grid">
            <!-- Sidebar: User Profile -->
            <div class="card profile-card">
                <div class="avatar"><?php echo htmlspecialchars($initials); ?></div>
                <h2 class="user-name"><?php echo htmlspecialchars($userName); ?></h2>
                <div class="user-email"><?php echo htmlspecialchars($userEmail); ?></div>
                <span class="role-badge"><?php echo htmlspecialchars($userRole); ?></span>

                <div class="profile-meta">
                    <div class="meta-row">
                        <span class="meta-label">Account ID:</span>
                        <span class="meta-val">#<?php echo htmlspecialchars($userId); ?></span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Security:</span>
                        <span class="meta-val" style="color:#16a34a;">Password Protected</span>
                    </div>
                    <div class="meta-row">
                        <span class="meta-label">Status:</span>
                        <span class="meta-val">Active Customer</span>
                    </div>
                </div>

                <a href="index.php" class="btn-shop" style="display:block;margin-top:20px;">Explore Store Deals</a>
            </div>

            <!-- Main: Recent Orders -->
            <div class="card">
                <div class="section-header">
                    <h3 class="section-title">My Orders & Purchases</h3>
                    <span id="orderCountBadge" style="font-size:12px;font-weight:600;color:#64748b;">Loading...</span>
                </div>

                <div id="ordersContainer">
                    <div class="empty-orders">
                        <div class="empty-icon">📦</div>
                        <h3>Scanning for recent orders...</h3>
                        <p>Orders placed during your session will appear right here.</p>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <footer>
        © 2026 TSA Shop — Customer Account Center
    </footer>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const container = document.getElementById("ordersContainer");
            const countBadge = document.getElementById("orderCountBadge");

            // Retrieve order list from localStorage
            let orders = [];
            try {
                // First check array of orders
                const savedOrders = JSON.parse(localStorage.getItem("tsa_orders") || "[]");
                if (Array.isArray(savedOrders) && savedOrders.length > 0) {
                    orders = savedOrders;
                } else {
                    // Fallback to single tsaLastOrder if present
                    const single = JSON.parse(localStorage.getItem("tsaLastOrder") || "null");
                    if (single && single.id) {
                        orders = [single];
                    }
                }
            } catch(e) {
                orders = [];
            }

            if (!orders.length) {
                countBadge.textContent = "0 Orders";
                container.innerHTML = `
                    <div class="empty-orders">
                        <div class="empty-icon">🛍️</div>
                        <h3>No orders found</h3>
                        <p>You haven't placed any orders yet. Discover our top deals and exclusive offers!</p>
                        <a href="index.php" class="btn-shop">Start Shopping Now</a>
                    </div>
                `;
                return;
            }

            countBadge.textContent = `${orders.length} Order${orders.length > 1 ? 's' : ''}`;

            // Render orders reverse-chronologically
            const reversed = [...orders].reverse();
            container.innerHTML = reversed.map(order => {
                const itemsHtml = Array.isArray(order.items) ? order.items.map(item => `
                    <div class="order-item-row">
                        <img src="${item.img || ''}" alt="${item.name || 'Product'}" class="order-item-img" onerror="this.src='https://images.unsplash.com/photo-1523275335684-37898b6baf30?auto=format&fit=crop&w=120&q=80'">
                        <div class="order-item-details">
                            <div class="order-item-name">${item.name || 'Item'}</div>
                            <div class="order-item-qty">Qty: ${item.qty || 1} • ₹${Number(item.price || 0).toLocaleString('en-IN')} each</div>
                        </div>
                        <div class="order-item-price">₹${(Number(item.price || 0) * Number(item.qty || 1)).toLocaleString('en-IN')}</div>
                    </div>
                `).join('') : '<p style="font-size:12px;color:#64748b;">Order details recorded</p>';

                return `
                    <div class="order-card">
                        <div class="order-header">
                            <div>
                                <span class="order-id">Order #${order.id || 'TSA-ORDER'}</span>
                                <div class="order-date">Expected Delivery: ${order.date || '3-5 business days'}</div>
                            </div>
                            <div>
                                <span class="order-status">✓ Confirmed</span>
                            </div>
                        </div>
                        <div class="order-items">
                            ${itemsHtml}
                        </div>
                        <div class="order-footer">
                            <span>Payment: <b>${order.payment || 'Cash on Delivery'}</b></span>
                            <span class="order-total">Total: ₹${Number(order.total || 0).toLocaleString('en-IN')}</span>
                        </div>
                    </div>
                `;
            }).join('');
        });
    </script>
</body>
</html>