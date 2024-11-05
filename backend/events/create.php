<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'organizer') {
    header("Location: ../auth/login.php");
    exit;
}

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $date = $_POST['date'];
    $organizer_id = $_SESSION['user_id'];

    $stmt = $pdo->prepare("INSERT INTO events (title, description, date, organizer_id) VALUES (?, ?, ?, ?)");
    $stmt->execute([$title, $description, $date, $organizer_id]);

    header("Location: ../profile/organizer.php");
    exit;
}
?>

<?php include_once '../includes/header.php'; ?>
<div class="container mt-5">
    <h2>Создать новое мероприятие</h2>
    <form method="POST" action="">
        <div class="form-group">
            <label>Название</label>
            <input type="text" name="title" class="form-control" required>
        </div>
        <div class="form-group">
            <label>Описание</label>
            <textarea name="description" class="form-control" required></textarea>
        </div>
        <div class="form-group">
            <label>Дата</label>
            <input type="date" name="date" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Создать</button>
    </form>
</div>
<?php include_once '../includes/footer.php'; ?>
