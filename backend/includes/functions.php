<?php


function getPopularEvents($limit) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY participant_count DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}



//  получение дет мероприятия
function getEventDetails($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}


function getFilteredEvents($limit, $offset, $sort, $location) {
    global $pdo;

    $query = "SELECT * FROM events WHERE 1=1";
    
    if (!empty($location)) {
        $query .= " AND location LIKE :location";
    }
    
    $query .= " ORDER BY $sort LIMIT :limit OFFSET :offset";
    
    $stmt = $pdo->prepare($query);
    if (!empty($location)) {
        $stmt->bindValue(':location', "%$location%", PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalEventsCount($location = '') {
    global $pdo;

    $query = "SELECT COUNT(*) FROM events WHERE 1=1";

    if (!empty($location)) {
        $query .= " AND location LIKE :location";
    }

    $stmt = $pdo->prepare($query);

    if (!empty($location)) {
        $stmt->bindValue(':location', "%$location%", PDO::PARAM_STR);
    }

    $stmt->execute();
    return (int)$stmt->fetchColumn();
}
