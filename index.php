<?php
$dataFile = 'data.json';
$data = file_exists($dataFile) ? json_decode(file_get_contents($dataFile), true) : null;

if (!$data) {
    die("Ошибка загрузки данных. Пожалуйста, убедитесь, что файл data.json существует и корректен.");
}
?>
<!DOCTYPE html>
<html lang="ru" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title><?= htmlspecialchars($data['site']['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($data['site']['description']) ?>">
    <meta property="og:title" content="<?= htmlspecialchars($data['site']['title']) ?>">
    <meta property="og:description" content="<?= htmlspecialchars($data['site']['description']) ?>">
    <meta property="og:image" content="https://an2439922.github.io/521travel/<?= htmlspecialchars($data['hero']['image']) ?>">
    <meta property="og:url" content="https://an2439922.github.io/521travel/">
    <meta property="og:type" content="website">
    <link rel="stylesheet" href="style.css?v=16">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="nav-side">
                <ul class="nav-links">
                    <li><a href="#about" class="nav-link">ФИЛОСОФИЯ</a></li>
                    <li><a href="#adventure" class="nav-link">ПРИКЛЮЧЕНИЯ</a></li>
                </ul>
            </div>
            <div class="logo">
                <a href="#" aria-label="Наверх">
                    <img src="<?= htmlspecialchars($data['header']['logo']) ?>" alt="Logo">
                </a>
            </div>
            <div class="nav-side">
                <ul class="nav-links">
                    <li><a href="#story" class="nav-link">ИСТОРИИ</a></li>
                    <li><a href="#gallery" class="nav-link">ЖУРНАЛ</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <header class="hero">
        <div class="hero-bg-wrapper">
            <img class="hero-bg-image" src="<?= htmlspecialchars($data['hero']['image']) ?>" alt="<?= htmlspecialchars($data['hero']['title']) ?>" decoding="async" fetchpriority="high">
        </div>
        <div class="hero-content container">
            <h1 class="reveal-text delay-1">
                <?php 
                    $words = explode(' ', $data['hero']['title']);
                    foreach($words as $word): 
                ?>
                    <span class="hero-word"><?= htmlspecialchars(mb_strtoupper($word, 'UTF-8')) ?></span>
                <?php endforeach; ?>
            </h1>
            <p class="reveal-text delay-2"><?= htmlspecialchars(mb_strtoupper($data['hero']['subtitle'], 'UTF-8')) ?></p>
            <div class="hero-actions reveal-text delay-2">
                <a href="#about" class="btn">Читать далее</a>
                <a href="<?= htmlspecialchars($data['social']['youtube']) ?>" target="_blank" rel="noopener noreferrer" class="btn hero-btn-social">YouTube</a>
                <a href="<?= htmlspecialchars($data['social']['rutube']) ?>" target="_blank" rel="noopener noreferrer" class="btn hero-btn-social">RuTube</a>
                <a href="<?= htmlspecialchars($data['social']['vk']) ?>" target="_blank" rel="noopener noreferrer" class="btn hero-btn-social"><span>ВКвидео</span></a>
            </div>
        </div>
    </header>

    <main>
        <section id="about" class="about-section fade-in-section">
            <div class="container">
                <div class="about-grid">
                    <div class="about-text-content">
                        <h2><?= htmlspecialchars($data['about']['title']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($data['about']['text'])) ?></p>
                    </div>
                    <div class="about-image-content">
                        <img src="<?= htmlspecialchars($data['about']['image']) ?>" alt="<?= htmlspecialchars($data['about']['title']) ?>" loading="lazy" decoding="async">
                    </div>
                </div>
            </div>
        </section>

        <section id="adventure" class="adventure-section fade-in-section">
            <div class="container">
                <div class="adventure-card bento-card">
                    <div class="adventure-image">
                        <img src="<?= htmlspecialchars($data['adventure']['image']) ?>" alt="<?= htmlspecialchars($data['adventure']['title']) ?>" loading="lazy" decoding="async">
                    </div>
                    <div class="adventure-content">
                        <h2><?= htmlspecialchars($data['adventure']['title']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($data['adventure']['text'])) ?></p>
                        <div class="adventure-actions">
                            <a href="<?= htmlspecialchars($data['adventure']['youtube']) ?>" target="_blank" rel="noopener noreferrer" class="badge-btn">YouTube</a>
                            <a href="<?= htmlspecialchars($data['adventure']['rutube']) ?>" target="_blank" rel="noopener noreferrer" class="badge-btn">RuTube</a>
                            <a href="<?= htmlspecialchars($data['adventure']['vk']) ?>" target="_blank" rel="noopener noreferrer" class="badge-btn"><span style="font-weight: 500;">ВКвидео</span></a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="story" class="story-section fade-in-section">
            <div class="container">
                <div class="section-header">
                    <h2 class="section-title">Истории</h2>
                    <div class="carousel-controls">
                        <button class="carousel-prev" aria-label="Предыдущая история">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                        </button>
                        <button class="carousel-next" aria-label="Следующая история">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>
                
                <div class="stories-carousel-container">
                    <div class="stories-carousel">
                        <?php foreach ($data['stories'] as $story): ?>
                        <div class="story-card bento-card carousel-slide">
                            <div class="story-content">
                                <h3><?= htmlspecialchars($story['title']) ?></h3>
                                <?php if(isset($story['tags']) && !empty($story['tags'])): ?>
                                <div class="story-tags">
                                    <?php foreach ($story['tags'] as $tag): ?>
                                        <span class="tag"><?= htmlspecialchars($tag) ?></span>
                                    <?php endforeach; ?>
                                </div>
                                <?php endif; ?>
                                <p><?= nl2br(htmlspecialchars($story['text'])) ?></p>
                                <?php if (!empty($story['link']) && $story['link'] !== '#'): ?>
                                    <div class="story-actions" style="margin-top: auto;">
                                        <a href="<?= htmlspecialchars($story['link']) ?>" target="_blank" rel="noopener noreferrer" class="badge-btn">Продолжение в ВК</a>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="story-image">
                                <img src="<?= htmlspecialchars($story['image']) ?>" alt="<?= htmlspecialchars($story['title']) ?>" loading="lazy" decoding="async">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="gallery" class="gallery-section fade-in-section">
            <div class="container">
                <h2>Журнал путешествий</h2>
                <div class="masonry-grid">
                    <?php foreach ($data['gallery'] as $item): ?>
                        <div class="masonry-item" onclick="void(0)">
                            <img src="<?= htmlspecialchars($item['image']) ?>" alt="Travel Photo" loading="lazy" decoding="async">
                            <div class="masonry-overlay">
                                <a href="<?= htmlspecialchars($item['youtube']) ?>" target="_blank" rel="noopener noreferrer" class="btn masonry-btn-social" onclick="event.stopPropagation()">YouTube</a>
                                <a href="<?= htmlspecialchars($item['rutube']) ?>" target="_blank" rel="noopener noreferrer" class="btn masonry-btn-social" onclick="event.stopPropagation()">RuTube</a>
                                <a href="<?= htmlspecialchars($item['vk']) ?>" target="_blank" rel="noopener noreferrer" class="btn masonry-btn-social" onclick="event.stopPropagation()"><span style="font-weight: 500;">ВКвидео</span></a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
    </main>

    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="social-links">
                    <?php 
                        $platformNames = [
                            'youtube' => 'YouTube',
                            'rutube' => 'RuTube',
                            'vk' => '<span style="font-weight: 500;">ВКвидео</span>'
                        ];
                        foreach ($data['social'] as $platform => $url): 
                            $name = isset($platformNames[$platform]) ? $platformNames[$platform] : ucfirst(htmlspecialchars($platform));
                    ?>
                        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer"><?= $name ?></a>
                    <?php endforeach; ?>
                </div>
                <div class="admin-access">
                    <a href="admin.php" aria-label="Вход для администратора" style="opacity: 0.2; transition: opacity 0.3s;" onmouseover="this.style.opacity=1" onmouseout="this.style.opacity=0.2">
                        <img src="<?= htmlspecialchars($data['header']['logo']) ?>" alt="Admin" style="height: 30px;">
                    </a>
                </div>
            </div>
            <div class="footer-bottom" style="text-align: center; opacity: 0.5; font-size: 0.9rem; line-height: 1.8;">
                <p>&copy; <?= date('Y') ?> <?= htmlspecialchars($data['site']['title']) ?>. Все права защищены.</p>
                <p>Разработано и создано 521технолОджи</p>
            </div>
        </div>
    </footer>

    <script src="script.js?v=15"></script>
</body>
</html>
