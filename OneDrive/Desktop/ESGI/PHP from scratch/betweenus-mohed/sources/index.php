<?php
/**
 * Activer l'affichage des erreurs en DEV
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Démarrer la session si pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Autoloader minimal pour vos classes internes
spl_autoload_register(function ($class) {
    $prefix  = 'App\\';
    $baseDir = __DIR__ . '/app/';
    $len     = strlen($prefix);

    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relativeClass = substr($class, $len);
    $file          = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// Importer le routeur
use App\Core\Router;
$router = new Router();

// ---------------- ROUTES ----------------

// Page d'accueil d'exemple
$router->get('/', ['AuthController', 'index']);

// Inscription
$router->get('/register', ['AuthController', 'register']);
$router->post('/register', ['AuthController', 'store']);

// Vérification code
$router->get('/verify', ['AuthController', 'verifyForm']);
$router->post('/verify', ['AuthController', 'verifySubmit']);

// Connexion
$router->get('/login', ['AuthController', 'login']);
$router->post('/login', ['AuthController', 'attemptLogin']);

// Déconnexion
$router->get('/logout', ['AuthController', 'logout']);

// Page connectée
$router->get('/connected', ['AuthController', 'connected']);

// Dispatch
$router->dispatch();
