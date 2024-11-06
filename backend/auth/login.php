<?php
session_start();
require_once '../config.php';
include_once '../includes/navbar.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // налич пользователя в бд
    $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();
    
    // if ($user) {
    //     echo "Найден пользователь с ID: " . $user['id'] . "<br>";
    //     echo "Пароль из базы данных: " . $user['password'] . "<br>";
    // } else {
    //     echo "Пользователь не найден.";
    // }


    // если пользователь + и пароль +
    if ($user && password_verify($password, $user['password'])) {
    
    
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // if ($user && $password === $user['password']) {
        //     echo "Пароли совпадают";

        // } else {
        //     echo "Пароли не совпадают";
        // }
        if ($user['role'] == 'volunteer') {
            header("Location: ../profile/volunteer.php");
        } elseif ($user['role'] == 'organizer') {
            header("Location: ../profile/organizer.php");
        }
        exit;
    } else {
        $errors[] = "Неправильное имя пользователя или пароль.";
    }
 }
?>

<div class="container mt-5">
    <h2>Вход</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Имя пользователя</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
    <p class="mt-3">
        Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a>
    </p>
</div>

<?php include_once '../includes/footer.php'; ?>