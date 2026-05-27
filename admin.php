<?php
session_start();

$password = 'admin';
$dataFile = 'data.json';
$trashDir = 'trash/';

// Auto-clean trash (files older than 30 days)
if (!is_dir($trashDir)) {
    mkdir($trashDir, 0777, true);
}
$files = glob($trashDir . '*');
$now = time();
foreach ($files as $file) {
    if (is_file($file)) {
        if ($now - filemtime($file) >= 30 * 24 * 60 * 60) {
            unlink($file);
        }
    }
}

// Auth Logic
if (isset($_POST['login'])) {
    if ($_POST['password'] === $password) {
        $_SESSION['admin_logged_in'] = true;
    } else {
        $loginError = "Неверный пароль!";
    }
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: admin.php");
    exit;
}

if (!isset($_SESSION['admin_logged_in'])) {
    ?>
    <!DOCTYPE html>
    <html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Вход в панель администратора</title>
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600&display=swap" rel="stylesheet">
        <style>
            body { font-family: 'Montserrat', sans-serif; background: #f4f4f4; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; }
            .login-box { background: white; padding: 40px; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); width: 100%; max-width: 400px; text-align: center; }
            input { width: 100%; padding: 12px; margin: 15px 0; border: 1px solid #ddd; border-radius: 8px; font-size: 16px; box-sizing: border-box; }
            button { background: #1a1a1a; color: white; border: none; padding: 12px; width: 100%; border-radius: 8px; font-size: 16px; cursor: pointer; transition: 0.3s; }
            button:hover { background: #333; }
            .error { color: red; margin-bottom: 15px; }
        </style>
    </head>
    <body>
        <div class="login-box">
            <h2>Вход</h2>
            <?php if (isset($loginError)) echo "<div class='error'>$loginError</div>"; ?>
            <form method="POST">
                <input type="password" name="password" placeholder="Пароль" required autofocus>
                <button type="submit" name="login">Войти</button>
            </form>
        </div>
    </body>
    </html>
    <?php
    exit;
}

// Function to move to trash
function moveToTrash($filename) {
    global $trashDir;
    if (file_exists($filename) && !is_dir($filename)) {
        rename($filename, $trashDir . time() . '_' . basename($filename));
    }
}

// Function to upload file
function uploadFile($fileInputName, $oldFilename = null) {
    if (isset($_FILES[$fileInputName]) && $_FILES[$fileInputName]['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES[$fileInputName]['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
        if (in_array($ext, $allowed)) {
            $newName = time() . '_' . uniqid() . '.' . $ext;
            move_uploaded_file($_FILES[$fileInputName]['tmp_name'], $newName);
            if ($oldFilename) {
                moveToTrash($oldFilename);
            }
            return $newName;
        }
    }
    return $oldFilename;
}

// Load data
$data = json_decode(file_get_contents($dataFile), true);

// Save logic
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    
        if ($_POST['action'] === 'save_social') {
        $data['social']['youtube'] = $_POST['youtube'];
        $data['social']['rutube'] = $_POST['rutube'];
        $data['social']['vk'] = $_POST['vk'];
    }
    if ($_POST['action'] === 'save_meta') {
        $data['meta']['title'] = $_POST['title'];
        $data['meta']['description'] = $_POST['description'];
    }
    
    if ($_POST['action'] === 'save_header') {
        $data['header']['logo'] = uploadFile('logo', $data['header']['logo']);
        $data['header']['nav'][0]['text'] = $_POST['nav_0'];
        $data['header']['nav'][1]['text'] = $_POST['nav_1'];
        $data['header']['nav'][2]['text'] = $_POST['nav_2'];
    }

    if ($_POST['action'] === 'save_hero') {
        $data['hero']['badge'] = $_POST['badge'];
        $data['hero']['title'] = $_POST['title'];
        $data['hero']['subtitle'] = $_POST['subtitle'];
        $data['hero']['image'] = uploadFile('image', $data['hero']['image']);
    }

    if ($_POST['action'] === 'save_about') {
        $data['about']['title'] = $_POST['title'];
        $data['about']['text'] = $_POST['text'];
        $data['about']['image'] = uploadFile('image', $data['about']['image']);
    }

    if ($_POST['action'] === 'save_adventure') {
        $data['adventure']['title'] = $_POST['title'];
        $data['adventure']['text'] = $_POST['text'];
        $data['adventure']['youtube'] = $_POST['youtube'];
        $data['adventure']['rutube'] = $_POST['rutube'];
        $data['adventure']['vk'] = $_POST['vk'];
        $newImg = uploadFile('image', $data['adventure']['image']);
        if ($newImg) $data['adventure']['image'] = $newImg;
    }

    if ($_POST['action'] === 'save_stories') {
            if ($_POST['action'] === 'add_story') {
        $newStory = [
            'title' => $_POST['title'],
            'text' => $_POST['text'],
            'link' => $_POST['link'],
            'tags' => array_map('trim', explode(',', $_POST['tags'])),
            'image' => uploadFile('image')
        ];
        array_unshift($data['stories'], $newStory);
    }
    if ($_POST['action'] === 'edit_story') {
        $index = $_POST['index'];
        $data['stories'][$index]['title'] = $_POST['title'];
        $data['stories'][$index]['text'] = $_POST['text'];
        $data['stories'][$index]['link'] = $_POST['link'];
        $data['stories'][$index]['tags'] = array_map('trim', explode(',', $_POST['tags']));
        $newImg = uploadFile('image', $data['stories'][$index]['image']);
        if ($newImg) $data['stories'][$index]['image'] = $newImg;
    }
    if ($_POST['action'] === 'delete_story') {
        $index = $_POST['index'];
        moveToTrash($data['stories'][$index]['image']);
        array_splice($data['stories'], $index, 1);
    }
    if ($_POST['action'] === 'add_gallery') {
        $newImg = uploadFile('image');
        if ($newImg) {
            array_unshift($data['gallery'], [
                'image' => $newImg,
                'youtube' => $_POST['youtube'],
                'rutube' => $_POST['rutube'],
                'vk' => $_POST['vk']
            ]);
        }
    }
    if ($_POST['action'] === 'edit_gallery') {
        $index = $_POST['index'];
        $data['gallery'][$index]['youtube'] = $_POST['youtube'];
        $data['gallery'][$index]['rutube'] = $_POST['rutube'];
        $data['gallery'][$index]['vk'] = $_POST['vk'];
    }
    if ($_POST['action'] === 'delete_gallery') {
        $index = $_POST['index'];
        moveToTrash($data['gallery'][$index]['image']);
        array_splice($data['gallery'], $index, 1);
    }
    }

    // Save and generate static html
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
    exec("php index.php > index.html");
    header("Location: admin.php?success=1");
    exit;
}

?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Админ-панель</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Montserrat', sans-serif; background: #f4f4f4; margin: 0; display: flex; color: #333; }
        .sidebar { width: 250px; background: #1a1a1a; color: white; min-height: 100vh; padding: 20px 0; position: fixed; }
        .sidebar h2 { text-align: center; margin-bottom: 30px; font-size: 20px; }
        .nav-link { display: block; padding: 15px 25px; color: #ccc; text-decoration: none; transition: 0.2s; cursor: pointer; }
        .nav-link:hover, .nav-link.active { background: #333; color: white; border-left: 4px solid #fff; }
        .main-content { margin-left: 250px; padding: 40px; flex: 1; }
        .panel { background: white; padding: 30px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); margin-bottom: 30px; display: none; }
        .panel.active { display: block; }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 8px; font-weight: 500; }
        input[type="text"], textarea { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px; box-sizing: border-box; font-family: inherit; }
        textarea { height: 120px; resize: vertical; }
        button.save { background: #000; color: white; border: none; padding: 12px 24px; border-radius: 6px; font-size: 16px; cursor: pointer; }
        button.save:hover { background: #333; }
        .success-msg { background: #d4edda; color: #155724; padding: 15px; border-radius: 6px; margin-bottom: 20px; }
        .img-preview { max-width: 200px; max-height: 150px; margin-top: 10px; border-radius: 8px; border: 1px solid #ddd; object-fit: cover; }
    </style>
</head>
<body>

<div class="sidebar">
    <img src="<?= htmlspecialchars($data['header']['logo']) ?>" style="max-width: 150px; display: block; margin: 0 auto 30px;">
    <a class="nav-link active" onclick="showPanel('meta')">Основные</a>
    <a class="nav-link" onclick="showPanel('header')">Шапка</a>
    <a class="nav-link" onclick="showPanel('hero')">Главный экран</a>
    <a class="nav-link" onclick="showPanel('about')">О нас</a>
        <a class="nav-link" onclick="showPanel('adventure')">Приключение</a>
    <a class="nav-link" onclick="showPanel('stories')">Истории</a>
    <a class="nav-link" onclick="showPanel('gallery')">Журнал (Галерея)</a>
    <a class="nav-link" onclick="showPanel('social')">Глобальные соц. сети</a>
    <a class="nav-link" href="?logout=1" style="margin-top: 50px; color: #ff6b6b;">Выйти</a>
</div>

<div class="main-content">
    <?php if (isset($_GET['success'])): ?>
        <div class="success-msg">Изменения сохранены! Сайт успешно обновлён.</div>
    <?php endif; ?>

    <div id="meta" class="panel active">
        <h2>Основные настройки</h2>
        <form method="POST">
            <input type="hidden" name="action" value="save_meta">
            <div class="form-group">
                <label>Название сайта</label>
                <input type="text" name="title" value="<?= htmlspecialchars($data['meta']['title'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>Описание сайта</label>
                <textarea name="description"><?= htmlspecialchars($data['meta']['description'] ?? '') ?></textarea>
            </div>
            <button type="submit" class="save">Сохранить</button>
        </form>
    </div>

    <div id="header" class="panel">
        <h2>Шапка (Header)</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_header">
            <div class="form-group">
                <label>Логотип</label>
                <img src="<?= htmlspecialchars($data['header']['logo']) ?>" class="img-preview"><br><br>
                <input type="file" name="logo">
            </div>
            <div class="form-group">
                <label>Ссылка 1</label>
                <input type="text" name="nav_0" value="<?= htmlspecialchars($data['header']['nav'][0]['text']) ?>">
            </div>
            <div class="form-group">
                <label>Ссылка 2</label>
                <input type="text" name="nav_1" value="<?= htmlspecialchars($data['header']['nav'][1]['text']) ?>">
            </div>
            <div class="form-group">
                <label>Ссылка 3</label>
                <input type="text" name="nav_2" value="<?= htmlspecialchars($data['header']['nav'][2]['text']) ?>">
            </div>
            <button type="submit" class="save">Сохранить</button>
        </form>
    </div>

    <div id="hero" class="panel">
        <h2>Главный экран (Hero)</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_hero">
            <div class="form-group">
                <label>Фоновая картинка</label>
                <img src="<?= htmlspecialchars($data['hero']['image']) ?>" class="img-preview"><br><br>
                <input type="file" name="image">
            </div>
            <div class="form-group">
                <label>Значок (Бейдж)</label>
                <input type="text" name="badge" value="<?= htmlspecialchars($data['hero']['badge']) ?>">
            </div>
            <div class="form-group">
                <label>Заголовок</label>
                <input type="text" name="title" value="<?= htmlspecialchars($data['hero']['title']) ?>">
            </div>
            <div class="form-group">
                <label>Подзаголовок</label>
                <textarea name="subtitle"><?= htmlspecialchars($data['hero']['subtitle']) ?></textarea>
            </div>
            <button type="submit" class="save">Сохранить</button>
        </form>
    </div>

    <div id="about" class="panel">
        <h2>Блок "О нас"</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_about">
            <div class="form-group">
                <label>Картинка</label>
                <img src="<?= htmlspecialchars($data['about']['image']) ?>" class="img-preview"><br><br>
                <input type="file" name="image">
            </div>
            <div class="form-group">
                <label>Заголовок</label>
                <input type="text" name="title" value="<?= htmlspecialchars($data['about']['title']) ?>">
            </div>
            <div class="form-group">
                <label>Текст</label>
                <textarea name="text"><?= htmlspecialchars($data['about']['text']) ?></textarea>
            </div>
            <button type="submit" class="save">Сохранить</button>
        </form>
    </div>

    <div id="adventure" class="panel">
        <h2>Блок "Новое приключение"</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="hidden" name="action" value="save_adventure">
            <div class="form-group">
                <label>Картинка</label>
                <img src="<?= htmlspecialchars($data['adventure']['image']) ?>" class="img-preview"><br><br>
                <input type="file" name="image">
            </div>
            <div class="form-group">
                <label>Заголовок</label>
                <input type="text" name="title" value="<?= htmlspecialchars($data['adventure']['title']) ?>">
            </div>
            <div class="form-group">
                <label>Текст</label>
                <textarea name="text"><?= htmlspecialchars($data['adventure']['text']) ?></textarea>
            </div>
            <div class="form-group">
                <label>YouTube</label>
                <input type="text" name="youtube" value="<?= htmlspecialchars($data['adventure']['youtube'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>RuTube</label>
                <input type="text" name="rutube" value="<?= htmlspecialchars($data['adventure']['rutube'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>ВКвидео</label>
                <input type="text" name="vk" value="<?= htmlspecialchars($data['adventure']['vk'] ?? '') ?>">
            </div>
            <button type="submit" class="save">Сохранить</button>
        </form>
    </div>

    <div id="stories" class="panel">
        <h2>Истории</h2>
        
        <h3>Добавить новую историю</h3>
        <form method="POST" enctype="multipart/form-data" style="background:#f9f9f9; padding:20px; border-radius:8px; margin-bottom:30px;">
            <input type="hidden" name="action" value="add_story">
            <div class="form-group">
                <label>Картинка</label>
                <input type="file" name="image" required>
            </div>
            <div class="form-group"><label>Заголовок</label><input type="text" name="title" required></div>
            <div class="form-group"><label>Текст</label><textarea name="text" required></textarea></div>
            <div class="form-group"><label>Ссылка на ВК</label><input type="text" name="link"></div>
            <div class="form-group"><label>Теги (через запятую)</label><input type="text" name="tags" placeholder="#истории, #лес"></div>
            <button type="submit" class="save">Добавить</button>
        </form>

        <hr>
        <h3>Текущие истории</h3>
        <?php foreach ($data['stories'] as $index => $story): ?>
            <div style="background:#fff; padding:20px; border:1px solid #ddd; border-radius:8px; margin-bottom:20px;">
                <form method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="edit_story">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <img src="<?= htmlspecialchars($story['image']) ?>" class="img-preview" style="float:right; margin-left:20px;">
                    <div class="form-group"><label>Новая картинка (оставьте пустым, чтобы не менять)</label><input type="file" name="image"></div>
                    <div class="form-group"><label>Заголовок</label><input type="text" name="title" value="<?= htmlspecialchars($story['title']) ?>"></div>
                    <div class="form-group"><label>Текст</label><textarea name="text"><?= htmlspecialchars($story['text']) ?></textarea></div>
                    <div class="form-group"><label>Ссылка на ВК</label><input type="text" name="link" value="<?= htmlspecialchars($story['link']) ?>"></div>
                    <div class="form-group"><label>Теги</label><input type="text" name="tags" value="<?= htmlspecialchars(implode(', ', $story['tags'])) ?>"></div>
                    <button type="submit" class="save" style="background:#28a745;">Сохранить изменения</button>
                </form>
                <form method="POST" style="margin-top:10px;" onsubmit="return confirm('Точно удалить?');">
                    <input type="hidden" name="action" value="delete_story">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit" class="save" style="background:#dc3545;">Удалить историю</button>
                </form>
                <div style="clear:both;"></div>
            </div>
        <?php endforeach; ?>
    </div>

    <div id="gallery" class="panel">
        <h2>Журнал (Галерея)</h2>
        
        <h3>Добавить фото</h3>
        <form method="POST" enctype="multipart/form-data" style="background:#f9f9f9; padding:20px; border-radius:8px; margin-bottom:30px;">
            <input type="hidden" name="action" value="add_gallery">
            <div class="form-group">
                <label>Фотография</label>
                <input type="file" name="image" required>
            </div>
            <div class="form-group"><label>Ссылка YouTube</label><input type="text" name="youtube" placeholder="https://..."></div>
            <div class="form-group"><label>Ссылка RuTube</label><input type="text" name="rutube" placeholder="https://..."></div>
            <div class="form-group"><label>Ссылка ВК</label><input type="text" name="vk" placeholder="https://..."></div>
            <button type="submit" class="save">Добавить фото</button>
        </form>

        <hr>
        <h3>Текущие фотографии</h3>
        <div style="display:flex; flex-wrap:wrap; gap:20px;">
        <?php foreach ($data['gallery'] as $index => $item): ?>
            <div style="background:#fff; padding:15px; border:1px solid #ddd; border-radius:8px; width:calc(50% - 10px); box-sizing:border-box;">
                <img src="<?= htmlspecialchars($item['image']) ?>" class="img-preview" style="width:100%; height:150px; margin-bottom:15px;"><br>
                <form method="POST">
                    <input type="hidden" name="action" value="edit_gallery">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <div class="form-group"><label>YouTube</label><input type="text" name="youtube" value="<?= htmlspecialchars($item['youtube']) ?>"></div>
                    <div class="form-group"><label>RuTube</label><input type="text" name="rutube" value="<?= htmlspecialchars($item['rutube']) ?>"></div>
                    <div class="form-group"><label>ВК</label><input type="text" name="vk" value="<?= htmlspecialchars($item['vk']) ?>"></div>
                    <button type="submit" class="save" style="background:#28a745; width:100%;">Сохранить ссылки</button>
                </form>
                <form method="POST" style="margin-top:10px;" onsubmit="return confirm('Точно удалить фото?');">
                    <input type="hidden" name="action" value="delete_gallery">
                    <input type="hidden" name="index" value="<?= $index ?>">
                    <button type="submit" class="save" style="background:#dc3545; width:100%;">Удалить</button>
                </form>
            </div>
        <?php endforeach; ?>
        </div>
    </div>
    <div id="social" class="panel">
        <h2>Глобальные соц. сети (для шапки и подвала)</h2>
        <form method="POST">
            <input type="hidden" name="action" value="save_social">
            <div class="form-group">
                <label>YouTube</label>
                <input type="text" name="youtube" value="<?= htmlspecialchars($data['social']['youtube'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>RuTube</label>
                <input type="text" name="rutube" value="<?= htmlspecialchars($data['social']['rutube'] ?? '') ?>">
            </div>
            <div class="form-group">
                <label>ВКвидео</label>
                <input type="text" name="vk" value="<?= htmlspecialchars($data['social']['vk'] ?? '') ?>">
            </div>
            <button type="submit" class="save">Сохранить</button>
        </form>
    </div>
</div>

<script>
function showPanel(id) {
    document.querySelectorAll('.panel').forEach(p => p.classList.remove('active'));
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
    document.getElementById(id).classList.add('active');
    event.target.classList.add('active');
}
// Remove success message after 3 seconds
setTimeout(() => {
    let msg = document.querySelector('.success-msg');
    if(msg) msg.style.display = 'none';
}, 3000);
</script>
</body>
</html>
