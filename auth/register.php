<?php

require_once "../config/database.php";

$message = "";
$message_type = "";

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

    } elseif (strlen($password) < 6) {

        $message = "Password must contain at least 6 characters.";
        $message_type = "error";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";
        $message_type = "error";

    } else {

        $check_sql = "SELECT id FROM users WHERE email = ?";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->execute([$email]);

        if ($check_stmt->fetch()) {

            $message = "An account with this email already exists.";
            $message_type = "error";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $insert_sql = "
                INSERT INTO users
                (name, email, phone, password, role)
                VALUES (?, ?, ?, ?, 'customer')
            ";

            $insert_stmt = $pdo->prepare($insert_sql);

            $insert_stmt->execute([
                $name,
                $email,
                $phone,
                $hashed_password
            ]);

            $message = "Registration successful! You can now login.";
            $message_type = "success";

        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>TSA Shop - Register</title>

    <style>

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .register-container {
            width: 100%;
            max-width: 450px;
            background: white;
            padding: 35px;
            border-radius: 12px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .subtitle {
            text-align: center;
            color: #666;
            margin-bottom: 25px;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
        }

        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 6px;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #333;
        }

        button {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 6px;
            background: #111;
            color: white;
            font-size: 16px;
            cursor: pointer;
        }

        button:hover {
            background: #333;
        }

        .message {
            padding: 12px;
            margin-bottom: 18px;
            border-radius: 6px;
            text-align: center;
        }

        .error {
            background: #ffe5e5;
            color: #b00020;
        }

        .success {
            background: #e5f7e5;
            color: #137333;
        }

        .login-link {
            text-align: center;
            margin-top: 20px;
        }

        .login-link a {
            color: #111;
            font-weight: bold;
            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="register-container">

    <div class="logo">
        TSA SHOP
    </div>

    <div class="subtitle">
        Create your account
    </div>

    <?php if ($message !== ""): ?>

        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST"
          action="register.php">

        <div class="form-group">

            <label for="name">
                Full Name *
            </label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter your full name"
                required
            >

        </div>

        <div class="form-group">

            <label for="email">
                Email *
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
            >

        </div>

        <div class="form-group">

            <label for="phone">
                Phone
            </label>

            <input
                type="tel"
                id="phone"
                name="phone"
                placeholder="Enter your phone number"
            >

        </div>

        <div class="form-group">

            <label for="password">
                Password *
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Minimum 6 characters"
                required
            >

        </div>

        <div class="form-group">

            <label for="confirm_password">
                Confirm Password *
            </label>

            <input
                type="password"
                id="confirm_password"
                name="confirm_password"
                placeholder="Enter password again"
                required
            >

        </div>

        <button type="submit">
            Create Account
        </button>

    </form>

    <div class="login-link">

        Already have an account?

        <a href="login.php">
            Login
        </a>

    </div>

</div>

</body>

</html>