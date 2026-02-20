<?php
session_start();

if (!isset($_SESSION['dialogue'])) {
    $_SESSION['dialogue'] = [];
    $_SESSION['countPoka'] = 0;
    $_SESSION['active'] = true;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['message']) && $_SESSION['active']) {
    $message = trim($_POST['message']);
    
    if (!empty($message)) {
        $_SESSION['dialogue'][] = [
            'type' => 'user', 
            'text' => $message, 
            'time' => date('H:i')
        ];
        
        $response = getGrandmaResponse($message, $_SESSION['countPoka'], $_SESSION['active']);
        
        $_SESSION['dialogue'][] = [
            'type' => 'grandma', 
            'text' => $response, 
            'time' => date('H:i')
        ];
    }
}

function getGrandmaResponse($input, &$countPoka, &$active) {
    $upperInput = mb_strtoupper($input);
    
    if ($upperInput === 'ПОКА!') {
        $countPoka++;
        
        if ($countPoka >= 3) {
            $active = false;
            return "ДО СВИДАНИЯ, МИЛЫЙ! ЗАХОДИ ЕЩЁ!";
        } else {
            $year = rand(1930, 1950);
            return "НЕТ, НИ РАЗУ С $year ГОДА!";
        }
    }
    
    $countPoka = 0;
    
    if (strpos($upperInput, 'ПОКА!') !== false) {
        $pokaCount = substr_count($upperInput, 'ПОКА!');
        if ($pokaCount > 1) {
            $year = rand(1930, 1950);
            return "ХИТРЫЙ КАКОЙ! НЕТ, НИ РАЗУ С $year ГОДА!";
        }
    }
    
    if (substr($input, -1) === '!') {
        $year = rand(1930, 1950);
        return "НЕТ, НИ РАЗУ С $year ГОДА!";
    } else {
        return "АСЬ?! ГОВОРИ ГРОМЧЕ, ВНУЧЕК!";
    }
}

if (isset($_POST['reset'])) {
    session_destroy();
    session_start();
    $_SESSION['dialogue'] = [];
    $_SESSION['countPoka'] = 0;
    $_SESSION['active'] = true;
    header('Location: ' . $_SERVER['PHP_SELF']);
    exit;
}

