<?php


function getPopularEvents($limit) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY participant_count DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

//  все мероприятия
// с пагинацией
function getAllEvents($limit, $offset) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY event_date ASC LIMIT :limit OFFSET :offset");
    $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll();
}


// для подсчета общего количества мероприятий
function getTotalEventsCount() {
    global $pdo;
    $stmt = $pdo->query("SELECT COUNT(*) FROM events");
    return $stmt->fetchColumn();
}



//  получение дет мероприятия
function getEventDetails($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
