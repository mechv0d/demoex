<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Регистрация</title>
</head>
<body>
<a href="index.php">На главную</a>
<h1>Страница регистрации</h1>
<?php
session_start();
require_once 'connect.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->getConn();
    $user = new User($db);
    $user->login = trim($_POST['login'] ?? '');
    $user->passwd = trim($_POST['passwd'] ?? '');
    $user->fio = trim($_POST['fio'] ?? '');
    $user->phonenum = substr(trim($_POST['phonenum'] ?? ''), 2);
    $user->email = trim($_POST['email'] ?? '');

    // Валидация
    if (strlen($user->login) < 6) {
        $message = 'Логин должен быть не менее 6 символов.';
    } elseif (empty($user->passwd) || empty($user->fio) || empty($user->phonenum) || empty($user->email)) {
        $message = 'Все поля обязательны для заполнения.';
    } elseif (!preg_match('/^\d{10}$/', $user->phonenum)) {
        $message = "Телефон должен содержать 10 цифр. {$user->phonenum}";
    } elseif (!filter_var($user->email, FILTER_VALIDATE_EMAIL)) {
        $message = 'Некорректный email.';
    } elseif ($user->exists()) {
        $message = 'Пользователь с таким логином или email уже существует.';
    } else {
        if ($user->register()) {
            // получаем admin_right и ID нового пользователя
            $query = "SELECT admin_right, id FROM user WHERE login = :login LIMIT 1";
            $stmt = $db->prepare($query);
            $stmt->bindParam(':login', $user->login);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['login'] = $user->login;
            $_SESSION['admin_right'] = $row ? $row['admin_right'] : 0;
            header('Location: index.php');
            exit;
        } else {
            $message = 'Ошибка регистрации.';
        }
    }
}
?>
<?php if ($message): ?>
    <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<form method="post">
    <label>Логин: <input type="text" name="login" required></label><br>
    <label>Пароль: <input type="password" name="passwd" required></label><br>
    <label>ФИО: <input type="text" name="fio" required></label><br>
    <label>Телефон: <input type="text" name="phonenum" required></label><br>
    <label>Email: <input type="email" name="email" required></label><br>
    <button type="submit">Зарегистрироваться</button>
</form>
</body>
</html>
