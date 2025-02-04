<?php
namespace App\Controllers;

use App\Core\Controller;
class DesignGuideController extends Controller
{
    public function index(): void
    {
        $data = [
            'title' => 'Design Guide'
        ];
        $this->loadView('designGuide/index', $data);
    }
}