?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Глухая бабушка</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background: #f0f0f0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .container {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            overflow: hidden;
        }

        .header {
            background: #4a6fa5;
            color: white;
            padding: 20px;
            text-align: center;
            border-bottom: 3px solid #3a5a8c;
        }

        .header h1 {
            font-size: 2em;
            margin-bottom: 5px;
        }

        .header p {
            font-size: 1em;
            opacity: 0.9;
        }

        .status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9em;
            margin-top: 10px;
            background: white;
            color: #4a6fa5;
        }

        .status.active {
            background: #4CAF50;
            color: white;
        }

        .status.inactive {
            background: #f44336;
            color: white;
        }

        .chat-container {
            height: 400px;
            overflow-y: auto;
            padding: 20px;
            background: #f9f9f9;
        }

        .message {
            display: flex;
            flex-direction: column;
            margin-bottom: 15px;
        }

        .message.user {
            align-items: flex-end;
        }

        .message.grandma {
            align-items: flex-start;
        }

        .message-content {
            max-width: 70%;
            padding: 10px 15px;
            border-radius: 15px;
            word-wrap: break-word;
        }

        .message.user .message-content {
            background: #4a6fa5;
            color: white;
            border-bottom-right-radius: 5px;
        }

        .message.grandma .message-content {
            background: #e9e9e9;
            color: #333;
            border-bottom-left-radius: 5px;
        }

        .message-meta {
            font-size: 0.8em;
            color: #666;
            margin-top: 5px;
            padding: 0 5px;
        }

        .message.user .message-meta {
            text-align: right;
        }

        .welcome-message {
            background: #e9e9e9;
            padding: 15px;
            border-radius: 10px;
            margin-bottom: 20px;
            text-align: center;
            color: #333;
            font-style: italic;
        }

        .input-area {
            padding: 20px;
            background: white;
            border-top: 1px solid #ddd;
        }

        .input-form {
            display: flex;
            gap: 10px;
            margin-bottom: 10px;
        }

        .message-input {
            flex: 1;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 25px;
            font-size: 1em;
            outline: none;
            transition: border-color 0.3s;
        }

        .message-input:focus {
            border-color: #4a6fa5;
        }

        .message-input:disabled {
            background: #f5f5f5;
            cursor: not-allowed;
        }

        .send-btn {
            padding: 12px 30px;
            background: #4a6fa5;
            color: white;
            border: none;
            border-radius: 25px;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.3s;
        }

        .send-btn:hover:not(:disabled) {
            background: #3a5a8c;
        }

        .send-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
        }

        .reset-btn {
            width: 100%;
            padding: 10px;
            background: transparent;
            color: #4a6fa5;
            border: 2px solid #4a6fa5;
            border-radius: 25px;
            font-size: 0.9em;
            cursor: pointer;
            transition: all 0.3s;
        }

        .reset-btn:hover {
            background: #4a6fa5;
            color: white;
        }

        .rules {
            padding: 15px 20px;
            background: #f5f5f5;
            border-top: 1px solid #ddd;
        }

        .rules h3 {
            font-size: 1em;
            color: #333;
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .rules ul {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
        }

        .rules li {
            font-size: 0.9em;
            color: #666;
            padding-left: 15px;
            position: relative;
        }

        .rules li::before {
            content: '•';
            position: absolute;
            left: 0;
            color: #4a6fa5;
        }

        .rules li strong {
            color: #4a6fa5;
            font-weight: 600;
        }

        @media (max-width: 500px) {
            .input-form {
                flex-direction: column;
            }
            
            .rules ul {
                flex-direction: column;
                gap: 8px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Глухая бабушка</h1>
            <p>беседа по душам</p>
            <div class="status <?php echo $_SESSION['active'] ? 'active' : 'inactive'; ?>">
                <?php echo $_SESSION['active'] ? 'бабушка слушает' : 'разговор окончен'; ?>
            </div>
        </div>
        
        <div class="chat-container" id="chatContainer">
            <div class="welcome-message">
                ЧЕГО СКАЗАТЬ-ТО ХОТЕЛ, МИЛОК?!
            </div>
            
            <?php foreach ($_SESSION['dialogue'] as $msg): ?>
                <div class="message <?php echo $msg['type']; ?>">
                    <div class="message-content">
                        <?php echo htmlspecialchars($msg['text']); ?>
                    </div>
                    <div class="message-meta">
                        <?php echo $msg['type'] === 'user' ? 'Вы' : 'Бабушка'; ?> • <?php echo $msg['time']; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="input-area">
            <form method="POST" class="input-form" id="messageForm">
                <input type="text" 
                       name="message" 
                       class="message-input" 
                       placeholder="Напишите что-нибудь..." 
                       autocomplete="off"
                       <?php echo !$_SESSION['active'] ? 'disabled' : ''; ?>
                       required>
                <button type="submit" class="send-btn" <?php echo !$_SESSION['active'] ? 'disabled' : ''; ?>>
                    Сказать
                </button>
            </form>
            
            <form method="POST">
                <button type="submit" name="reset" class="reset-btn">
                    Начать заново
                </button>
            </form>
        </div>
        
        <div class="rules">
            <h3>Правила</h3>
            <ul>
                <li><strong>Тихо</strong> — без !, бабушка не слышит</li>
                <li><strong>Громко</strong> — с !, бабушка отвечает годом</li>
                <li><strong>Пока!</strong> — 3 раза подряд для прощания</li>
                <li><strong>Хитрость</strong> — несколько "пока!" в одной строке не работают</li>
            </ul>
        </div>
    </div>
    
    <script>
        const chatContainer = document.getElementById('chatContainer');
        chatContainer.scrollTop = chatContainer.scrollHeight;
        
        document.getElementById('messageForm').addEventListener('submit', function() {
            setTimeout(() => {
                document.querySelector('.message-input').value = '';
            }, 10);
        });
        
        if (document.querySelector('.message-input:not(:disabled)')) {
            document.querySelector('.message-input').focus();
        }
        
    </script>
</body>
</html>
