<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <?php if (isset($_SESSION['user'])): ?>
                <li><a href="/connected">Espace Connecté</a></li>
                <li><a href="/logout">Logout</a></li>
            <?php else: ?>
                <li><a href="/login">Login</a></li>
                <li><a href="/register">Register</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
