<?php
session_start();
require_once '../includes/functions.php';
require_once '../config.php';

// Проверка авторизации
if (!isset($_SESSION['user_id'])) {
    header("Location: /auth/login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$userRole = $_SESSION['role'];

// Проверка роли пользователя
if ($userRole !== 'organizer') {
    header("Location: /404.php");
    exit;
}

// Проверка наличия ID мероприятия
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: /404.php");
    exit;
}

$eventId = $_GET['id'];

// Вызов функции удаления
$result = deleteEvent($pdo, $eventId, $userId);

if ($result['status']) {
    $_SESSION['success_message'] = $result['message'];
} else {
    $_SESSION['error_message'] = $result['message'];
}


header("Location: /profile/organizer.php");
exit;
?>
