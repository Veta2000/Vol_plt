<?php
session_start();
include_once '../config.php';
include_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userId = $_SESSION['user_id'] ?? null;
    if (!$userId) {
        header("Location: /login.php");
        exit;
    }

    $eventId = $_POST['event_id'];
    $commentText = $_POST['commentText'];
    $rating = $_POST['rating'];
    $eventDate = $_POST['eventDate'];
    $experience = $_POST['experience'] ?? '';
    $likedAspects = isset($_POST['liked_aspects']) ? json_encode($_POST['liked_aspects']) : '[]';
    $recommend = isset($_POST['recommend']) ? 1 : 0;
    $accessibility = $_POST['accessibility'] ?? 'average';

    // Обработка загруженных изображений
    $uploadedImages = [];
    if (!empty($_FILES['images']['name'][0])) {
        foreach ($_FILES['images']['tmp_name'] as $key => $tmp_name) {
            $fileName = basename($_FILES['images']['name'][$key]);
            $targetPath = "../uploads/" . $fileName;
            if (move_uploaded_file($tmp_name, $targetPath)) {
                $uploadedImages[] = $fileName;
            }
        }
    }
    $imagesJson = json_encode($uploadedImages);

    // В бд
    $stmt = $pdo->prepare("
        INSERT INTO comments (event_id, user_id, comment_text, rating, event_date, experience, liked_aspects, recommend, accessibility, images, created_at) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())
    ");
    $stmt->execute([
        $eventId, 
        $userId, 
        $commentText, 
        $rating, 
        $eventDate, 
        $experience, 
        $likedAspects, 
        $recommend, 
        $accessibility, 
        $imagesJson
    ]);

    header("Location: detail.php?id=" . $eventId);
    exit;
}