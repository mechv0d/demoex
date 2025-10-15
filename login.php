<?php
session_start();
require_once 'connect.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $db = (new Database())->getConn();
    $user = new User($db);
    $user->login = trim($_POST['login'] ?? '');
    $user->passwd = trim($_POST['passwd'] ?? '');
    if ($user->login() === true) {
        $query = "SELECT admin_right, id FROM user WHERE login = :login LIMIT 1";
        $stmt = $db->prepare($query);
        $stmt->bindParam(':login', $user->login);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['user_id'] = $row ? $row['id'] : null;
        $_SESSION['login'] = $user->login;
        $_SESSION['admin_right'] = $row ? $row['admin_right'] : 0;
        // редирект на главную или admin.php (зависит от крутости юзера)
        if ($_SESSION['admin_right']) {
            header('Location: admin.php');
            exit;
        } else {
            header('Location: index.php');
            exit;
        }
    } else {
        $message = 'Неверный логин или пароль.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Авторизация</title>
</head>
<body>
<a href="index.php">На главную</a>
<h1>Страница авторизации</h1>
<?php if ($message): ?>
    <p style="color:red;"><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>
<form method="post">
    <label>Логин: <input type="text" name="login" required></label><br>
    <label>Пароль: <input type="password" name="passwd" required></label><br>
    <button type="submit">Войти</button>
</form>
<p><a href="register.php">Еще не зарегистрированы? Регистрация</a></p>
</body>
</html>
