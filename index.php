<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
    session_unset();
    session_destroy();
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Корочки.Есть</title>
</head>
<body>
<?php if (!empty($_SESSION['login'])): ?>
    <p>Здравствуйте, <?php echo htmlspecialchars($_SESSION['login']); ?> (<?php echo htmlspecialchars($_SESSION['user_id'])?>)!</p>
    <form method="post" style="display:inline;">
        <button type="submit" name="logout">Выйти</button>
    </form>
<?php endif; ?>
    <h1>Корочки ЕСТЬ</h1>
    <ul>
        <li><a href="register.php">Регистрация</a></li>
        <li><a href="login.php">Авторизация</a></li>
        <li><a href="forms.php">Просмотр заявок</a></li>
        <li><a href="create_eduform.php">Создать заявку</a></li>
        <li><a href="admin.php">Админ-панель</a></li>
    </ul>
</body>
</html>
