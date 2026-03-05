<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$result = '';
$captchaCode = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_input'])) {
    $userInput = trim($_POST['captcha_input']);
    $expectedCode = $_SESSION['captcha_code'] ?? '';
    unset($_SESSION['captcha_code']);

    if ($userInput !== '' && $expectedCode !== '' && $userInput === $expectedCode) {
        $result = '<span class="success">✓ Отлично! Капча пройдена</span>';
    } else {
        $result = '<span class="error">✗ Неверно. Попробуйте снова</span>';
    }
    $_SESSION['captcha_code'] = str_pad((string)random_int(0, 99999), 5, '0', STR_PAD_LEFT);
    $captchaCode = $_SESSION['captcha_code'];
}

if ($captchaCode === '' && (isset($_GET['new_captcha']) || !isset($_SESSION['captcha_code']))) {
    $digits = '0123456789';
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $digits[random_int(0, strlen($digits) - 1)];
    }
    $_SESSION['captcha_code'] = $code;
}

if ($captchaCode === '') {
    $captchaCode = $_SESSION['captcha_code'];
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контрольная точка №2</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(145deg, #f0f2f5 0%, #e6e9f0 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 16px;
        }

        .card {
            background: white;
            border-radius: 28px;
            box-shadow: 0 20px 40px -10px rgba(0, 20, 30, 0.2),
                        0 8px 20px rgba(0, 0, 0, 0.08);
            width: 100%;
            max-width: 480px;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .card-header {
            background: #4361ee;
            padding: 32px 24px;
            text-align: center;
        }

        .card-header h2 {
            color: white;
            font-size: 26px;
            font-weight: 600;
            letter-spacing: -0.5px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .card-header p {
            color: rgba(255,255,255,0.9);
            font-size: 15px;
            margin-top: 8px;
            font-weight: 400;
        }

        .card-body {
            padding: 32px 28px;
        }

        .captcha-area {
            background-image: url(noise.jpg);
            border-radius: 20px;
            padding: 24px;
            margin-bottom: 24px;
            border: 2px solid #eef2f6;
            text-align: center;
        }

        .captcha-label {
            color: #000000;
            font-size: 20px;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1.2px;
            margin-bottom: 16px;
        }

        .captcha-code {
            font-family: 'SF Mono', 'Courier New', monospace;
            font-size: 56px;
            font-weight: 700;
            background: linear-gradient(145deg, #1e293b, #334155);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            letter-spacing: 12px;
            margin: 8px 0 12px;
            filter: drop-shadow(0 4px 6px rgba(0,0,0,0.1));
            transform: rotate(-1deg);
        }

        .refresh-btn {
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 40px;
            padding: 12px 28px;
            font-size: 15px;
            font-weight: 600;
            color: #4361ee;
            cursor: pointer;
            transition: all 0.25s ease;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            text-decoration: none;
            margin-top: 8px;
        }

        .refresh-btn:hover {
            background: #f1f5f9;
            border-color: #4361ee;
            transform: translateY(-2px);
            box-shadow: 0 8px 16px -6px rgba(67, 97, 238, 0.4);
        }

        .refresh-btn:active {
            transform: translateY(0);
        }

        .refresh-icon {
            font-size: 18px;
            line-height: 1;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            color: #1e293b;
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 8px;
        }

        input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 16px;
            transition: all 0.2s ease;
            background: #ffffff;
        }

        input:focus {
            outline: none;
            border-color: #4361ee;
            box-shadow: 0 0 0 4px rgba(67, 97, 238, 0.15);
        }

        input::placeholder {
            color: #94a3b8;
        }

        .submit-btn {
            width: 100%;
            background: #4361ee;
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px 24px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.25s ease;
            box-shadow: 0 10px 20px -8px rgba(67, 97, 238, 0.5);
            letter-spacing: 0.5px;
        }

        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 16px 24px -8px rgba(67, 97, 238, 0.6);
        }

        .submit-btn:active {
            transform: translateY(0);
        }

        .result-area {
            margin-top: 24px;
            padding: 16px 20px;
            border-radius: 16px;
            text-align: center;
            font-weight: 600;
            font-size: 16px;
            animation: slideUp 0.3s ease;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .success {
            background: #dff9e6;
            color: #0a5c2e;
            border-left: 4px solid #10b981;
            display: block;
            padding: 12px 16px;
            border-radius: 12px;
        }

        .error {
            background: #fee9e7;
            color: #b91c1c;
            border-left: 4px solid #ef4444;
            display: block;
            padding: 12px 16px;
            border-radius: 12px;
        }

        .hint {
            text-align: center;
            color: #64748b;
            font-size: 14px;
            margin-top: 20px;
            padding-top: 16px;
            border-top: 2px dashed #e2e8f0;
        }

        .hint i {
            font-style: normal;
            background: #eef2f6;
            padding: 4px 8px;
            border-radius: 30px;
            font-size: 13px;
        }
    </style>
</head>
<body>
    <div class="card">
        <div class="card-header">
            <h2>Проверка безопасности</h2>
            <p>Введите код, чтобы продолжить</p>
        </div>
        
        <div class="card-body">
            <div class="captcha-area">
                <div class="captcha-label">Код подтверждения</div>
                <div class="captcha-code"><?= htmlspecialchars($captchaCode) ?></div>
                
                <a href="?new_captcha=1" class="refresh-btn">
                    <span class="refresh-icon">↻</span>
                    Сгенерировать новый код
                </a>
            </div>

            <form method="POST">
                <div class="form-group">
                    <label for="captcha_input">Введите код с картинки</label>
                    <input 
                        type="text" 
                        id="captcha_input" 
                        name="captcha_input" 
                        placeholder="например: 12345"
                        maxlength="5"
                        autocomplete="off"
                        required
                    >
                </div>
                
                <button type="submit" class="submit-btn">
                    Проверить код
                </button>
            </form>

            <?php if ($result !== ''): ?>
                <div class="result-area">
                    <?= $result ?>
                </div>
            <?php endif; ?>

            <div class="hint">
                <i>Подсказка: код состоит из 5 цифр</i>
            </div>
        </div>
    </div>
</body>
</html>
