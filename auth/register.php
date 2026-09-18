<?php

session_start();

if (isset($_SESSION["user_id"])) {
    header("Location: ../dashboard.php");
    exit;
}

require_once __DIR__ . "/../config/database.php";

$message = "";
$message_type = "";
$name = "";
$email = "";
$phone = "";
$registration_success = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $phone = trim($_POST["phone"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if ($name === "" || $email === "" || $password === "") {

        $message = "Please fill in all required fields.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } elseif ($phone !== "" && !preg_match('/^[0-9]{10}$/', preg_replace('/[^0-9]/', '', $phone))) {

        $message = "Please enter a valid 10-digit mobile number.";
        $message_type = "error";

    } elseif (strlen($password) < 6) {

        $message = "Password must contain at least 6 characters.";
        $message_type = "error";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match. Please re-enter.";
        $message_type = "error";

    } elseif (!$db_connected || !$pdo) {

        $message = "Database service is not running. Please start MySQL in your XAMPP Control Panel.";
        $message_type = "error";

    } else {

        try {
            $check_sql = "SELECT id FROM users WHERE email = ?";
            $check_stmt = $pdo->prepare($check_sql);
            $check_stmt->execute([$email]);

            if ($check_stmt->fetch()) {

                $message = "An account with this email address already exists.";
                $message_type = "error";

            } else {

                $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                $insert_sql = "
                    INSERT INTO users (name, email, phone, password, role)
                    VALUES (?, ?, ?, ?, 'customer')
                ";

                $insert_stmt = $pdo->prepare($insert_sql);
                $clean_phone = preg_replace('/[^0-9]/', '', $phone);

                $insert_stmt->execute([
                    $name,
                    $email,
                    $clean_phone,
                    $hashed_password
                ]);

                $registration_success = true;
                $message = "Your account has been created successfully!";
                $message_type = "success";

            }
        } catch (PDOException $e) {
            $message = "Registration error: " . $e->getMessage();
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
    <title>TSA Shop — Create Account</title>
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
            padding: 30px 16px;
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
            max-width: 480px;
            background: #ffffff;
            padding: 38px 36px;
            border-radius: 16px;
            box-shadow: 0 12px 35px rgba(23, 44, 78, 0.08), 0 2px 6px rgba(0, 0, 0, 0.02);
            border: 1px solid rgba(226, 232, 240, 0.8);
        }
        .brand-row {
            text-align: center;
            margin-bottom: 22px;
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
            margin: 0 0 20px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        label {
            display: block;
            margin-bottom: 6px;
            font-size: 13px;
            font-weight: 600;
            color: #334155;
        }
        label span.req { color: #dc2626; }
        .input-wrap {
            position: relative;
        }
        input {
            width: 100%;
            padding: 11px 14px;
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
            margin-top: 8px;
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
            margin-top: 22px;
            padding-top: 18px;
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
        .success-box {
            text-align: center;
            padding: 20px 0 10px;
        }
        .success-icon {
            width: 60px;
            height: 60px;
            background: #16a34a;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 16px;
        }
    </style>
</head>
<body>

    <a href="../index.php" class="back-link">← Back to TSA Store</a>

    <div class="auth-card">
        <div class="brand-row">
            <a href="../index.php" class="logo">TSA<span>.</span><small>Shop</small></a>
            <h1>Create Account</h1>
            <p class="subtitle">Join TSA Shop for seamless shopping and tracking</p>
        </div>

        <?php if ($message !== ""): ?>
            <div class="message <?php echo $message_type; ?>">
                <span><?php echo $message_type === "error" ? "⚠️" : "✓"; ?></span>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <?php if ($registration_success): ?>
            <div class="success-box">
                <div class="success-icon">✓</div>
                <h3>Welcome to TSA Shop!</h3>
                <p style="color:#64748b;font-size:14px;margin-bottom:24px;">
                    Your account has been registered with <strong><?php echo htmlspecialchars($email); ?></strong>. You can now log in to start shopping.
                </p>
                <a href="login.php" class="btn-primary" style="display:block;text-align:center;text-decoration:none;padding:12px;">Sign In to Account</a>
            </div>
        <?php else: ?>
            <form method="POST" action="register.php" novalidate>
                <div class="form-group">
                    <label for="name">Full Name <span class="req">*</span></label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        placeholder="e.g. Rahul Sharma"
                        value="<?php echo htmlspecialchars($name); ?>"
                        required
                        autofocus
                    >
                </div>

                <div class="form-group">
                    <label for="email">Email Address <span class="req">*</span></label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        placeholder="you@example.com"
                        value="<?php echo htmlspecialchars($email); ?>"
                        required
                    >
                </div>

                <div class="form-group">
                    <label for="phone">Mobile Number</label>
                    <input
                        type="tel"
                        id="phone"
                        name="phone"
                        placeholder="10-digit mobile number"
                        maxlength="10"
                        value="<?php echo htmlspecialchars($phone); ?>"
                    >
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimum 6 characters"
                            required
                        >
                        <button type="button" class="toggle-pw" onclick="togglePasswordVisibility('password', this)">Show</button>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm_password">Confirm Password <span class="req">*</span></label>
                    <div class="input-wrap">
                        <input
                            type="password"
                            id="confirm_password"
                            name="confirm_password"
                            placeholder="Re-enter your password"
                            required
                        >
                        <button type="button" class="toggle-pw" onclick="togglePasswordVisibility('confirm_password', this)">Show</button>
                    </div>
                </div>

                <button type="submit" class="btn-primary">Create Account</button>
            </form>

            <div class="auth-footer">
                Already have an account? <a href="login.php">Sign In</a>
            </div>
        <?php endif; ?>
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