<?php
namespace App\Controllers;

use App\Core\Controller;

class HomeController extends Controller
{
	public function index(): void
	{
		$data = [
			'title' => 'Partagez facilement vos photos de voyage entre amis'
		];
		$this->loadView('home/index', $data);
	}
}
