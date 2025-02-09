<?php

namespace App\Controllers;

use App\Core\Controller;
use Exception;

class ProfileController extends Controller
{
	/**
	 * @throws Exception
	 */
	public function index(): void
	{
		$loadModel = $this->loadModel('AuthModel');

		$user = $loadModel->findUserByUsernameOrEmail('mohed332@betweenus.com');
		$data = [
			'title' => 'Profile Page',
			'user' => $user
		];
		$this->loadView('profile/index', $data);
	}
}
