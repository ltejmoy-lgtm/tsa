<?php

require_once __DIR__ . "/config/database.php";

$tables = [];
$counts = [];

if ($db_connected && $pdo) {
    try {
        $stmt = $pdo->query("SHOW TABLES");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);

        foreach ($tables as $t) {
            $cntStmt = $pdo->query("SELECT COUNT(*) FROM `{$t}`");
            $counts[$t] = $cntStmt->fetchColumn();
        }
    } catch (Exception $e) {
        $db_error = $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSA Shop - Database Diagnostic</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: #f4f6fa;
            color: #1a2538;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .diag-card {
            background: #fff;
            max-width: 600px;
            width: 100%;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.06);
            padding: 36px;
            border: 1px solid #e5e9f0;
        }
        .header {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
        }
        .badge-status {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }
        .status-ok { background: #e8f5e9; color: #2e7d32; }
        .status-fail { background: #ffebee; color: #c62828; }
        h1 { margin: 0; font-size: 24px; font-weight: 800; color: #152542; }
        p { color: #5a6a85; font-size: 14px; line-height: 1.6; }
        .details-box {
            background: #f8fafc;
            border: 1px solid #e8edf4;
            border-radius: 8px;
            padding: 16px;
            margin: 20px 0;
            font-size: 13px;
        }
        .details-box table { width: 100%; border-collapse: collapse; }
        .details-box td { padding: 6px 0; }
        .details-box td:first-child { color: #6b7280; font-weight: 600; width: 40%; }
        .btn {
            display: inline-block;
            background: #2874f0;
            color: white;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 600;
            margin-right: 10px;
            transition: 0.2s;
        }
        .btn:hover { background: #1c5ecc; }
        .btn-secondary { background: #e2e8f0; color: #334155; }
        .btn-secondary:hover { background: #cbd5e1; }
    </style>
</head>
<body>
<div class="diag-card">
    <div class="header">
        <span class="badge-status <?php echo $db_connected ? 'status-ok' : 'status-fail'; ?>">
            <?php echo $db_connected ? '● Connected' : '✕ Disconnected'; ?>
        </span>
    </div>
    <h1>TSA Shop Database Diagnostic</h1>
    <p>This utility checks whether your local MySQL server is running and accessible to TSA Shop.</p>

    <div class="details-box">
        <table>
            <tr><td>Host:</td><td><strong>localhost</strong></td></tr>
            <tr><td>Database:</td><td><strong>tsa_shop</strong></td></tr>
            <tr><td>PHP Version:</td><td><strong><?php echo PHP_VERSION; ?></strong></td></tr>
            <tr><td>Status:</td><td><strong><?php echo $db_connected ? "Operational" : "Not connected"; ?></strong></td></tr>
            <?php if (!$db_connected && $db_error): ?>
                <tr><td>Error Notice:</td><td style="color:#c62828"><?php echo htmlspecialchars($db_error); ?></td></tr>
            <?php endif; ?>
        </table>
    </div>

    <?php if ($db_connected): ?>
        <h3>Discovered Tables:</h3>
        <?php if (!empty($tables)): ?>
            <ul>
                <?php foreach ($tables as $t): ?>
                    <li><strong><?php echo htmlspecialchars($t); ?></strong> — <?php echo $counts[$t] ?? 0; ?> records</li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Database exists but has no tables yet. You can import <code>database.sql</code> via phpMyAdmin to seed it.</p>
        <?php endif; ?>
    <?php else: ?>
        <p style="color:#b91c1c;">
            <strong>To connect MySQL:</strong> Open XAMPP Control Panel and click <strong>Start</strong> next to MySQL, then refresh this page.
        </p>
    <?php endif; ?>

    <div style="margin-top: 24px;">
        <a href="index.php" class="btn">Return to Store</a>
        <a href="test_db.php" class="btn btn-secondary">Re-run Test</a>
    </div>
</div>
</body>
</html>