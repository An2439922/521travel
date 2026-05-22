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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($data['site']['title']) ?></title>
    <meta name="description" content="<?= htmlspecialchars($data['site']['description']) ?>">
    <link rel="stylesheet" href="style.css">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
</head>
<body>

    <nav class="navbar">
        <div class="nav-container">
            <div class="logo">
                <img src="<?= htmlspecialchars($data['header']['logo']) ?>" alt="Logo">
            </div>
            <div class="nav-menu">
                <div class="nav-indicator"></div>
                <ul class="nav-links">
                    <li><a href="#about" class="nav-link">Философия</a></li>
                    <li><a href="#adventure" class="nav-link">Новые приключения</a></li>
                    <li><a href="#story" class="nav-link">Истории</a></li>
                    <li><a href="#gallery" class="nav-link">Журнал путешествий</a></li>
                </ul>
            </div>
            <div class="nav-actions">
                <button id="theme-toggle" class="theme-toggle" aria-label="Переключить тему">
                    <svg class="sun-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"></circle><line x1="12" y1="1" x2="12" y2="3"></line><line x1="12" y1="21" x2="12" y2="23"></line><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"></line><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"></line><line x1="1" y1="12" x2="3" y2="12"></line><line x1="21" y1="12" x2="23" y2="12"></line><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"></line><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"></line></svg>
                    <svg class="moon-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="display: none;"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"></path></svg>
                </button>
            </div>
        </div>
    </nav>

    <header class="hero">
        <img class="hero-bg-image" src="<?= htmlspecialchars($data['hero']['image']) ?>" alt="<?= htmlspecialchars($data['hero']['title']) ?>">
        <div class="hero-overlay"></div>
        <div class="hero-content container">
            <div class="adventure-badge reveal-text">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                <span>521 - Путешествия</span>
            </div>
            <h1 class="reveal-text delay-1"><?= htmlspecialchars($data['hero']['title']) ?></h1>
            <p class="reveal-text delay-2"><?= htmlspecialchars($data['hero']['subtitle']) ?></p>
            <div class="reveal-text delay-3" style="margin-top: 30px;">
                <a href="#about" class="btn">Читать далее</a>
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
                        <img src="<?= htmlspecialchars($data['about']['image']) ?>" alt="<?= htmlspecialchars($data['about']['title']) ?>" loading="lazy">
                    </div>
                </div>
            </div>
        </section>

        <section id="adventure" class="adventure-section fade-in-section">
            <div class="container">
                <div class="adventure-card bento-card">
                    <div class="adventure-image">
                        <img src="<?= htmlspecialchars($data['adventure']['image']) ?>" alt="<?= htmlspecialchars($data['adventure']['title']) ?>" loading="lazy">
                    </div>
                    <div class="adventure-content">
                        <h2><?= htmlspecialchars($data['adventure']['title']) ?></h2>
                        <p><?= nl2br(htmlspecialchars($data['adventure']['text'])) ?></p>
                        <div class="adventure-actions">
                            <a href="<?= htmlspecialchars($data['social']['youtube']) ?>" target="_blank" rel="noopener noreferrer" class="badge-btn">YouTube</a>
                            <a href="<?= htmlspecialchars($data['social']['rutube']) ?>" target="_blank" rel="noopener noreferrer" class="badge-btn">RuTube</a>
                            <a href="<?= htmlspecialchars($data['social']['vk']) ?>" target="_blank" rel="noopener noreferrer" class="badge-btn">ВкВидео</a>
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
                                <img src="<?= htmlspecialchars($story['image']) ?>" alt="<?= htmlspecialchars($story['title']) ?>" loading="lazy">
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </section>

        <section id="gallery" class="gallery-section fade-in-section">
            <div class="container">
                <h2>Журнал Путешествий</h2>
                <div class="masonry-grid">
                    <?php foreach ($data['gallery'] as $image): ?>
                        <div class="masonry-item">
                            <img src="<?= htmlspecialchars($image) ?>" alt="Travel Photo" loading="lazy">
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
                    <?php foreach ($data['social'] as $platform => $url): ?>
                        <a href="<?= htmlspecialchars($url) ?>" target="_blank" rel="noopener noreferrer"><?= ucfirst(htmlspecialchars($platform)) ?></a>
                    <?php endforeach; ?>
                </div>
                <div class="admin-access">
                    <a href="admin.php">Вход для администратора</a>
                </div>
            </div>
            <p class="copyright">&copy; <?= date('Y') ?> <?= htmlspecialchars($data['site']['title']) ?>. Все права защищены.</p>
        </div>
    </footer>

    <script src="script.js"></script>
</body>
</html>
