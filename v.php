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
         .table-wrapper {
            overflow-x: auto;
            margin: 20px 0;
            border-radius: 20px;
            background: rgba(0,0,0,0.2);
            padding: 5px;
        }
        .anketa-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.9rem;
            min-width: 900px;
        }
        .anketa-table th, .anketa-table td {
            padding: 12px 8px;
            text-align: left;
            border-bottom: 1px solid #3a3a3e;
            vertical-align: top;
        }
        .anketa-table th {
            background: #252530;
            color: #ffcc88;
            font-weight: 600;
            position: sticky;
            top: 0;
        }
        .anketa-table tr:hover td {
            background: rgba(90,124,158,0.2);
        }
        .anketa-table td:first-child,
        .anketa-table th:first-child {
            padding-left: 15px;
        }
        .anketa-table td:last-child,
        .anketa-table th:last-child {
            padding-right: 15px;
        }
        /* Ограничение ширины для длинных полей */
        .anketa-table td:nth-child(2) { max-width: 200px; word-break: break-word; } /* ФИО */
        .anketa-table td:nth-child(3) { max-width: 130px; } /* Телефон */
        .anketa-table td:nth-child(4) { max-width: 180px; word-break: break-word; } /* Email */
        .anketa-table td:nth-child(7) { max-width: 250px; word-break: break-word; } /* Языки */
        .anketa-table td:nth-child(8) { max-width: 300px; word-break: break-word; } /* Биография */
        @media (max-width: 768px) {
            .anketa-table {
                font-size: 0.8rem;
            }
            .anketa-table th, .anketa-table td {
                padding: 8px 5px;
            }
        }
        .badge {
            display: inline-block;
            background: #2c5f2d;
            padding: 2px 8px;
            border-radius: 20px;
            font-size: 0.75rem;
        }
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