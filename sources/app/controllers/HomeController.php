<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Form;
class HomeController extends Controller
{
	public function index(): void
	{
		$data = [
			'title' => 'Home'
		];
		$this->loadView('home/index', $data);
	}
}
