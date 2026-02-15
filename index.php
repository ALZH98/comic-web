<?php
require_once 'db.php';
if (isset($_SESSION['user_id'])) {
    header('Location: home.php');
    exit;
}

// 处理登录
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        header('Location: home.php');
        exit;
    } else {
        $error = '用户名或密码错误';
    }
}

// 处理注册
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';
    
    if (strlen($username) < 3 || strlen($password) < 6) {
        $reg_error = '用户名至少3位，密码至少6位';
    } else {
        // 检查用户名是否存在
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetch()) {
            $reg_error = '用户名已存在';
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
            if ($stmt->execute([$username, $hashed])) {
                $reg_success = '注册成功，请登录';
            } else {
                $reg_error = '注册失败';
            }
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>快看漫画 - 登录注册</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-tabs">
                <div class="auth-tab active" onclick="showTab('login')">登录</div>
                <div class="auth-tab" onclick="showTab('register')">注册</div>
            </div>
            
            <!-- 登录表单 -->
            <div id="login-form" class="auth-form active">
                <form method="POST">
                    <input type="hidden" name="login" value="1">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="用户名" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="密码" required>
                    </div>
                    <?php if (isset($error)): ?>
                        <div class="message error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    <button type="submit" class="btn">登录</button>
                </form>
            </div>
            
            <!-- 注册表单 -->
            <div id="register-form" class="auth-form">
                <form method="POST">
                    <input type="hidden" name="register" value="1">
                    <div class="form-group">
                        <input type="text" name="username" placeholder="用户名" required>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" placeholder="密码" required>
                    </div>
                    <?php if (isset($reg_success)): ?>
                        <div class="message success"><?php echo $reg_success; ?></div>
                    <?php endif; ?>
                    <?php if (isset($reg_error)): ?>
                        <div class="message error"><?php echo $reg_error; ?></div>
                    <?php endif; ?>
                    <button type="submit" class="btn">注册</button>
                </form>
            </div>
        </div>
    </div>

    <script>
    function showTab(tab) {
        document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
        document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
        
        if (tab === 'login') {
            document.querySelector('.auth-tab').classList.add('active');
            document.getElementById('login-form').classList.add('active');
        } else {
            document.querySelectorAll('.auth-tab')[1].classList.add('active');
            document.getElementById('register-form').classList.add('active');
        }
    }
    </script>
</body>
</html>
