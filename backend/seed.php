<?php

require 'vendor/autoload.php';
require_once 'config.php'; 

$faker = Faker\Factory::create();

// подключение к бд
try {
    $pdo = new PDO("mysql:host={$config['db']['host']};dbname={$config['db']['dbname']}", $config['db']['user'], $config['db']['password']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Ошибка подключения к базе данных: " . $e->getMessage());
}

// // пароль 
$password = password_hash('ComplexPass123!', PASSWORD_DEFAULT);

// // пользователи
for ($i = 0; $i < 1000; $i++) {
    $role = $i < 500 ? 'volunteer' : 'organizer';
    $username = $faker->unique()->userName;
    $email = $faker->unique()->email;
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
    $userdt = [$username, $email, $password, $role];
    $stmt->execute($userdt);
}
echo "500 волонтеров и 500 организаторов успешно созданы.\n";
$organizerIds = $pdo->query("SELECT id FROM users WHERE role = 'organizer'")->fetchAll(PDO::FETCH_COLUMN);

// мероприятия
$eventCount = 0;

foreach ($organizerIds as $organizerId) {
    $eventsToCreate = rand(0, 10);
    for ($j = 0; $j < $eventsToCreate; $j++) {
        $stmt = $pdo->prepare("INSERT INTO events (name, description, event_date, location, participant_count, created_by, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $faker->sentence(3),
            $faker->paragraph,
            $faker->dateTimeBetween('-1 year', '+1 year')->format('Y-m-d'),
            $faker->city,
            rand(10, 100),
            rand(1, 5),
            $organizerId,
            $faker->dateTimeThisYear()->format('Y-m-d H:i:s')
        ]);
        $eventCount++;
        if ($eventCount >= 1000) break 2;
    }
}
echo "1000 мероприятий успешно созданы.\n";

$eventIds = $pdo->query("SELECT id FROM events")->fetchAll(PDO::FETCH_COLUMN);
$usersId = $pdo->query("SELECT id FROM users")->fetchAll(PDO::FETCH_COLUMN);

// // комментарии
for ($i = 0; $i < 1000; $i++) {
    $stmt = $pdo->prepare("INSERT INTO comments (event_id, user_id, comment_text, created_at) VALUES (?, ?, ?, ?)");
    $stmt->execute([
        $faker->randomElement($eventIds), 
        $faker->randomElement($usersId),  
        $faker->sentence,
        $faker->dateTimeThisYear()->format('Y-m-d H:i:s')
    ]);
}
echo "1000 комментариев успешно созданы.\n";

// присоединение пользователей к мероприятиям

$volunteerIds = $pdo->query("SELECT id FROM users WHERE role = 'volunteer'")->fetchAll(PDO::FETCH_COLUMN);

for ($i = 0; $i < 1000; $i++) {
    $stmt = $pdo->prepare("INSERT INTO event_participants (event_id, user_id, registration_date) VALUES (?, ?, ?)");
    $stmt->execute([
        $faker->randomElement($eventIds),
        $faker->randomElement($volunteerIds),
        $faker->dateTimeThisYear()->format('Y-m-d H:i:s')
    ]);
}
echo "Записи о присоединении волонтеров к мероприятиям успешно созданы.\n";
