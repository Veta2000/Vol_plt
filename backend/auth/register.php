<?php
// include_once '../includes/navbar.php';
require_once '../config.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role'];

     // уникальность имени пользователя
     $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
     $stmt->execute([$username]);
     if ($stmt->fetchColumn() > 0) {
         $errors[] = "Имя пользователя уже занято.";
     }
 
     // пароля
     if (strlen($password) < 10 || !preg_match('/[A-Z]/', $password) || !preg_match('/[a-z]/', $password) || !preg_match('/\d/', $password) || !preg_match('/[\W_]/', $password)) {
         $errors[] = "Пароль должен содержать более 10 символов, включать буквы верхнего и нижнего регистра, цифры и спецсимволы.";
     }
 
     // сохр бд
     if (empty($errors)) {
         $passwordHash = password_hash($password, PASSWORD_DEFAULT);
         $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
         $stmt->execute([$username, $email, $passwordHash, $role]);
         header("Location: login.php");
         exit;
     }
 }
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
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Электронная почта</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Пароль</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <div class="mb-3">
            <label for="role" class="form-label">Роль</label>
            <select class="form-control" id="role" name="role" required>
                <option value="volunteer">Волонтер</option>
                <option value="organizer">Организатор</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Зарегистрироваться</button>
    </form>
    <p class="mt-3">Уже есть аккаунт? <a href="login.php">Войдите</a></p>
</div>
<?php include_once '../includes/footer.php'; ?>
