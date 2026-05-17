<?php
$db_user = 'u82325';
$db_pass = '2941524';
$db_name = 'u82325';

try {
    $pdo = new PDO("mysql:host=localhost;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $pdo->query("
        SELECT a.*, GROUP_CONCAT(l.name SEPARATOR ', ') AS languages
        FROM application a
        LEFT JOIN application_language al ON a.id = al.application_id
        LEFT JOIN language l ON al.language_id = l.id
        GROUP BY a.id
        ORDER BY a.id DESC
    ");
    $applications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    die("Ошибка: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Сохранённые анкеты</title>
    <link rel="stylesheet" href="style.css">
    <style>
        h1 { margin-bottom: 20px; }
        .back-link { margin-top: 30px; }
    </style>
</head>
<body>
<div class="gradient-bg"></div>
<div class="blob blob1"></div>
<div class="blob blob2"></div>
<div class="blob blob3"></div>

<div class="container">
    <div class="site-header">
        <h1>Сохранённые анкеты</h1>
        <div class="nav-links">
            <a href="index.php">Форма</a>
            
        </div>
    </div>

    <p>Всего записей: <?= count($applications) ?></p>

    <table>
        <thead>
            <tr><th>ID</th><th>ФИО</th><th>Телефон</th><th>Email</th><th>Дата рождения</th><th>Пол</th><th>Языки</th><th>Биография</th><th>Дата создания</th></tr>
        </thead>
        <tbody>
        <?php foreach ($applications as $app): ?>
            <tr>
                <td><?= htmlspecialchars($app['id']) ?></td>
                <td><?= htmlspecialchars($app['full_name']) ?></td>
                <td><?= htmlspecialchars($app['phone']) ?></td>
                <td><?= htmlspecialchars($app['email']) ?></td>
                <td><?= htmlspecialchars($app['birth_date']) ?></td>
                <td><?= $app['gender'] === 'male' ? 'Мужской' : 'Женский' ?></td>
                <td><?= htmlspecialchars($app['languages']) ?></td>
                <td><?= nl2br(htmlspecialchars($app['biography'])) ?></td>
                <td><?= htmlspecialchars($app['created_at']) ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>

    <div class="back-link">
        <a href="index.php">← Вернуться к форме</a>
    </div>

    <div class="site-footer">
        <p>ЛАБОРАТОРНАЯ РАБОТА №3</p>
    </div>
</div>
</body>
</html>