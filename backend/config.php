<?php

$config = parse_ini_file(__DIR__ . '/config/config.ini', true);

if ($config === false) {
    die('Ошибка загрузки файла конфигурации');
}


$host = $config['db']['host'] ?? 'db';
$db = $config['db']['dbname'] ?? 'vol_platform';
$user = $config['db']['user'] ?? 'vol_platform';
$pass = $config['db']['password'] ?? 'vol_platform';
$charset = $config['db']['charset'] ?? 'utf8mb4';

//  PDO
$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Создание экземпляра PDO для подключения к базе данных
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Вывод ошибки при неудачном подключении к базе данных
    die('Ошибка подключения к базе данных: ' . $e->getMessage());
}
