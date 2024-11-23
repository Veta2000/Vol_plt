<?php
include_once '../includes/functions.php';
require_once '../config.php';
require_once '../includes/validators/EventFormatter.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['role'];
}

$eventId = $_GET['id'];
$event = getEventDetails($eventId);

if (!$event || ($userRole !== 'organizer' || $userId !== $event['created_by'])) {
    header("Location: /404.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $formatter = new EventFormatter();

    $name = $_POST['name'];
    $eventDate = $_POST['event_date'];
    $description = $_POST['description'];

    // Форматирование данных перед записью
    [$formattedName, $formattedDate] = $formatter->formatEvent($name, $eventDate);

    $stmt = $pdo->prepare("UPDATE events SET name = ?, event_date = ?, description = ? WHERE id = ?");
    $stmt->execute([$formattedName, $formattedDate, $description, $eventId]);

    header("Location: detail.php?id=" . $eventId);
    exit;
}
?>

<?php include_once '../includes/navbar.php'; ?>

<div class="container mt-5">
    <h2>Редактирование мероприятия</h2>
    <form action="edit_event.php?id=<?= $eventId; ?>" method="POST">
        <div class="mb-3">
            <label for="name" class="form-label">Название</label>
            <input type="text" class="form-control" id="name" name="name" value="<?= htmlspecialchars($event['name']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="event_date" class="form-label">Дата</label>
            <input type="date" class="form-control" id="event_date" name="event_date" value="<?= htmlspecialchars($event['event_date']); ?>" required>
        </div>
        <div class="mb-3">
            <label for="description" class="form-label">Описание</label>
            <textarea class="form-control" id="description" name="description" required><?= htmlspecialchars($event['description']); ?></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Сохранить изменения</button>
    </form>
</div>

<?php include_once '../includes/footer.php'; ?>
