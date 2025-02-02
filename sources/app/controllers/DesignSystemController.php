<?php
namespace App\Controllers;

use App\Core\Controller;
class DesignSystemController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => 'Design System'
        ];
        $this->loadView('designSystem/index', $data);
    }
}
