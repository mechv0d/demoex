<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}
require_once 'connect.php';
$db = (new Database())->getConn();
$user_id = $_SESSION['user_id'];

// обпаботка отправки отзыва
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'], $_POST['form_id'])) {
    $feedback = trim($_POST['feedback']);
    $form_id = (int)$_POST['form_id'];
    // проверка
    $stmt = $db->prepare('SELECT user_feedback FROM edu_form WHERE id = ? AND user_fk = ?');
    $stmt->execute([$form_id, $user_id]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($row && empty($row['user_feedback']) && $feedback !== '') {
        $upd = $db->prepare('UPDATE edu_form SET user_feedback = ? WHERE id = ?');
        $upd->execute([$feedback, $form_id]);
    }
    header('Location: forms.php');
    exit();
}
// получаеим завяки прользователя
$stmt = $db->prepare('SELECT * FROM edu_form WHERE user_fk = ? ORDER BY id DESC');
$stmt->execute([$user_id]);
$forms = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Заявки</title>
</head>
<body>
    <a href="index.php">На главную</a>
    <h1>Просмотр заявок</h1>
    <?php if (empty($forms)): ?>
        <p>У вас нет заявок.</p>
    <?php else: ?>
        <?php foreach ($forms as $form): ?>
            <div style="border:1px solid #ccc; padding:10px; margin-bottom:10px;">
                <strong>Курс:</strong> <?= htmlspecialchars($form['course_name']) ?><br>
                <strong>Дата:</strong> <?= htmlspecialchars($form['target_date']) ?><br>
                <strong>Тип оплаты:</strong> <?= $form['pay_type'] == 1 ? 'СБП' : 'Наличные' ?><br>
                <strong>Статус:</strong> <?php
                    if ($form['view_status'] === null) echo 'Неизвестно';
                    elseif ($form['view_status'] == 0) echo 'Новая';
                    elseif ($form['view_status'] == 1) echo 'Обучение идет';
                    elseif ($form['view_status'] == 2) echo 'Обучение завершено';
                    else echo 'Неизвестно';
                ?><br>
                <?php if (!empty($form['user_feedback'])): ?>
                    <strong>Ваш отзыв:</strong> <?= nl2br(htmlspecialchars($form['user_feedback'])) ?>
                <?php else: ?>
                    <form method="post" style="margin-top:10px;">
                        <input type="hidden" name="form_id" value="<?= $form['id'] ?>">
                        <label>Оставьте отзыв:<br>
                            <textarea name="feedback" rows="2" cols="40" required></textarea>
                        </label><br>
                        <button type="submit">Отправить</button>
                    </form>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
