<?php

session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: ../dashboard.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

$message = "";
$message_type = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $message = "Please enter both your email address and password.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif (!$db_connected || !$pdo) {

        $message = "Database service is not running. Please start MySQL in your XAMPP Control Panel.";
        $message_type = "error";

    } else {

        try {
            $sql = "SELECT id, name, email, password, role FROM users WHERE email = ?";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user["password"])) {

                session_regenerate_id(true);

                $_SESSION["user_id"] = $user["id"];
                $_SESSION["user_name"] = $user["name"];
                $_SESSION["user_email"] = $user["email"];
                $_SESSION["user_role"] = $user["role"];

                header("Location: ../dashboard.php");
                exit;

            } else {

                $message = "Invalid email address or password.";
                $message_type = "error";

            }
        } catch (PDOException $e) {
            $message = "Authentication query error: " . $e->getMessage();
            $message_type = "error";
        }
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TSA Shop — Sign In</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: linear-gradient(135deg, #f0f4fc 0%, #e6eefb 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            padding: 24px 16px;
            color: #172337;
        }
        .back-link {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            color: #4b5a6f;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 18px;
            transition: color 0.15s ease;
        }
        .back-link:hover { color: #2874f0; }
        .auth-card {
            width: 100%;
            max-width: 440px;
            background: #ffffff;
            padding: 40px 36px;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(23, 44, 78, 0.08), 0 2px 6px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .brand-row {
            text-align: center;
            margin-bottom: 24px;
        }
        .logo {
            font-size: 30px;
            font-weight: 800;
            color: #2874f0;
            letter-spacing: -0.5px;
            text-decoration: none;
            display: inline-block;
        }
        .logo span { color: #ffb703; }
        .logo small {
            font-size: 12px;
            font-weight: 600;
            color: #718096;
            margin-left: 4px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        h1 {
            font-size: 20px;
            font-weight: 700;
            margin: 8px 0 4px;
            color: #111827;
        }
        .subtitle {
            font-size: 13px;
            color: #64748b;
            margin: 0 0 24px;
        }
        .form-group {
            margin-bottom: 18px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }
        .input-wrap {
            position: relative;
        }
        input {
            width: 100%;
            padding: 12px 14px;
            border: 1.5px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: #0f172a;
            background: #fff;
            transition: all 0.2s ease;
        }
        input:focus {
            outline: none;
            border-color: #2874f0;
            box-shadow: 0 0 0 3px rgba(40, 116, 240, 0.15);
        }
        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #64748b;
            font-size: 13px;
            font-weight: 600;
            padding: 4px;
        }
        .toggle-pw:hover { color: #0f172a; }
        button.btn-primary {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #2874f0;
            color: white;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 6px;
            box-shadow: 0 4px 12px rgba(40, 116, 240, 0.25);
        }
        button.btn-primary:hover {
            background: #1c5ecc;
        }
        button.btn-primary:active {
            transform: scale(0.99);
        }
        .message {
            padding: 12px 16px;
            margin-bottom: 20px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.5;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .message.error {
            background: #fef2f2;
            color: #991b1b;
            border: 1px solid #fecaca;
        }
        .message.success {
            background: #f0fdf4;
            color: #166534;
            border: 1px solid #bbf7d0;
        }
        .auth-footer {
            text-align: center;
            margin-top: 24px;
            padding-top: 20px;
            border-top: 1px solid #f1f5f9;
            font-size: 13px;
            color: #64748b;
        }
        .auth-footer a {
            color: #2874f0;
            font-weight: 700;
            text-decoration: none;
        }
        .auth-footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <a href="../index.php" class="back-link">← Back to TSA Store</a>

    <div class="auth-card">
        <div class="brand-row">
            <a href="../index.php" class="logo">TSA<span>.</span><small>Shop</small></a>
            <h1>Welcome Back</h1>
            <p class="subtitle">Sign in to your account to manage orders</p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message <?php echo $message_type; ?>">
                <span><?php echo $message_type === "error" ? "⚠️" : "✓"; ?></span>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" novalidate>
            <div class="form-group">
                <label for="email">Email Address</label>
                <div class="input-wrap">
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="<?php echo htmlspecialchars($email); ?>"
                        required
                        autofocus
                    >
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrap">
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Enter your password"
                        required
                    >
                    <button type="button" class="toggle-pw" onclick="togglePasswordVisibility('password', this)">Show</button>
                </div>
            </div>

            <button type="submit" class="btn-primary">Sign In</button>
        </form>

        <div class="auth-footer">
            Don't have an account? <a href="register.php">Create Account</a>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(fieldId, btn) {
            const input = document.getElementById(fieldId);
            if (input.type === "password") {
                input.type = "text";
                btn.textContent = "Hide";
            } else {
                input.type = "password";
                btn.textContent = "Show";
            }
        }
    </script>
</body>
</html>