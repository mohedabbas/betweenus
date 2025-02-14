<?php
use App\Core\Router;
session_start();
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


//Gallery routes
$router->get('/gallery', ['GalleryController', 'index']);
$router->get('/gallery/create', ['GalleryController', 'createGallery']);
$router->post('/gallery/create', ['GalleryController', 'storeGallery']);
$router->get('/gallery/{id}', ['GalleryController', 'showGallery']);
$router->get('/gallery/upload/{id}', ['GalleryController', 'uploadPhotoForm']);
$router->post('/gallery/upload/{id}', ['GalleryController', 'storePhoto']);

// Delete photo
$router->get('/gallery/delete/{id}', ['GalleryController', 'deletePhoto']);

// Empty gallery 
$router->get('/gallery/empty/{galleryId}', ['GalleryController','emptyGallery']);
$router->get('/gallery/deletegallery/{galleryId}', ['GalleryController','deleteGallery']);



// Gallery user routes
$router->get('/gallery/addusers/{id}', ['GalleryUserController', 'addUsersInGallery']);
$router->post('/gallery/addusers/{id}', ['GalleryUserController', 'addUsersInGallery']);
$router->get('/gallery/send_invite/{userid}', ['GalleryUserController', 'addUserAndSendMail']);

// Remove user from gallery
$router->get('/gallery/removeuser/{userid}', ['GalleryUserController', 'removeUserFromGallery']);

// Temporary code for testing
$router->get('/profile', ['ProfileController', 'index']);

// Dispatch
$router->get('/designguide', ['DesignGuideController', 'index']);


$router->dispatch();
