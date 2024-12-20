<?php
session_start();
include_once '../includes/functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    include '../404.php'; 
    exit;
}

require_once '../config.php';

$stmt = $pdo->prepare("SELECT * FROM events WHERE created_by = ?");
$stmt->execute([$_SESSION['user_id']]);
$events = $stmt->fetchAll();

include_once '../includes/navbar.php';
?>


<div class="container mt-5">
    <h2>Мои мероприятия</h2>
    
    <?php if (isset($_SESSION['success_message'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['success_message']); ?>
        <?php unset($_SESSION['success_message']); ?>
    </div>
<?php endif; ?>

<?php if (isset($_SESSION['error_message'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['error_message']); ?>
        <?php unset($_SESSION['error_message']); ?>
    </div>
<?php endif; ?>

    <a href="../events/create.php" class="btn btn-primary mb-3">Создать новое мероприятие</a>
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
