<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $ip = $_SERVER['REMOTE_ADDR'];

    $loginData = json_decode(file_get_contents('login.json'), true) ?? [];
    $loginData[] = ["ip" => $ip, "name" => $name];

    file_put_contents('login.json', json_encode($loginData));

    header("Location: index.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style>
        body {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background: linear-gradient(135deg, #1e3c72, #2a5298);
            color: #ffffff;
            font-family: Arial, sans-serif;
        }
        #loginForm {
            display: flex;
            flex-direction: column;
            align-items: center;
            background: rgba(28, 28, 30, 0.9);
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            text-align: center;
            max-width: 400px;
            width: 100%;
        }
        #loginForm h2 {
            color: #00bfff;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border-radius: 8px;
            border: 1px solid #555;
            background-color: #2c2c2e;
            color: #ffffff;
            font-size: 1em;
            outline: none;
            transition: all 0.3s ease;
        }
        input[type="text"]:focus {
            border-color: #00bfff;
            box-shadow: 0 0 10px rgba(0, 191, 255, 0.5);
        }
        button {
            width: 100%;
            padding: 12px;
            margin-top: 20px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(45deg, #00bfff, #1e90ff);
            color: #ffffff;
            font-size: 1.1em;
            font-weight: bold;
            cursor: pointer;
            transition: transform 0.2s ease, box-shadow 0.3s ease;
        }
        button:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 10px rgba(0, 191, 255, 0.5);
        }
        button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <form id="loginForm" method="POST">
        <h1>Live chat</h1>
        <h2>Login</h2>
        <input type="text" name="name" placeholder="Enter your name" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>