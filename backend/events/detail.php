<?php
session_start();
include_once '../includes/functions.php';
require_once '../config.php';

$eventId = $_GET['id'];
$event = getEventDetails($eventId);

// если ID мероприятия не указан событие не существует
if (!$eventId || !($event = getEventDetails($eventId))) {
    header("Location: /404.php");
    exit;
}

// проверка, пользователь авторизован
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $userRole = $_SESSION['role']; 
}

if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php"); 
    exit;
}
//проверка на логин

$eventId = $_GET['id'];
$event = getEventDetails($eventId);

$isOrganizer = $userRole === 'organizer' && $userId === $event['created_by'];

// Проверка, является ли или уже присоединился
$isVolunteer = $userRole === 'volunteer';
$stmt = $pdo->prepare("SELECT * FROM event_participants WHERE event_id = ? AND user_id = ?");
$stmt->execute([$eventId, $userId]);
$hasJoined = $stmt->fetch();

?>
<?php include_once  '../includes/navbar.php'; ?>


<div class="container mt-5">
    <h2>Детали мероприятия</h2>
    <h3><?= htmlspecialchars($event['name']); ?></h3>
    <p>Дата: <?= htmlspecialchars($event['event_date']); ?></p>
    <p>Описание: <?= htmlspecialchars($event['description']); ?></p>
    <p>Участников: <?= htmlspecialchars($event['participant_count']); ?></p>

    <?php if ($isOrganizer): ?>
        <a href="edit_event.php?id=<?= $eventId; ?>" class="btn btn-primary mt-3">Редактировать мероприятие</a>
    <a href="delete_event.php?id=<?= $eventId; ?>"  class="btn btn-danger mt-3" onclick="return confirm('Вы уверены, что хотите удалить это мероприятие?');"> Удалить мероприятие </a>
    <?php endif; ?>

    <?php if ($isVolunteer && !$hasJoined): ?>
        <form action="join_event.php" method="POST" class="mt-3">
            <input type="hidden" name="event_id" value="<?= $eventId; ?>">
            <button type="submit" class="btn btn-primary">Присоединиться</button>
            </form>
            <?php elseif ($isVolunteer): ?>
        <p class="text-success mt-3">Вы уже присоединились к этому мероприятию.</p>
    <?php endif; ?>

    <?php if ($isVolunteer && $hasJoined): ?>
        <form action="generate_certificate.php" method="POST" class="mt-3">
            <input type="hidden" name="event_id" value="<?= $eventId; ?>">
            <button type="submit" class="btn btn-primary">Сгенерировать сертификат</button>
        </form>
    <?php endif; ?>

</div>


<h3 class="container mt-5">Комментарии</h3>
   

    <h4 class="container mt-5">Оставить комментарий</h4>
    <form action="comment.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="event_id" value="<?php echo $eventId; ?>">

        <div class="container mt-5">
            <label for="commentText" class="form-label">Комментарий</label>
            <textarea class="form-control" id="commentText" name="commentText" required></textarea>
        </div>

        <div class="container mt-5">
            <label for="rating" class="form-label">Рейтинг (от 1 до 10)</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="10" required>
        </div>

        <div class="container mt-5">
            <label for="eventDate" class="form-label">Дата участия</label>
            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
        </div>

        <div class="container mt-5">
            <label for="experience" class="form-label">Ваш опыт </label>
            <textarea class="form-control" id="experience" name="experience"></textarea>
        </div>

        <div class="container mt-5">
            <label for="images" class="form-label">Загрузить фото (до 3)</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
        </div>

        <div class="container mt-5">
            <label class="form-label">Выберите, что вам понравилось (мультивыбор)</label><br>
            <select class="form-select" name="liked_aspects[]" multiple>
                <option value="organization">Организация</option>
                <option value="people">Люди</option>
                <option value="environment">Окружение</option>
            </select>
        </div>

        <div class="container mt-5">
            <label>Рекомендовать </label>
            <input type="checkbox" name="recommend" value="yes"> Да
            <input type="checkbox" name="recommend" value="no"> Нет
        </div>

        <div class="container mt-5">
            <label>Оценка по доступности</label><br>
            <input type="radio" name="accessibility" value="good"> Хорошо
            <input type="radio" name="accessibility" value="average"> Средне
            <input type="radio" name="accessibility" value="poor"> Плохо
        </div>

        <button type="submit" class="btn btn-primary">Отправить комментарий</button>
    </form>
</div>

<div class="comments">
<?php
$stmt = $pdo->prepare("SELECT * FROM comments WHERE event_id = ?");
$stmt->execute([$eventId]);
$comments = $stmt->fetchAll();

foreach ($comments as $comment) {
    echo "<div class='comment'>";
    echo "<p><strong>Рейтинг:</strong> " . htmlspecialchars($comment['rating']) . "</p>";
    echo "<p><strong>Опыт:</strong> " . htmlspecialchars($comment['experience']) . "</p>";
    echo "<p><strong>Рекомендация:</strong> " . ($comment['recommend'] ? "Да" : "Нет") . "</p>";
    echo "<p><strong>Оценка доступности:</strong> " . htmlspecialchars($comment['accessibility']) . "</p>";
    echo "<p><strong>Комментарий:</strong> " . htmlspecialchars($comment['comment_text']) . "</p>";
    // Вывод фото
    $images = json_decode($comment['images'], true);
    if ($images) {
        foreach ($images as $image) {
            echo "<img src='../uploads/" . htmlspecialchars($image) . "' alt='Фото' style='width:100px;'>";
        }
    }
    echo "</div><hr>";
}
?> 
</div>

<?php include_once '../includes/footer.php'; ?>