<?php

namespace App\Controllers;

use App\Middlewares\AuthMiddleware;
use App\Core\Controller;
use Exception;
use App\Core\Form;

class ProfileController extends Controller
{
	/**
	 * @throws Exception
	 */
	public function index(): void
	{
		// Vérifier si l'utilisateur est connecté
		AuthMiddleware::requireLogin();

		// Récupérer les données de l'utilisateur
		$user = AuthMiddleware::getSessionUser();

		$data = [
			'title' => $user['first_name'] . ' ' . $user['last_name'],
			'user' => $user
		];

		$this->loadView('profile/index', $data);
	}
}
