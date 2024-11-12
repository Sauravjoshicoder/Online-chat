<?php
session_start();

// Load JSON files
$loginData = json_decode(file_get_contents('login.json'), true);
$msgData = json_decode(file_get_contents('msg.json'), true) ?? [];

// Get User IP
$userIP = $_SERVER['REMOTE_ADDR'];

// Check if IP is logged in login.json
$isLoggedIn = false;
$userName = '';
foreach ($loginData as $user) {
    if ($user['ip'] == $userIP) {
        $isLoggedIn = true;
        $userName = $user['name'];
        break;
    }
}

// Redirect to login page if IP not found
if (!$isLoggedIn) {
    header("Location: login.php");
    exit();
}

// Save new message
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message = $_POST['message'];
    if (!empty($message)) {
        $newMsg = ["name" => $userName, "msg" => $message, "timestamp" => time()];
        $msgData[] = $newMsg;
        file_put_contents('msg.json', json_encode($msgData));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Room</title>
    <style>
        body {
            background-color: #121212;
            color: #ffffff;
            font-family: Arial, sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 0;
            height: 100vh;
            overflow: hidden;
        }
        #nameDisplay {
            font-size: 1.3em;
            font-weight: bold;
            margin: 20px;
            color: #00bfff;
        }
        #chatBox {
            width: 85%;
            max-width: 700px;
            height: 70vh;
            border: 1px solid #333;
            background-color: #1c1c1e;
            overflow-y: auto;
            padding: 15px;
            margin-top: 10px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }
        .message {
            background-color: #333;
            color: #00bfff;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 12px;
        }
        .message span {
            color: #aaaaaa;
            font-size: 0.8em;
            display: block;
            margin-top: 5px;
        }
        #inputBox {
            display: flex;
            position: fixed;
            bottom: 20px;
            width: 85%;
            max-width: 700px;
            justify-content: center;
            align-items: center;
            padding: 10px 0;
            background-color: #121212;
            border-top: 1px solid #333;
        }
        #messageInput {
            flex: 1;
            padding: 14px;
            border: none;
            border-radius: 10px;
            background-color: #2c2c2e;
            color: #ffffff;
            font-size: 1em;
            margin-right: 10px;
            outline: none;
            transition: border 0.3s, box-shadow 0.3s;
        }
        #messageInput:focus {
            border: 1px solid #00bfff;
            box-shadow: 0 0 8px rgba(0, 191, 255, 0.5);
        }
        #sendButton {
            padding: 12px 20px;
            background: linear-gradient(45deg, #00bfff, #1e90ff);
            color: #ffffff;
            font-weight: bold;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background 0.3s, transform 0.2s;
        }
        #sendButton:hover {
            background: linear-gradient(45deg, #1e90ff, #00bfff);
            transform: scale(1.05);
        }
        #sendButton:active {
            transform: scale(0.95);
        }
    </style>
</head>
<body>

    <div id="nameDisplay">Welcome, <?php echo htmlspecialchars($userName); ?></div>

    <div id="chatBox">
        <?php
        foreach ($msgData as $msg) {
            echo "<div class='message'><strong>" . htmlspecialchars($msg['name']) . ":</strong> " . htmlspecialchars($msg['msg']) . "<br><span>" . date("H:i:s", $msg['timestamp']) . "</span></div>";
        }
        ?>
    </div>

    <div id="inputBox">
        <form action="index.php" method="POST" style="display: flex; width: 100%;" onsubmit="sendNotification()">
            <input type="text" id="messageInput" name="message" placeholder="Type your message..." required>
            <button type="submit" id="sendButton">Send</button>
        </form>
    </div>

    <script>
        const chatBox = document.getElementById('chatBox');
        chatBox.scrollTop = chatBox.scrollHeight;  // Auto-scroll to the bottom on load

        // Request permission for notifications
        if (Notification.permission !== "granted") {
            Notification.requestPermission();
        }

        // Show notification after form submit
        function sendNotification() {
            const messageContent = document.getElementById('messageInput').value;

            if (Notification.permission === "granted" && messageContent) {
                const notification = new Notification("New Message Sent", {
                    body: messageContent,
                    icon: 'https://via.placeholder.com/64' // Placeholder icon, replace if needed
                });
                
                // Reset notification after sending message
                setTimeout(() => notification.close(), 5000); // Auto close after 5 seconds
            }
        }
    </script>
</body>
</html>