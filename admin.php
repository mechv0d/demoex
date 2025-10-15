<?php
session_start();
require_once 'connect.php';

// Проверка прав администратора
if (!isset($_SESSION['login']) || $_SESSION['login'] !== 'Admin' || !isset($_SESSION['admin_right']) || $_SESSION['admin_right'] == 0) {
    header('Location: index.php');
    exit;
}

// Подключение к БД
$db = new Database();
$conn = $db->getConn();

// Обработка смены статуса заявки
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_id'], $_POST['new_status'])) {
    $form_id = (int)$_POST['form_id'];
    $new_status = (int)$_POST['new_status'];
    $stmt = $conn->prepare('UPDATE edu_form SET view_status = :status WHERE id = :id');
    $stmt->execute([':status' => $new_status, ':id' => $form_id]);
    header('Location: admin.php');
    exit;
}

// Получение всех заявок с данными пользователя
$stmt = $conn->query('SELECT ef.*, u.fio, u.login FROM edu_form ef JOIN user u ON ef.user_fk = u.id ORDER BY ef.id DESC');
$forms = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Статусы заявок
$status_labels = [
    0 => 'Новая',
    1 => 'Идет обучение',
    2 => 'Обучение завершено'
];
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <title>Админ-панель</title>
    <style>
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ccc; padding: 8px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <a href="index.php">На главную</a>
    <h1>Страница админ-панели</h1>
    <h2>Заявки пользователей</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Пользователь</th>
            <th>Курс</th>
            <th>Дата</th>
            <th>Тип оплаты</th>
            <th>Статус</th>
            <th>Действие</th>
        </tr>
        <?php foreach ($forms as $form): ?>
        <tr>
            <td><?= htmlspecialchars($form['id']) ?></td>
            <td><?= htmlspecialchars($form['fio']) ?> (<?= htmlspecialchars($form['login']) ?>)</td>
            <td><?= htmlspecialchars($form['course_name']) ?></td>
            <td><?= htmlspecialchars($form['target_date']) ?></td>
            <td><?= $form['pay_type'] == 1 ? 'СБП' : 'Наличные' ?></td>
            <td><?= $status_labels[$form['view_status'] ?? 0] ?></td>
            <td>
                <form method="post" style="display:inline;">
                    <input type="hidden" name="form_id" value="<?= $form['id'] ?>">
                    <select name="new_status">
                        <?php foreach ($status_labels as $key => $label): ?>
                            <option value="<?= $key ?>" <?= ($form['view_status'] == $key) ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit">Сменить</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>
