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

// сортировка
$sort = isset($_GET['sort']) && $_GET['sort'] === 'popularity' ? 'participant_count' : 'event_date';

// фильтр
$location = isset($_GET['location']) ? trim($_GET['location']) : '';

$locationList = getUniqueLocations();

// Поиск
$search = isset($_GET['search']) ? trim($_GET['search']) : '';

$events = getFilteredEvents($limit, $offset, $sort, $location, $search);

// для пагинации 
$totalEvents = getTotalEventsCount($location, $search);
$totalPages = ceil($totalEvents / $limit);

// начальная, конечная стр
$visiblePages = 5;

$startPage = max(1, $page - floor($visiblePages / 2));
$endPage = min($totalPages, $startPage + $visiblePages - 1);

$startPage = max(1, $endPage - $visiblePages + 1);


include_once '../includes/navbar.php';
?>

<div class="container mt-5">
    <h2>Все мероприятия</h2>
    <form method="GET" class="mb-3">
        <div class="form-group">
            <label for="search">Поиск по названию или описанию:</label>
            <input type="text" name="search" id="search" class="form-control" value="<?= htmlspecialchars($search); ?>">
        </div>
        <div class="form-group">
            <label for="location">Фильтр по местоположению:</label>
            <select name="location" id="location" class="form-control">
                <option value="">Все местоположения</option>
                <?php foreach ($locationList as $loc): ?>
                    <option value="<?= htmlspecialchars($loc); ?>" <?= $loc === $location ? 'selected' : ''; ?>>
                        <?= htmlspecialchars($loc); ?>
                    </option>
                <?php endforeach; ?>
            </select>
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
        <?php if (count($events) > 0): ?>
            <div class="row">
                <?php foreach ($events as $event): ?>
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <h5 class="card-title"><?= htmlspecialchars($event['name']); ?></h5>
                                <p class="card-text"><strong>Дата:</strong> <?= htmlspecialchars($event['event_date']); ?></p>
                                <p class="card-text"><strong>Участников:</strong> <?= htmlspecialchars($event['participant_count']); ?></p>
                                <p class="card-text"><strong>Местоположение:</strong> <?= htmlspecialchars($event['location']); ?></p>
                                <a href="detail.php?id=<?= htmlspecialchars($event['id']); ?>" class="btn btn-info">Подробнее</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p>Мероприятий не найдено.</p>
        <?php endif; ?>
    </div>

    <!-- Пагинация -->
    <?php if ($totalPages > 1): ?>
    <nav aria-label="Page navigation" class="mt-4">
        <ul class="pagination justify-content-center">

            <!-- Назад -->
            <?php if ($page > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page - 1; ?>&sort=<?= $sort; ?>&location=<?= urlencode($location); ?>" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">&laquo;</span>
                </li>
            <?php endif; ?>

            <!-- диапазон страниц -->
            <?php if ($startPage > 1): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $startPage - 1; ?>&sort=<?= $sort; ?>&location=<?= urlencode($location); ?>">...</a>
                </li>
            <?php endif; ?>

            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <li class="page-item <?= ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?= $i; ?>&sort=<?= $sort; ?>&location=<?= urlencode($location); ?>"><?= $i; ?></a>
                </li>
            <?php endfor; ?>

            <?php if ($endPage < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $endPage + 1; ?>&sort=<?= $sort; ?>&location=<?= urlencode($location); ?>">...</a>
                </li>
            <?php endif; ?>

            <!-- вперед -->
            <?php if ($page < $totalPages): ?>
                <li class="page-item">
                    <a class="page-link" href="?page=<?= $page + 1; ?>&sort=<?= $sort; ?>&location=<?= urlencode($location); ?>" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                    </a>
                </li>
            <?php else: ?>
                <li class="page-item disabled">
                    <span class="page-link">&raquo;</span>
                </li>
            <?php endif; ?>

        </ul>
    </nav>
<?php endif; ?>

<?php include_once '../includes/footer.php'; ?>
