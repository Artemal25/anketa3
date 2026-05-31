<?php
header('Content-Type: text/html; charset=UTF-8');

function getDB() {
    static $pdo = null;
    if ($pdo === null) {
        $db_host = 'localhost';
        $db_user = 'u82325';
        $db_pass = '2941524';
        $db_name = 'u82325';
        try {
            $pdo = new PDO("mysql:host=$db_host;dbname=$db_name;charset=utf8mb4", $db_user, $db_pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            error_log($e->getMessage());
            die("Внутренняя ошибка сервера. Попробуйте позже.");
        }
    }
    return $pdo;
}

$allowed_languages = ['Pascal', 'C', 'C++', 'JavaScript', 'PHP', 'Python',
    'Java', 'Haskell', 'Clojure', 'Prolog', 'Scala', 'Go'];
$allowed_genders = ['male', 'female'];

$pdo = getDB();
$languages_from_db = $pdo->query("SELECT name FROM language ORDER BY name")->fetchAll(PDO::FETCH_COLUMN);
if (empty($languages_from_db)) $languages_from_db = $allowed_languages;

$form_data = [
    'full_name' => '',
    'phone' => '',
    'email' => '',
    'birth_date' => '',
    'gender' => '',
    'biography' => '',
    'contract_accepted' => false,
    'languages' => []
];
$errors = [];
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $birth_date = trim($_POST['birth_date'] ?? '');
    $gender = $_POST['gender'] ?? '';
    $biography = trim($_POST['biography'] ?? '');
    $contract_accepted = isset($_POST['contract_accepted']);
    $languages = $_POST['languages'] ?? [];

    $has_error = false;

    if (empty($full_name) || !preg_match('/^[а-яА-Яa-zA-Z\s]+$/u', $full_name) || strlen($full_name) > 150) {
        $errors['full_name'] = 'ФИО должно содержать только буквы и пробелы (макс. 150 символов).';
        $has_error = true;
    }
    if (empty($phone) || !preg_match('/^[\d\s\-\+\(\)]{6,12}$/', $phone)) {
        $errors['phone'] = 'Телефон: 6–12 цифр, разрешены +, -, (, ), пробел.';
        $has_error = true;
    }
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Введите корректный email.';
        $has_error = true;
    }
    if (empty($birth_date)) {
        $errors['birth_date'] = 'Дата рождения обязательна.';
        $has_error = true;
    } else {
        $date = DateTime::createFromFormat('Y-m-d', $birth_date);
        if (!$date || $date->format('Y-m-d') !== $birth_date || $date > new DateTime('today')) {
            $errors['birth_date'] = 'Некорректная дата (ГГГГ-ММ-ДД, не позже сегодня).';
            $has_error = true;
        }
    }
    if (empty($gender) || !in_array($gender, $allowed_genders)) {
        $errors['gender'] = 'Выберите пол.';
        $has_error = true;
    }
    if (strlen($biography) > 10000) {
        $errors['biography'] = 'Биография не должна превышать 10000 символов.';
        $has_error = true;
    }
    if (!$contract_accepted) {
        $errors['contract_accepted'] = 'Необходимо подтвердить согласие.';
        $has_error = true;
    }
    if (empty($languages)) {
        $errors['languages'] = 'Выберите хотя бы один язык.';
        $has_error = true;
    } else {
        foreach ($languages as $lang) {
            if (!in_array($lang, $allowed_languages)) {
                $errors['languages'] = 'Выбран недопустимый язык.';
                $has_error = true;
                break;
            }
        }
    }

    if (!$has_error) {
        try {
            $pdo = getDB();
            $pdo->beginTransaction();

            $stmt = $pdo->prepare("INSERT INTO application (full_name, phone, email, birth_date, gender, biography, contract_accepted) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$full_name, $phone, $email, $birth_date, $gender, $biography, (int)$contract_accepted]);
            $app_id = $pdo->lastInsertId();

            $lang_map = [];
            $stmt = $pdo->query("SELECT id, name FROM language");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $lang_map[$row['name']] = $row['id'];
            }
            $stmt = $pdo->prepare("INSERT INTO application_language (application_id, language_id) VALUES (?, ?)");
            foreach ($languages as $lang_name) {
                if (isset($lang_map[$lang_name])) {
                    $stmt->execute([$app_id, $lang_map[$lang_name]]);
                }
            }
            $pdo->commit();

            $success_message = '✅ Данные успешно сохранены!';
            $form_data = [
                'full_name' => '',
                'phone' => '',
                'email' => '',
                'birth_date' => '',
                'gender' => '',
                'biography' => '',
                'contract_accepted' => false,
                'languages' => []
            ];
        } catch (Exception $e) {
            $pdo->rollBack();
            $errors['db'] = 'Ошибка сохранения: ' . $e->getMessage();
            $form_data = compact('full_name', 'phone', 'email', 'birth_date', 'gender', 'biography', 'contract_accepted', 'languages');
        }
    } else {
        $form_data = compact('full_name', 'phone', 'email', 'birth_date', 'gender', 'biography', 'contract_accepted', 'languages');
    }
}

include 'anketa.php';