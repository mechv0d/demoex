<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

require_once 'connect.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = trim($_POST['course_name'] ?? '');
    $start_date = $_POST['start_date'] ?? '';
    $payment_method = $_POST['payment_method'] ?? '';
    $pay_type = ($payment_method === 'cash') ? 0 : 1;
    $user_fk = $_SESSION['user_id'];

    if ($course_name && $start_date && ($payment_method === 'cash' || $payment_method === 'phone_transfer')) {
        $db = (new Database())->getConn();
        $eduForm = new EduForm($db);
        $eduForm->course_name = $course_name;
        $eduForm->target_date = $start_date;
        $eduForm->pay_type = $pay_type;
        $eduForm->user_fk = $user_fk;
        if ($eduForm->create()) {
            $message = 'Заявка успешно отправлена на рассмотрение.';
        } else {
            $message = 'Ошибка при создании заявки. Попробуйте позже.';
        }
    } else {
        $message = 'Пожалуйста, заполните все поля корректно.';
    }
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Создать заявку</title>
</head>
<body>
<h1>Создание заявки</h1>
<a href="index.php" >На главную</a>
<?php if ($message): ?>
    <p><?= htmlspecialchars($message) ?></p>
<?php endif; ?>
<form method="post">
    <label>Наименование курса: <input type="text" name="course_name" required></label><br>
    <label>Желаемая дата начала обучения: <input type="date" name="start_date" required></label><br>
    <label>Способ оплаты:
        <select name="payment_method" required>
            <option value="cash">Наличными</option>
            <option value="phone_transfer">Перевод по номеру телефона</option>
        </select>
    </label><br>
    <button type="submit">Отправить</button>
</form>
</body>
</html>
