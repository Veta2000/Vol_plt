<?php
session_start();
require_once '../config.php';
require_once '../includes/validators/Validator.php';
include_once '../includes/functions.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $remember = isset($_POST['remember']);

    $validator = new Validator();
    $validator->addValidator('username', 'string');
    $validator->addValidator('password', 'string');

    // Собр данные для валидации
    $data = [
        'username' => $username,
        'password' => $password
    ];


    if ($validator->validate($data)) {
        // Поиск пользователя в бд
        $stmt = $pdo->prepare("SELECT id, password, role FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();


        // Проверка пароля
        if ($user && password_verify($password, $user['password'])) {
            // Сохр в сессии
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];

            // Проверка на ЗМ
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('remember_token', $token, time() + (86400 * 30), "/"); 

                // Обнов токен в бд
                $stmt = $pdo->prepare("UPDATE users SET remember_token = ? WHERE id = ?");
                $stmt->execute([$token, $user['id']]);
            }
           
            // Перенаправление на профиль
            if ($user['role'] === 'volunteer') {
                header("Location: ../profile/volunteer.php");
                exit;
            } elseif ($user['role'] === 'organizer') {
                header("Location: ../profile/organizer.php");
                exit;
            }
            
        } else {
            $errors[] = "Неправильное имя пользователя или пароль.";
        }
    } else {
        $errors = $validator->getErrors();
    }
}

include_once '../includes/navbar.php';
?>

<div class="container mt-5">
    <h2>Вход</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $field => $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="login.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Имя пользователя</label>
            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= htmlspecialchars($username ?? ''); ?>" required>
            <?php if (isset($errors['username'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['username']); ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
            <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['password']); ?></div>
            <?php endif; ?>
        </div>
        <div class="form-check mb-3">
            <input type="checkbox" class="form-check-input" id="remember" name="remember">
            <label class="form-check-label" for="remember">Запомнить меня</label>
        </div>
        <button type="submit" class="btn btn-primary">Войти</button>
    </form>
    <p class="mt-3">
        Нет аккаунта? <a href="register.php">Зарегистрируйтесь</a>
    </p>
</div>

<?php include_once '../includes/footer.php'; ?>
