<?php
require_once '../config.php';
require_once '../includes/validators/Validator.php';
include_once '../includes/functions.php';

session_start();
$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    $validator = new Validator();
    $validator->addValidator('username', 'string');
    $validator->addValidator('email', 'string');
    $validator->addValidator('password', 'string');
    $validator->addValidator('role', 'string');

    $data = [
        'username' => $username,
        'email' => $email,
        'password' => $password,
        'role' => $role
    ];

    if ($validator->validate($data)) {
        // Проверка уникальности имени пользователя
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
        $stmt->execute([$username]);
        if ($stmt->fetchColumn() > 0) {
            $errors['username'] = "Имя пользователя уже занято.";
        }

        // Проверка пароля
        if (strlen($password) < 10 || 
            !preg_match('/[A-Z]/', $password) || 
            !preg_match('/[a-z]/', $password) || 
            !preg_match('/\d/', $password) || 
            !preg_match('/[\W_]/', $password)) {
            $errors['password'] = "Пароль должен содержать более 10 символов, включать буквы верхнего и нижнего регистра, цифры и спецсимволы.";
        }

        // Если ошибок нет, сохраняем пользователя в БД
        if (empty($errors)) {
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $passwordHash, $role]);

            if ($stmt->rowCount() > 0) {
                // Получение ID нового пользователя
                $userId = $pdo->lastInsertId();

                // Данные сессии
                $_SESSION['user_id'] = $userId;
                $_SESSION['username'] = $username;
                $_SESSION['role'] = $role;

                // Перенаправление на профиль
                $profilePage = $role === 'organizer' ? '/profile/organizer.php' : '/profile/volunteer.php';
                header("Location: $profilePage");
                exit;
            }
        }
    } else {
        $errors = array_merge($errors, $validator->getErrors());
    }
}

include_once '../includes/navbar.php';
?>

<div class="container mt-5">
    <h2>Регистрация</h2>

    <?php if (!empty($errors)): ?>
        <div class="alert alert-danger">
            <?php foreach ($errors as $error): ?>
                <p><?= htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

    <form action="register.php" method="POST">
        <div class="mb-3">
            <label for="username" class="form-label">Имя пользователя</label>
            <input type="text" class="form-control <?= isset($errors['username']) ? 'is-invalid' : ''; ?>" id="username" name="username" value="<?= htmlspecialchars($username ?? ''); ?>" required>
            <?php if (isset($errors['username'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['username']); ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Электронная почта</label>
            <input type="email" class="form-control <?= isset($errors['email']) ? 'is-invalid' : ''; ?>" id="email" name="email" value="<?= htmlspecialchars($email ?? ''); ?>" required>
            <?php if (isset($errors['email'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['email']); ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control <?= isset($errors['password']) ? 'is-invalid' : ''; ?>" id="password" name="password" required>
            <?php if (isset($errors['password'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['password']); ?></div>
            <?php endif; ?>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Роль</label>
            <select class="form-control <?= isset($errors['role']) ? 'is-invalid' : ''; ?>" id="role" name="role" required>
                <option value="">Выберите роль</option>
                <option value="volunteer" <?= (isset($role) && $role === 'volunteer') ? 'selected' : ''; ?>>Волонтер</option>
                <option value="organizer" <?= (isset($role) && $role === 'organizer') ? 'selected' : ''; ?>>Организатор</option>
            </select>
            <?php if (isset($errors['role'])): ?>
                <div class="invalid-feedback"><?= htmlspecialchars($errors['role']); ?></div>
            <?php endif; ?>
        </div>
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>
    <p class="mt-3">Уже есть аккаунт? <a href="login.php">Войдите</a></p>
</div>
<?php include_once '../includes/footer.php'; ?>
