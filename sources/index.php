<?php
use App\Core\Router;

//$databaseName = $_ENV["DATABASE_NAME"];
//$databaseUser = $_ENV["DATABASE_USER"];
//$databasePassword = $_ENV["DATABASE_PASSWORD"];
//
//$database = new PDO("mysql:host=mariadb;dbname=$databaseName", $databaseUser, $databasePassword);
//
//$query = $database->query("SELECT 1 + 1");
//
//$result = $query->fetch();
//
//var_dump($result);

// Register the autoloader
spl_autoload_register(function ($class) {
	// Namespace prefix to match
	$prefix = 'App\\';
	// Base directory where that namespace lives
	$baseDir = __DIR__ . '/app/';

	// Does the class use the "App\" prefix?
	$len = strlen($prefix);
	if (strncmp($prefix, $class, $len) !== 0) {
		// If not, move to the next registered autoloader
		return;
	}
	// Strip the "App\" prefix off the class
	$relativeClass = substr($class, $len);

	// Replace namespace separators with directory separators
	// Then append ".php"
	$file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

	// If the file exists, require it
	if (file_exists($file)) {
		require $file;
	}
});

// All the routes that we want to add to handle the requests
$router = new Router();
$router->get('/', ['HomeController', 'index']);
$router->post('/', ['HomeController', 'index']);

// Page d'accueil d'exemple
//$router->get('/', ['AuthController', 'index']);

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

$router->get('/forgot-password',  ['AuthController', 'forgotPasswordForm']);
$router->post('/forgot-password', ['AuthController', 'forgotPasswordSubmit']);
$router->get('/reset-password',   ['AuthController', 'resetPasswordForm']);
$router->post('/reset-password',  ['AuthController', 'resetPasswordSubmit']);


// Dispatch
$router->get('/designguide', ['DesignGuideController', 'index']);
$router->dispatch();
