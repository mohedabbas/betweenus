<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title ?? 'BetweenUs') ?></title>
</head>
<body>
<header>
    <nav>
        <a href="/">Accueil</a> |
        <a href="/register">Inscription</a> |
        <a href="/login">Connexion</a> |
        <a href="/logout">Déconnexion</a>
    </nav>
</header>

<main>
    <?= $content ?? '' ?>
</main>

<footer>
    <p>Mon footer</p>
</footer>
</body>
</html>
