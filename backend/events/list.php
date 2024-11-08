<?php
session_start();
include_once '../config.php';
include_once '../includes/functions.php';

// Проверка авторизации пользователя
if (!isset($_SESSION['user_id'])) {
    header("Location: ../404.php");
    exit();
}

// Параметры пагинации
$limit = 10; 
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
if ($page < 1) {
    $page = 1;
}
$offset = ($page - 1) * $limit;

// Параметры сортировки
$sort = isset($_GET['sort']) && $_GET['sort'] === 'popularity' ? 'participant_count' : 'event_date';

// Параметры фильтрации
$location = isset($_GET['location']) ? trim($_GET['location']) : '';

// Получение данных с учетом сортировки и фильтрации
$events = getFilteredEvents($limit, $offset, $sort, $location);

// Получение общего количества мероприятий для пагинации
$totalEvents = getTotalEventsCount($location);
$totalPages = ceil($totalEvents / $limit);

include_once '../includes/navbar.php';
?>

<div class="container mt-5">
    <h2>Все мероприятия</h2>
    <form method="GET" class="mb-3">
        <div class="form-group">
            <label for="location">Фильтр по местоположению:</label>
            <input type="text" name="location" id="location" class="form-control" value="<?= htmlspecialchars($location); ?>">
        </div>
        <div class="form-group">
            <label for="sort">Сортировка:</label>
            <select name="sort" id="sort" class="form-control">
                <option value="date" <?= $sort === 'event_date' ? 'selected' : ''; ?>>По дате</option>
                <option value="popularity" <?= $sort === 'participant_count' ? 'selected' : ''; ?>>По популярности</option>
            </select>
        </div>
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

    <nav aria-label="Пагинация">
        <ul class="pagination justify-content-center mt-4">
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1 ?>&sort=<?= $sort ?>&location=<?= urlencode($location) ?>">Назад</a>
                </li>
            <?php endif; ?>
            <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?= $i === $page ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i ?>&sort=<?= $sort ?>&location=<?= urlencode($location) ?>"><?= $i ?></a>
                </li>
            <?php endfor; ?>
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1 ?>&sort=<?= $sort ?>&location=<?= urlencode($location) ?>">Вперед</a>
                </li>
            <?php endif; ?>
        </ul>
    </nav>
</div>

<?php include_once '../includes/footer.php'; ?>
