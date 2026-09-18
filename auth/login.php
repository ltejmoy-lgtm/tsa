<?php

session_start();

require_once "../config/database.php";

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = trim($_POST["email"] ?? "");
    $password = $_POST["password"] ?? "";

    if ($email === "" || $password === "") {

        $message = "Please enter your email and password.";
        $message_type = "error";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "Please enter a valid email address.";
        $message_type = "error";

    } else {

        $sql = "SELECT id, name, email, password, role
                FROM users
                WHERE email = ?";

        $stmt = $pdo->prepare($sql);
        $stmt->execute([$email]);

        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["user_name"] = $user["name"];
            $_SESSION["user_email"] = $user["email"];
            $_SESSION["user_role"] = $user["role"];

            header("Location: ../index.php");
            exit;

        } else {

            $message = "Invalid email or password.";
            $message_type = "error";

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

    <title>TSA Shop - Login</title>

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

        .login-container {
            width: 100%;
            max-width: 430px;

            background: white;

            padding: 35px;

            border-radius: 12px;

            box-shadow:
                0 5px 25px rgba(0, 0, 0, 0.1);
        }

        .logo {
            text-align: center;

            font-size: 32px;

            font-weight: bold;

            margin-bottom: 8px;
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

        .register-link {
            text-align: center;

            margin-top: 20px;
        }

        .register-link a {
            color: #111;

            font-weight: bold;

            text-decoration: none;
        }

    </style>

</head>

<body>

<div class="login-container">

    <div class="logo">
        TSA SHOP
    </div>

    <div class="subtitle">
        Login to your account
    </div>

    <?php if ($message !== ""): ?>

        <div class="message <?php echo $message_type; ?>">
            <?php echo htmlspecialchars($message); ?>
        </div>

    <?php endif; ?>

    <form method="POST" action="login.php">

        <div class="form-group">

            <label for="email">
                Email
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

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >

        </div>

        <button type="submit">
            Login
        </button>

    </form>

    <div class="register-link">

        Don't have an account?

        <a href="register.php">
            Create Account
        </a>

    </div>

</div>

</body>

</html>