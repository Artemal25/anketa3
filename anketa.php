<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Лабораторная работа №3</title>
    <link rel="icon" type="image/x-icon" href="j.ico">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1"></script>
    <script src="https://cdn.jsdelivr.net/npm/tsparticles@2.12.0/tsparticles.bundle.min.js"></script>
    <style>
        .db-screenshots {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid rgba(255,255,255,0.2);
        }
        .db-screenshots h3 {
            text-align: center;
            margin-bottom: 20px;
            color: #ffcc88;
        }
        .screenshot-item {
            background: rgba(0,0,0,0.4);
            border-radius: 20px;
            padding: 15px;
            margin-bottom: 25px;
            text-align: center;
        }
        .screenshot-item img {
            max-width: 100%;
            border-radius: 12px;
            border: 1px solid #5a7c9e;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        }
        .screenshot-item p {
            margin-top: 10px;
            color: #ddd;
            font-size: 0.95rem;
        }
    </style>
</head>
<body>
<div class="gradient-bg"></div>
<div class="blob blob1"></div>
<div class="blob blob2"></div>
<div class="blob blob3"></div>
<div class="blob blob4"></div>

<div class="container">
    <div class="site-header">
        <div class="header-left">
            <img src="image.jpg" alt="User photo" class="profile-photo">
            <h1>Анкета</h1>
        </div>
        <div class="nav-links">
            <a href="index.php">Главная</a>
            <a href="v.php">Просмотр анкет</a>
        </div>
    </div>

    <?php if (!empty($success_message)): ?>
        <div class="success-message"><?= htmlspecialchars($success_message) ?></div>
    <?php endif; ?>

    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="error-message"><?= htmlspecialchars($error) ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="post" action="index.php">
        <div class="form-group">
            <label>ФИО *</label>
            <input type="text" name="full_name" value="<?= htmlspecialchars($form_data['full_name']) ?>"
                   <?= isset($errors['full_name']) ? 'class="error"' : '' ?>>
        </div>

        <div class="form-group">
            <label>Телефон *</label>
            <input type="tel" name="phone" value="<?= htmlspecialchars($form_data['phone']) ?>"
                   <?= isset($errors['phone']) ? 'class="error"' : '' ?>>
        </div>

        <div class="form-group">
            <label>E-mail *</label>
            <input type="email" name="email" value="<?= htmlspecialchars($form_data['email']) ?>"
                   <?= isset($errors['email']) ? 'class="error"' : '' ?>>
        </div>

        <div class="form-group">
            <label>Дата рождения *</label>
            <input type="date" name="birth_date" value="<?= htmlspecialchars($form_data['birth_date']) ?>"
                   <?= isset($errors['birth_date']) ? 'class="error"' : '' ?>>
        </div>

        <div class="form-group">
            <label>Пол *</label>
            <div class="radio-group">
                <label><input type="radio" name="gender" value="male" <?= $form_data['gender'] === 'male' ? 'checked' : '' ?>> Мужской</label>
                <label><input type="radio" name="gender" value="female" <?= $form_data['gender'] === 'female' ? 'checked' : '' ?>> Женский</label>
            </div>
        </div>

        <div class="form-group">
            <label>Любимые языки программирования *</label>
            <select name="languages[]" multiple size="8">
                <?php foreach ($languages_from_db as $lang): ?>
                    <option value="<?= htmlspecialchars($lang) ?>" <?= in_array($lang, $form_data['languages']) ? 'selected' : '' ?>><?= htmlspecialchars($lang) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Биография</label>
            <textarea name="biography" rows="5"><?= htmlspecialchars($form_data['biography']) ?></textarea>
        </div>

        <div class="form-group checkbox">
            <label>
                <input type="checkbox" name="contract_accepted" value="1" <?= $form_data['contract_accepted'] ? 'checked' : '' ?>>
                Я ознакомлен(а) с контрактом *
            </label>
        </div>

        <button type="submit">Сохранить</button>
    </form>

    <!-- Блок с описанием работы с базой данных и скриншотами -->
    <div class="db-screenshots">
        <h3> Работа с базой данных</h3>

        <div class="screenshot-item">
            <img src="1.png" alt="Создание таблиц">
            <p><strong>Шаг 1 – Создание таблиц</strong><br>
            Созданы три таблицы: <code>application</code> (анкеты), <code>language</code> (языки программирования) 
            и <code>application_language</code> (связь многие-ко-многим). Таблицы используют движок InnoDB, 
            кодировку utf8mb4, внешние ключи с каскадным удалением. Поля соответствуют требованиям задания.</p>
        </div>

        <div class="screenshot-item">
            <img src="2.png" alt="Заполнение таблицы language">
            <p><strong>Шаг 2 – Заполнение справочника языков</strong><br>
            В таблицу <code>language</code> вставлены все допустимые языки программирования согласно заданию 
            (Pascal, C, C++, JavaScript, PHP, Python, Java, Haskell, Clojure, Prolog, Scala, Go) 
            а также дополнительные языки для расширения функциональности.</p>
        </div>

        <div class="screenshot-item">
            <img src="3.png" alt="Результат SELECT * FROM application">
            <p><strong>Шаг 3 – Просмотр сохранённых анкет</strong><br>
            Команда <code>SELECT * FROM application</code> показывает все записи, которые были успешно 
            сохранены через форму. Каждая запись имеет уникальный ID, ФИО, телефон, email, дату рождения, 
            пол, биографию, отметку о согласии с контрактом и timestamp создания. Языки программирования 
            хранятся в связанной таблице <code>application_language</code>.</p>
        </div>

        <div class="screenshot-item">
            <p style="text-align: center; font-style: italic; color: #aaa;">
                База данных приведена к третьей нормальной форме (3НФ). 
                Все запросы к БД выполняются с использованием подготовленных выражений (prepared statements) 
                для защиты от SQL-инъекций.
            </p>
        </div>
    </div>

    <div class="site-footer">
        <p>ЛАБОРАТОРНАЯ РАБОТА №3</p>
    </div>
</div>

<script>
    tsParticles.load({
        id: "tsparticles",
        options: {
            fpsLimit: 60,
            background: { color: "transparent" },
            particles: {
                number: { value: 80, density: { enable: true, area: 800 } },
                color: { value: ["#ffffff", "#aaccff", "#ffaa88"] },
                shape: { type: "circle" },
                opacity: { value: 0.6, random: true, anim: { enable: true, speed: 1, opacity_min: 0.1 } },
                size: { value: 2, random: true, anim: { enable: true, speed: 2, size_min: 0.5 } },
                move: {
                    enable: true,
                    speed: 1.5,
                    direction: "none",
                    random: true,
                    straight: false,
                    outModes: { default: "out" },
                },
                links: {
                    enable: true,
                    distance: 150,
                    color: "#5a7c9e",
                    opacity: 0.4,
                    width: 1,
                },
                interactivity: {
                    events: {
                        onHover: { enable: true, mode: "grab" },
                        onClick: { enable: false },
                    },
                    modes: {
                        grab: { distance: 140, links: { opacity: 0.8 } },
                    },
                },
            },
            detectRetina: true,
        },
    });

    window.addEventListener('load', function() {
        if (document.querySelector('.success-message')) {
            canvasConfetti({
                particleCount: 200,
                spread: 70,
                origin: { y: 0.6 },
                startVelocity: 20,
                colors: ['#2c4c8c', '#5a7c9e', '#ffffff']
            });
            setTimeout(() => canvasConfetti({ particleCount: 100, spread: 100, origin: { y: 0.5, x: 0.3 } }), 150);
            setTimeout(() => canvasConfetti({ particleCount: 100, spread: 100, origin: { y: 0.5, x: 0.7 } }), 300);
        }
    });
</script>
</body>
</html>