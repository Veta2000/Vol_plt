+<?php
include_once '../includes/header.php';
include_once '../includes/navbar.php';
?>

<div class="container mt-5">
    <h2>Вход</h2>
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
