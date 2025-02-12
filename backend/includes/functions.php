<?php

function getPopularEvents($limit) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events ORDER BY participant_count DESC LIMIT ?");
    $stmt->execute([$limit]);
    return $stmt->fetchAll();
}

// Получение деталей мероприятия
function getEventDetails($id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM events WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getFilteredEvents($limit, $offset, $sort, $location, $search = '') {
    global $pdo;

    $sql = "SELECT * FROM events WHERE 1";

    // Фильтр по местоположению
    if (!empty($location)) {
        $sql .= " AND location LIKE :location";
    }

    // Поиск по названию и описанию
    if (!empty($search)) {
        $sql .= " AND (name LIKE :search OR description LIKE :searchDescription)";
    }

    // Сортировка
  $sql .= " ORDER BY $sort";

    // Лимит смещение
    $sql .= " LIMIT :limit OFFSET :offset";

    $stmt = $pdo->prepare($sql);

    if (!empty($location)) {
        $stmt->bindValue(':location', '%' . $location . '%', PDO::PARAM_STR);
    }
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . strtolower(trim($search)) . '%', PDO::PARAM_STR);
        $stmt->bindValue(':searchDescription', '%' . strtolower(trim($search)) . '%', PDO::PARAM_STR);
    }
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);

    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function getTotalEventsCount($location = '', $search = '') {
    global $pdo;

    $sql = "SELECT COUNT(*) FROM events WHERE 1";

    // Фильтр по местоположению
    if (!empty($location)) {
        $sql .= " AND location LIKE :location";
    }

    // Поиск по имени или описанию
    if (!empty($search)) {
        $sql .= " AND (name LIKE :search OR description LIKE :searchDescription)";
    }

    $stmt = $pdo->prepare($sql);

    if (!empty($location)) {
        $stmt->bindValue(':location', '%' . $location . '%', PDO::PARAM_STR);
    }
    if (!empty($search)) {
        $stmt->bindValue(':search', '%' . $search . '%', PDO::PARAM_STR);
        $stmt->bindValue(':searchDescription', '%' . strtolower(trim($search)) . '%', PDO::PARAM_STR);
    }

    $stmt->execute();
    return (int)$stmt->fetchColumn();
}

function deleteEvent($pdo, $eventId, $userId) {

    $event = getEventDetails($eventId);


    if (!$event) {
        return ['status' => false, 'message' => 'Мероприятие не найдено.'];
    }

    // Проверка прав на удаление
    if ($event['created_by'] != $userId) {
        return ['status' => false, 'message' => 'У вас нет прав для удаления этого мероприятия.'];
    }

    try {
        $pdo->beginTransaction();

        // Удаление участников мероприятия
        $stmt = $pdo->prepare("DELETE FROM event_participants WHERE event_id = ?");
        $stmt->execute([$eventId]);

        // Удаление комментариев
        $stmt = $pdo->prepare("DELETE FROM comments WHERE event_id = ?");
        $stmt->execute([$eventId]);

        // Удаление самого мероприятия
        $stmt = $pdo->prepare("DELETE FROM events WHERE id = ?");
        $stmt->execute([$eventId]);

        $pdo->commit();
        return ['status' => true, 'message' => 'Мероприятие успешно удалено.'];
    } catch (Exception $e) {
        $pdo->rollBack();
        return ['status' => false, 'message' => 'Ошибка при удалении: ' . $e->getMessage()];
    }
}
// список для фильтрации
function getUniqueLocations() {
    global $pdo;
    $stmt = $pdo->query("SELECT DISTINCT location FROM events WHERE location IS NOT NULL ORDER BY location");
    return $stmt->fetchAll(PDO::FETCH_COLUMN);
}


// Подключение Bootstrap
function includeBootstrap() {
    echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">';
}
function includeCustomCSS($path) {
    echo '<link rel="stylesheet" href="' . htmlspecialchars($path) . '">';
}

function includeBootstrapJS() {
    echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>';
}