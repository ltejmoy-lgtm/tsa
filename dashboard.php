<?php

session_start();

if (!isset($_SESSION["user_id"])) {

    header("Location: auth/login.php");
    exit;

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>TSA Shop - Dashboard</title>

    <style>

        body {
            font-family: Arial, sans-serif;
            background: #f5f5f5;
            margin: 0;
            padding: 40px;
        }

        .container {
            max-width: 700px;
            margin: auto;

            background: white;

            padding: 40px;

            border-radius: 12px;

            box-shadow: 0 5px 20px rgba(0,0,0,0.1);
        }

        h1 {
            margin-top: 0;
        }

        .info {
            background: #f1f1f1;

            padding: 20px;

            border-radius: 8px;

            margin: 20px 0;
        }

        .logout {
            display: inline-block;

            padding: 12px 20px;

            background: #111;

            color: white;

            text-decoration: none;

            border-radius: 6px;
        }

    </style>

</head>

<body>

<div class="container">

    <h1>
        Welcome to TSA Shop
    </h1>

    <p>
        You are successfully logged in.
    </p>

    <div class="info">

        <p>
            <strong>User ID:</strong>
            <?php echo htmlspecialchars($_SESSION["user_id"]); ?>
        </p>

        <p>
            <strong>Name:</strong>
            <?php echo htmlspecialchars($_SESSION["user_name"]); ?>
        </p>

        <p>
            <strong>Email:</strong>
            <?php echo htmlspecialchars($_SESSION["user_email"]); ?>
        </p>

        <p>
            <strong>Role:</strong>
            <?php echo htmlspecialchars($_SESSION["user_role"]); ?>
        </p>

    </div>

    <a class="logout" href="auth/logout.php">
        Logout
    </a>

</div>

</body>

</html>