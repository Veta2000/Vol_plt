<?php
// require __DIR__ . '../../vendor/autoload.php';
// session_start();
// use Palmo\Core\service\Db;

// // Обробка форми входу
// if($_SERVER["REQUEST_METHOD"] == "POST") {
//         $username = $_POST['username'];
//         $password = $_POST['password'];

//         $db = new Db();
//         $dbh = $db->getHandler();
//         $stmt = $dbh->prepare("SELECT * FROM users WHERE username = :username");
//         $stmt->bindParam(':username', $username);
//         $stmt->execute();
//         $user = $stmt->fetch();

//         if(!empty($user)) {
//             if($password === $user['password']) {
//                 $_SESSION['user_id'] = $user['id'];
//                 $_SESSION['username'] = $user['username'];
//                 header("Location: dashboard.php");
//                 exit();
//             } else {
//                 $_SESSION['error'] = "Невірний пароль";
//                 header("Location: /");
//                 exit();
//             }
//         } else {
//             $_SESSION['error'] = "Невірний логін";
//             header("Location: /");
//             exit();
//         }
//     }
