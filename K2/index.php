<?php
session_start();

$message = '';
$message_class = '';
$show_result = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['captcha_input']) && isset($_SESSION['captcha'])) {
        $user_input = trim(strtoupper($_POST['captcha_input']));
        $correct_code = $_SESSION['captcha'];
        $show_result = true;
        
        if ($user_input === $correct_code) {
            $message = 'УСПЕХ! CAPTCHA введена правильно!';
            $message_class = 'success';
            unset($_SESSION['captcha']);
        } else {
            $message = 'ОШИБКА! Неправильный код. Попробуйте снова.';
            $message_class = 'error';
            unset($_SESSION['captcha']);
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Доткулов</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif;
            background: linear-gradient(145deg, #0f172a 0%, #1e293b 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 16px;
        }
        
        .container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 32px;
            border-radius: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
            max-width: 560px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        h1 {
            color: #0f172a;
            margin-bottom: 28px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        
        h1::before, h1::after {
            font-size: 24px;
            opacity: 0.8;
        }
        
        .captcha-container {
            background: #f8fafc;
            padding: 24px;
            border-radius: 24px;
            margin-bottom: 28px;
            border: 2px solid #e2e8f0;
            box-shadow: inset 0 2px 4px rgba(0, 0, 0, 0.02);
        }
        
        .captcha-image {
            width: 100%;
            height: auto;
            border-radius: 16px;
            margin-bottom: 16px;
            cursor: pointer;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
            border: 2px solid #cbd5e1;
        }
        
        .captcha-image:hover {
            transform: scale(1.01);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.15);
            border-color: #94a3b8;
        }
        
        .captcha-image:active {
            transform: scale(0.99);
        }
        
        .captcha-hint {
            color: #475569;
            font-size: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            background: #ffffff;
            padding: 10px 16px;
            border-radius: 40px;
            border: 1px solid #e2e8f0;
            width: fit-content;
            margin: 0 auto;
        }
        
        .captcha-hint::before {
            content: "💡";
            font-size: 16px;
        }
        
        .form-group {
            margin-bottom: 24px;
        }
        
        label {
            display: block;
            margin-bottom: 10px;
            color: #1e293b;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 0.3px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 18px 20px;
            border: 2px solid #e2e8f0;
            border-radius: 18px;
            font-size: 32px;
            letter-spacing: 10px;
            text-align: center;
            transition: all 0.2s;
            background: #ffffff;
            text-transform: uppercase;
            font-weight: 600;
            color: #0f172a;
            box-shadow: 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
        
        input[type="text"]:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.15);
        }
        
        input[type="text"]::placeholder {
            color: #94a3b8;
            font-size: 16px;
            letter-spacing: normal;
            font-weight: 400;
            opacity: 0.6;
        }
        
        .submit-btn {
            width: 100%;
            padding: 18px;
            background: linear-gradient(145deg, #2563eb, #1d4ed8);
            color: white;
            border: none;
            border-radius: 18px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 10px 15px -3px rgba(37, 99, 235, 0.3);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .submit-btn:hover {
            background: linear-gradient(145deg, #1d4ed8, #2563eb);
            transform: translateY(-2px);
            box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4);
        }
        
        .submit-btn:active {
            transform: translateY(1px);
            box-shadow: 0 5px 10px -3px rgba(37, 99, 235, 0.3);
        }
        
        .submit-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }
        
        .result-container {
            margin-top: 28px;
            padding: 24px;
            border-radius: 24px;
            animation: slideIn 0.4s cubic-bezier(0.16, 1, 0.3, 1);
            border: 2px solid transparent;
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(-15px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        .result-container.success {
            background: linear-gradient(145deg, #f0fdf4, #dcfce7);
            border-color: #4ade80;
        }
        
        .result-container.error {
            background: linear-gradient(145deg, #fef2f2, #fee2e2);
            border-color: #f87171;
        }
        
        .result-title {
            font-size: 22px;
            font-weight: 700;
            margin-bottom: 18px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .result-title.success {
            color: #166534;
        }
        
        .result-title.error {
            color: #991b1b;
        }
        
        .result-title::before {
            font-size: 28px;
        }
        
        .result-title.success::before {
            content: "✅";
        }
        
        .result-title.error::before {
            content: "❌";
        }
        
        .result-details {
            background: rgba(255, 255, 255, 0.8);
            backdrop-filter: blur(4px);
            padding: 18px;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.5);
        }
        
        .detail-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
            padding: 10px;
            background: white;
            border-radius: 14px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
        }
        
        .detail-label {
            font-weight: 600;
            color: #334155;
            font-size: 15px;
        }
        
        .detail-value {
            font-family: 'Courier New', monospace;
            font-size: 22px;
            font-weight: 700;
            padding: 6px 16px;
            border-radius: 12px;
            background: #f1f5f9;
            color: #0f172a;
        }
        
        .detail-value.correct {
            background: #4ade80;
            color: #14532d;
        }
        
        .detail-value.incorrect {
            background: #f87171;
            color: #7f1d1d;
        }
        
        .comparison-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 16px;
        }
        
        .comparison-item {
            text-align: center;
            padding: 14px;
            border-radius: 16px;
            background: white;
            font-weight: 500;
        }
        
        .comparison-item.success {
            background: #bbf7d0;
            color: #14532d;
        }
        
        .comparison-item.error {
            background: #fecaca;
            color: #7f1d1d;
        }
        
        .comparison-icon {
            font-size: 24px;
            margin-bottom: 6px;
        }
        
        .footer-message {
            text-align: center;
            margin-top: 20px;
            padding: 14px;
            border-radius: 40px;
            font-weight: 600;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        
        .footer-message.success {
            background: #bbf7d0;
            color: #14532d;
        }
        
        .footer-message.error {
            background: #fecaca;
            color: #7f1d1d;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>CAPTCHA</h1>
        
        <div class="captcha-container">
            <img src="captcha.php?t=<?php echo time(); ?>" 
                 alt="CAPTCHA" 
                 class="captcha-image" 
                 id="captchaImage"
                 onclick="refreshCaptcha()"
                 title="Нажмите для обновления">
            
            <div class="captcha-hint">
                Нажмите на картинку для обновления
            </div>
        </div>
        
        <form method="POST" action="" id="captchaForm">
            <div class="form-group">
                <label for="captcha_input">
                    Введите текст с изображения:
                </label>
                <div class="input-wrapper">
                    <input type="text" 
                           id="captcha_input" 
                           name="captcha_input" 
                           placeholder="Ввыедите капчу"
                           required
                           autocomplete="off"
                           maxlength="5"
                           autofocus>
                </div>
            </div>
            
            <button type="submit" class="submit-btn" id="submitBtn">
                Подтвердить
            </button>
        </form>
        
        <?php if ($show_result): ?>
            <div class="result-container <?php echo $message_class; ?>">
                <div class="result-title <?php echo $message_class; ?>">
                    <?php echo $message; ?>
                </div>
                
                <div class="result-details">
                    <div class="detail-item">
                        <span class="detail-label">Ваш код:</span>
                        <span class="detail-value <?php echo $message_class === 'success' ? 'correct' : 'incorrect'; ?>">
                            <?php echo htmlspecialchars($_POST['captcha_input'] ?? ''); ?>
                        </span>
                    </div>
                    
                    <div class="detail-item">
                        <span class="detail-label">Правильный код:</span>
                        <span class="detail-value">
                            <?php echo htmlspecialchars($correct_code ?? '???'); ?>
                        </span>
                    </div>
                    
                    <div class="comparison-grid">
                        <div class="comparison-item <?php echo $message_class; ?>">
                            <div class="comparison-icon"><?php echo $message_class === 'success' ? '✓' : '✗'; ?></div>
                            <div><?php echo $message_class === 'success' ? 'Совпадение' : 'Ошибка'; ?></div>
                        </div>
                        <div class="comparison-item">
                            <div class="comparison-icon">📏</div>
                            <div><?php echo strlen($_POST['captcha_input'] ?? ''); ?>/<?php echo strlen($correct_code ?? '0'); ?></div>
                        </div>
                    </div>
                </div>
                
                <div class="footer-message <?php echo $message_class; ?>">
                    <?php if($message_class === 'success'): ?>
                        Доступ разрешен ✅
                    <?php else: ?>
                        Попробуйте еще раз 🔄
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <script>
        function refreshCaptcha() {
            const img = document.getElementById('captchaImage');
            img.src = 'captcha.php?t=' + new Date().getTime();
            
            const input = document.getElementById('captcha_input');
            input.value = '';
            input.focus();
            
            img.style.opacity = '0.5';
            setTimeout(() => img.style.opacity = '1', 150);
        }
        
        document.getElementById('captcha_input').addEventListener('input', function(e) {
            this.value = this.value.toUpperCase().replace(/[^A-Z2-9]/g, '');
        });
        
        document.getElementById('captchaForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('submitBtn');
            btn.disabled = true;
            btn.innerHTML = 'Проверка...';
        });
        
        <?php if ($show_result): ?>
        setTimeout(() => {
            const result = document.querySelector('.result-container');
            if (result) {
                result.style.transition = 'opacity 0.4s';
                result.style.opacity = '0';
                setTimeout(() => result.remove(), 400);
            }
        }, 5000);
        <?php endif; ?>
    </script>
</body>
</html>
