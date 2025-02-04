<?php
namespace App\Core;

use Exception;

class Controller
{
    public function loadModel(string $model)
    {
        $class = "App\\Models\\$model";
        if (!class_exists($class)) {
            throw new Exception("Le modèle $class n’existe pas.");
        }
        return new $class();
    }

    public function loadView(string $view, array $data = []): void
    {
        extract($data);
        require __DIR__ . "/../views/$view.php";
    }
}
