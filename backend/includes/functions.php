<?php
include_once('config.php');

function getPopularEvents($limit) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY participant_count DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

//  все мероприятия
function getAllEvents() {
    global $pdo;
    $stmt = $pdo->query("SELECT * FROM events ORDER BY date ASC");
    return $stmt->fetchAll();
}

//  получение дет мероприятия
function getEventDetails($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
