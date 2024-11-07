<?php
session_start();
include_once '../config.php';
include_once '../includes/functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../404.php");
    exit();
}


$limit = 2;
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
// Расчет смещения
$offset = ($page - 1) * $limit;

$events = getAllEvents($limit, $offset);
// общ кол мероприятий для паu
$totalEvents = getTotalEventsCount();
$totalPages = ceil($totalEvents / $limit);

include_once '../includes/navbar.php';
?>


<div class="container mt-5">
    <h2>Все мероприятия</h2>
    <form method="GET" class="mb-3">
        <select name="sort" class="form-control">
            <option value="date">По дате</option>
            <option value="popularity">По популярности</option>
        </select>
        <button type="submit" class="btn btn-secondary mt-2">Применить</button>
    </form>
    <div class="events">
        <?php foreach ($events as $event): ?>
            <div class="event">
                <h3><?= htmlspecialchars($event['name']); ?></h3>
                <p>Дата: <?= htmlspecialchars($event['event_date']); ?></p>
                <p>Участников: <?= htmlspecialchars($event['participant_count']); ?></p>
                <a href="detail.php?id=<?= $event['id']; ?>" class="btn btn-info">Подробнее</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
