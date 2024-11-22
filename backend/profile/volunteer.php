<?php
session_start();

include_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'volunteer') {
    include '../404.php'; 
    exit;
}

require_once '../config.php';

$stmt = $pdo->prepare("SELECT e.* FROM events e JOIN event_participants er ON e.id = er.event_id WHERE er.user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll();

include_once '../includes/navbar.php';
?>

<?php ?>
<div class="container mt-5">
    <h2>Мои мероприятия</h2>
    <div class="row">
        <?php foreach ($events as $event): ?>
            <div class="col-md-4">
                <h3><?= htmlspecialchars($event['name']) ?></h3>
                <a href="../events/detail.php?id=<?= $event['id'] ?>" class="btn btn-primary">Подробнее</a>
            </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include_once '../includes/footer.php'; ?>
