<?php
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit;
}

// 获取所有漫画
$stmt = $pdo->query("SELECT * FROM comics ORDER BY created_at DESC");
$comics = $stmt->fetchAll();

// 获取热门漫画
$stmt = $pdo->query("SELECT * FROM comics ORDER BY views DESC LIMIT 4");
$hot_comics = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>漫宇宙 - 首页</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <a href="home.php" class="logo">📚 漫宇宙</a>
            <div class="nav-menu">
                <a href="home.php">首页</a>
                <a href="#">分类</a>
                <a href="#">排行</a>
                <a href="admin.php">后台</a>
            </div>
            <div class="user-info">
                <span><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                <a href="logout.php" class="logout">退出</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <!-- 轮播图 -->
        <div class="carousel" id="carousel">
            <div class="carousel-inner">
                <?php
                $carousel_items = [
     ['title' => '无名者的测试', 'desc' => '意外卷入了一场奇怪的游戏测试', 'img' => '/images/1.jpg'],
    ['title' => '一人之下', 'desc' => '异人世界的奇幻故事', 'img' => '/images/2.jpg'],
    ['title' => '全职高手', 'desc' => '电竞高手的荣耀之路', 'img' => '/images/3.jpg'],
    ['title' => '溯洄春时', 'desc' => '谁是怪物', 'img' => '/images/4.jpg'],
    ['title' => '仙为奴神为仆', 'desc' => '一刀破青云，魂骨何去向！', 'img' => '/images/5.jpg'],
  ['title' => '以恋相称尚显微茫', 'desc' => '《堀与宫村》漫画作者新连载！若要恋爱的话，像这样子就好！', 'img' => '/images/8.jpg']

        ];
                foreach ($carousel_items as $i => $item):
                ?>
                <div class="carousel-item <?php echo $i === 0 ? 'active' : ''; ?>" style="background-image: url('<?php echo $item['img']; ?>')">
                    <div class="overlay"></div>
                    <div class="carousel-caption">
                        <h3><?php echo $item['title']; ?></h3>
                        <p><?php echo $item['desc']; ?></p>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <button class="carousel-control prev" onclick="changeSlide(-1)">❮</button>
            <button class="carousel-control next" onclick="changeSlide(1)">❯</button>
            <div class="carousel-indicators">
                <?php foreach ($carousel_items as $i => $item): ?>
                <span class="indicator <?php echo $i === 0 ? 'active' : ''; ?>" onclick="goToSlide(<?php echo $i; ?>)"></span>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 分类 -->
        <div class="categories">
            <a href="#" class="category-btn">全部</a>
            <a href="#" class="category-btn">玄幻</a>
            <a href="#" class="category-btn">搞笑</a>
            <a href="#" class="category-btn">古风</a>
            <a href="#" class="category-btn">科幻</a>
        </div>

        <!-- 热门推荐 -->
        <div class="section">
            <div class="section-header">
                <h2>🔥 热门推荐</h2>
                <a href="#" class="more-link">更多</a>
            </div>
            <div class="comic-grid">
                <?php foreach ($hot_comics as $comic): ?>
                <a href="#" class="comic-card">
                    <div class="comic-cover">
                        <img src="<?php echo $comic['cover']; ?>" alt="<?php echo $comic['title']; ?>">
                    </div>
                    <div class="comic-info">
                        <h3 class="comic-title"><?php echo htmlspecialchars($comic['title']); ?></h3>
                        <div class="comic-meta">
                            <span><?php echo $comic['author']; ?></span>
                            <span>👁️ <?php echo $comic['views']; ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>

        <!-- 最新上架 -->
        <div class="section">
            <div class="section-header">
                <h2>🆕 最新上架</h2>
                <a href="#" class="more-link">更多</a>
            </div>
            <div class="comic-grid">
                <?php foreach ($comics as $comic): ?>
                <a href="#" class="comic-card">
                    <div class="comic-cover">
                        <img src="<?php echo $comic['cover']; ?>" alt="<?php echo $comic['title']; ?>">
                    </div>
                    <div class="comic-info">
                        <h3 class="comic-title"><?php echo htmlspecialchars($comic['title']); ?></h3>
                        <div class="comic-meta">
                            <span><?php echo $comic['author']; ?></span>
                            <span>❤️ <?php echo $comic['likes']; ?></span>
                        </div>
                    </div>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p>© 2026 快看漫画</p>
        </div>
    </footer>

    <script>
    let currentSlide = 0;
    const slides = document.querySelectorAll('.carousel-item');
    const indicators = document.querySelectorAll('.indicator');

    function showSlide(index) {
        if (index < 0) index = slides.length - 1;
        if (index >= slides.length) index = 0;
        
        slides.forEach(s => s.classList.remove('active'));
        indicators.forEach(i => i.classList.remove('active'));
        
        slides[index].classList.add('active');
        indicators[index].classList.add('active');
        currentSlide = index;
    }

    function changeSlide(direction) {
        showSlide(currentSlide + direction);
    }

    function goToSlide(index) {
        showSlide(index);
    }

    setInterval(() => changeSlide(1), 5000);
    </script>
</body>
</html>


