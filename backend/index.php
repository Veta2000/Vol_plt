<?php
session_start();
include_once 'includes/header.php';
include_once 'includes/functions.php';
require_once 'config.php';

$events = getPopularEvents(5); 

include_once 'includes/navbar.php';

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
    $rememberToken = $_COOKIE['remember_me'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE remember_token = :token");
    $stmt->bindParam(':token', $rememberToken);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Автоматическая аутентификация
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['role'] = $user['role'];
    }
}

?>

<div class="container mt-5">
    <h1>Добро пожаловать на платформу для волонтеров</h1>
    <p>Здесь вы можете найти интересные волонтерские мероприятия и принять участие.</p>
    <h2>Популярные мероприятия</h2>

    <div class="events">
        <?php foreach ($events as $event): ?>
            <div class="event">
                <h3><?= htmlspecialchars($event['name']); ?></h3>
                <p>Дата: <?= htmlspecialchars($event['event_date']); ?></p>
                <p>Участников: <?= htmlspecialchars($event['participant_count']); ?></p>
                <a href="events/detail.php?id=<?= $event['id']; ?>" class="btn btn-info">Подробнее</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<?php include_once 'includes/footer.php'; ?>