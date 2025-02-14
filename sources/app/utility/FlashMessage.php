<?php
namespace App\Utility;

class FlashMessage
{
    // Add a flash message
    public static function add($message, $type = 'info')
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION['flash_messages'][] = [
            'message' => $message,
            'type' => $type
        ];
    }

    // Display all flash messages
    public static function display()
    {
        if (session_status() === PHP_SESSION_NONE)
            session_start();

        if (!empty($_SESSION['flash_messages'])) {
            foreach ($_SESSION['flash_messages'] as $message) {
                echo '<div class="alert alert--' . $message['type'] . '">'
                    . htmlspecialchars($message['message'])
                    . '</div>';
            }
            unset($_SESSION['flash_messages']);
        }
    }
}

// Basic error handler
set_exception_handler(function ($exception) {
    error_log($exception->getMessage());
    FlashMessage::add('Something went wrong. Please try again later.', 'error');
    header('Location: /error');
    exit();
});
