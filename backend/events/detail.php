<?php
include_once '../includes/header.php';
include_once '../includes/navbar.php';
include_once '../includes/functions.php';

$eventId = $_GET['id'];
$event = getEventDetails($eventId);

?>

<div class="container mt-5">
    <h2>Детали мероприятия</h2>
    <h3><?= htmlspecialchars($event['name']); ?></h3>
    <p>Дата: <?= htmlspecialchars($event['date']); ?></p>
    
    <p>Описание: <?= htmlspecialchars($event['description']); ?></p>
    <p>Участников: <?= htmlspecialchars($event['participants']); ?></p>
</div>

<h3 class="mt-4">Комментарии</h3>
   

    <h4 class="mt-4">Оставить комментарий</h4>
    <form action="comment.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">

        <div class="mb-3">
            <label for="commentText" class="form-label">Комментарий</label>
            <textarea class="form-control" id="commentText" name="commentText" required></textarea>
        </div>

        <div class="mb-3">
            <label for="rating" class="form-label">Рейтинг (от 1 до 10)</label>
            <input type="number" class="form-control" id="rating" name="rating" min="1" max="10" required>
        </div>

        <div class="mb-3">
            <label for="eventDate" class="form-label">Дата участия</label>
            <input type="date" class="form-control" id="eventDate" name="eventDate" required>
        </div>

        <div class="mb-3">
            <label for="experience" class="form-label">Ваш опыт </label>
            <textarea class="form-control" id="experience" name="experience"></textarea>
        </div>

        <div class="mb-3">
            <label for="images" class="form-label">Загрузить фото (до 3)</label>
            <input type="file" class="form-control" id="images" name="images[]" multiple accept="image/*">
        </div>

        <div class="mb-3">
            <label class="form-label">Выберите, что вам понравилось (мультивыбор)</label><br>
            <select class="form-select" name="liked_aspects[]" multiple>
                <option value="organization">Организация</option>
                <option value="people">Люди</option>
                <option value="environment">Окружение</option>
            </select>
        </div>

        <div class="mb-3">
            <label>Рекомендовать </label>
            <input type="checkbox" name="recommend" value="yes"> Да
        </div>

        <div class="mb-3">
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