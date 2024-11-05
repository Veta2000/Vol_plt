<?php
include_once '../includes/header.php';
include_once '../includes/navbar.php';
?>

<div class="container mt-5">
    <h2>Регистрация</h2>
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
