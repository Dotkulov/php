<?php
session_start();

$result = '';
$messageClass = '';

$chars = 'ABCDEFGHJKMNPQRSTUVWXYZ23456789';

function generateCaptchaCode($chars) {
    $code = '';
    for ($i = 0; $i < 5; $i++) {
        $code .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $code;
}

if (isset($_GET['ajax_new_captcha'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    exit;
}

if (isset($_GET['new_captcha'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    header('Location: index.php');
    exit;
}

if (!isset($_SESSION['captcha_code'])) {
    $_SESSION['captcha_code'] = generateCaptchaCode($chars);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['captcha_input'])) {
    $userInput = trim($_POST['captcha_input']);
    $expectedCode = $_SESSION['captcha_code'] ?? '';

    if ($userInput !== '' && $expectedCode !== '' && $userInput === $expectedCode) {
        $result = 'Отлично! Капча пройдена';
        $messageClass = 'success';
        $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    } else {
        $result = 'Неверно. Попробуйте снова';
        $messageClass = 'error';
        $_SESSION['captcha_code'] = generateCaptchaCode($chars);
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Капча с разноцветными символами</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Roboto, sans-serif;
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
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.2);
            max-width: 480px;
            width: 100%;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .card-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 32px 24px;
            text-align: center;
        }
        .card-header h2 {
            color: white;
            font-size: 26px;
            margin-bottom: 8px;
        }
        .card-header p {
            color: rgba(255,255,255,0.9);
            font-size: 14px;
        }
        .card-body {
            padding: 32px 28px;
        }
        .captcha-area {
            text-align: center;
            margin-bottom: 24px;
        }
        .captcha-img {
            border-radius: 16px;
            border: 3px solid #e2e8f0;
            background: #f8fafc;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            max-width: 100%;
            height: auto;
            cursor: pointer;
            transition: all 0.3s;
        }
        .captcha-img:hover {
            transform: scale(1.02);
            border-color: #667eea;
        }
        .refresh-btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-top: 16px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 40px;
            padding: 10px 24px;
            text-decoration: none;
            color: #667eea;
            font-weight: 600;
            transition: all 0.2s;
            cursor: pointer;
        }
        .refresh-btn:hover {
            border-color: #667eea;
            transform: translateY(-2px);
            background: #f1f5f9;
        }
        .captcha-hint {
            font-size: 12px;
            color: #64748b;
            text-align: center;
            margin-top: 12px;
        }
        .form-group {
            margin-bottom: 24px;
        }
        label {
            font-weight: 600;
            display: block;
            margin-bottom: 8px;
            color: #1e293b;
        }
        input {
            width: 100%;
            padding: 16px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 16px;
            font-size: 18px;
            transition: all 0.2s;
            font-family: monospace;
            letter-spacing: 2px;
            text-transform: uppercase;
        }
        input:focus {
            border-color: #667eea;
            outline: none;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.15);
        }
        .submit-btn {
            width: 100%;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 16px;
            padding: 16px;
            font-weight: 600;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.2s;
        }
        .submit-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }
        .result-area {
            margin-top: 24px;
            animation: fadeIn 0.3s ease;
        }
        .success {
            background: #dff9e6;
            color: #0a5c2e;
            padding: 14px;
            border-radius: 12px;
            display: block;
            text-align: center;
            font-weight: 600;
            border-left: 4px solid #0a5c2e;
        }
        .error {
            background: #fee9e7;
            color: #b91c1c;
            padding: 14px;
            border-radius: 12px;
            display: block;
            text-align: center;
            font-weight: 600;
            border-left: 4px solid #b91c1c;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
<div class="card">
    <div class="card-header">
        <h2>Проверка безопасности</h2>
        <p>Введите символы с картинки</p>
    </div>
    <div class="card-body">
        <div class="captcha-area">
            <img src="captcha.php?<?= time() ?>" alt="Капча" class="captcha-img" id="captchaImg">
            <br>
            <button type="button" class="refresh-btn" id="refreshBtn">
                ↻ Обновить капчу
            </button>
            <div class="captcha-hint">
                <small>Нажмите на изображение для обновления</small>
            </div>
        </div>

        <form method="POST" id="captchaForm">
            <div class="form-group">
                <label>Введите код с изображения</label>
                <input type="text" name="captcha_input" id="captchaInput" maxlength="5" autocomplete="off" required placeholder="Например: AB3XY">
            </div>
            <button type="submit" class="submit-btn">Проверить</button>
        </form>

        <?php if ($result): ?>
            <div class="result-area">
                <div class="<?= $messageClass ?>"><?= htmlspecialchars($result) ?></div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
    const captchaImg = document.getElementById('captchaImg');
    const refreshBtn = document.getElementById('refreshBtn');
    const captchaForm = document.getElementById('captchaForm');
    const captchaInput = document.getElementById('captchaInput');

    function refreshCaptcha() {
        captchaImg.style.opacity = '0.5';
        fetch('index.php?ajax_new_captcha=1')
            .then(() => {
                captchaImg.src = 'captcha.php?' + Date.now();
                captchaInput.value = '';
                captchaInput.focus();
                captchaImg.onload = () => {
                    captchaImg.style.opacity = '1';
                };
            })
            .catch(() => {
                captchaImg.style.opacity = '1';
            });
    }

    refreshBtn.addEventListener('click', refreshCaptcha);
    captchaImg.addEventListener('click', refreshCaptcha);
    captchaInput.focus();
    
    captchaInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            captchaForm.submit();
        }
    });
    
    captchaInput.addEventListener('input', function() {
        this.value = this.value.toUpperCase();
    });
</script>
</body>
</html>
