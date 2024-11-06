<?php
include_once '../includes/navbar.php';
include_once '../includes/functions.php';

$events = getAllEvents();

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
                <p>Дата: <?= htmlspecialchars($event['date']); ?></p>
                <p>Участников: <?= htmlspecialchars($event['participants']); ?></p>
                <a href="detail.php?id=<?= $event['id']; ?>" class="btn btn-info">Подробнее</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once '../includes/footer.php'; ?>
