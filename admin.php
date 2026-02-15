<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// 处理添加漫画
$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_comic'])) {
    $title = $_POST['title'] ?? '';
    $cover = $_POST['cover'] ?? '';
    $category = $_POST['category'] ?? '';
    $author = $_POST['author'] ?? '';
    $description = $_POST['description'] ?? '';
    
    if ($title && $cover && $category && $author) {
        $stmt = $pdo->prepare("INSERT INTO comics (title, cover, category, author, description) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$title, $cover, $category, $author, $description])) {
            $message = '添加成功！';
            $message_type = 'success';
        } else {
            $message = '添加失败';
            $message_type = 'error';
        }
    } else {
        $message = '请填写所有必填字段';
        $message_type = 'error';
    }
}

// 获取所有漫画
$stmt = $pdo->query("SELECT * FROM comics ORDER BY created_at DESC");
$comics = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>后台管理</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="home.php" class="logo">📚 快看漫画 - 后台</a>
            <div class="nav-menu">
                <a href="home.php">返回首页</a>
            </div>
            <div class="user-info">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="logout">退出</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="admin-container">
            <!-- 侧边栏 -->
            <div class="sidebar">
                <h3>管理菜单</h3>
                <ul class="sidebar-menu">
                    <li><a href="#" class="active">📝 添加漫画</a></li>
                    <li><a href="#">📋 所有漫画</a></li>
                    <li><a href="#">👥 用户管理</a></li>
                </ul>
            </div>
            
            <!-- 内容区 -->
            <div class="content">
                <?php if ($message): ?>
                <div class="message <?php echo $message_type; ?>">
                    <?php echo $message; ?>
                </div>
                <?php endif; ?>

                <h2>添加新漫画</h2>
                <form method="POST">
                    <input type="hidden" name="add_comic" value="1">
                    
                    <div class="form-group">
                        <label>标题</label>
                        <input type="text" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label>封面图片URL</label>
                        <input type="text" name="cover" required placeholder="https://picsum.photos/300/400?random=...">
                    </div>
                    
                    <div class="form-group">
                        <label>分类</label>
                        <select name="category" required>
                            <option value="">请选择</option>
                            <option value="玄幻">玄幻</option>
                            <option value="搞笑">搞笑</option>
                            <option value="古风">古风</option>
                            <option value="科幻">科幻</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label>作者</label>
                        <input type="text" name="author" required>
                    </div>
                    
                    <div class="form-group">
                        <label>简介</label>
                        <textarea name="description"></textarea>
                    </div>
                    
                    <button type="submit" class="btn">添加漫画</button>
                </form>

                <h2 style="margin-top: 40px;">已上传漫画</h2>
                <table class="comic-table">
                    <thead>
                        <tr>
                            <th>封面</th>
                            <th>标题</th>
                            <th>作者</th>
                            <th>分类</th>
                            <th>阅读量</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($comics as $comic): ?>
                        <tr>
                            <td><img src="<?php echo $comic['cover']; ?>" class="table-cover"></td>
                            <td><?php echo htmlspecialchars($comic['title']); ?></td>
                            <td><?php echo htmlspecialchars($comic['author']); ?></td>
                            <td><?php echo $comic['category']; ?></td>
                            <td><?php echo $comic['views']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
