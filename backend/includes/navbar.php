<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="/index.php">Volunteer Platform</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <?php if (!isset($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/login.php">Вход</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/events/list.php">Мероприятия</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/profile/<?= $_SESSION['role'] ?>.php">Профиль</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/auth/logout.php">Выход</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
