<header>
    <nav>
        <ul>
            <li><a href="/">Home</a></li>
            <li><a href="/news">News</a></li>
            <?php if (isset($_SESSION['user']['id'])): ?>
            <li><a href="/logout">Logout</a></li>
            <?php else: ?>
            <li><a href="/register">Sign up</a></li>
            <li><a href="/login">Login</a></li>
            <?php endif; ?>
        </ul>
    </nav>
</header>
