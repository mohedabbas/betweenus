<?php
namespace App\Middlewares;

use App\Utility\FlashMessage;

class AuthMiddleware
{

    /**
     * AuthMiddleware constructor.
     * Start the session if it is not already started
     */
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Check if the user is logged in
     * @return bool
     */
    public static function isLoggedIn(): bool
    {
        return isset($_SESSION['user']);
    }

    /**
     * Check if the user is an admin
     * @return bool
     */
    public static function isAdmin(): bool
    {
        return isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin';
    }


    /**
     * Get the user from the session
     * @return mixed
     */
    public static function getSessionUser()
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Require the user to be logged in
     */
    public static function requireLogin()
    {
        if (!self::isLoggedIn()) {

            FlashMessage::add('Vous devez être connecté pour accéder à cette page', 'error');
            header('Location: /login');
            exit;
        }
    }

    /**
     * Require the user to be an admin
     * Redirect the user to the home page if they are not an admin
     */
    public static function requireAdmin()
    {
        self::requireLogin();
        if (!self::isAdmin()) {
            FlashMessage::add('Vous devez être administrateur pour accéder à cette page', 'error');
            header('Location: /');
        }
    }


    /**
     * Generate a CSRF token and store it in the session
     * @return string
     */
    public static function generateCsrfToken(): string
    {
        $token = bin2hex(random_bytes(32));
        $_SESSION['csrf_token'] = $token;
        return $token;
    }

    /**
     * Verify the CSRF token
     * @param $token
     * @return bool
     */
    public static function verifyCsrfToken($token): bool
    {
        return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    }

}
