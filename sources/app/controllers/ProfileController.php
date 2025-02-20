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

	public function updateProfileImage(): void
	{
		AuthMiddleware::requireLogin();
		$user = AuthMiddleware::getSessionUser();
		$authModel = $this->loadModel('AuthModel');
		var_dump($_FILES);

		$formAction = "/profile";
		$photoForm = new Form($formAction, 'POST', 'multipart/form-data');
		$photoForm->addFileField(
			'photo',
			'',
			[
				'required' => true,
				'class' => 'button button-cta'
			]
		)->addHiddenField(
				'csrf_token',
				AuthMiddleware::generateCsrfToken()
			)
			->addSubmitButton('Upload Photo', ['class' => 'button']);

		if (isset($_FILES['photo']) && !empty($_FILES['photo']['name'])) {
			$image = $_FILES['photo'];
			//$imagePath = // FileManager::uploadProfileImage($photo, $user['id']);

			// Ajout de la photo dans la base de données
			//$authModel->updateProfileImage($user['id'], $imagePath);
		}
	}

	public function uploadPhotoForm(int $id): void
	{
		AuthMiddleware::requireLogin();
		$formAction = "/profile";
		$photoForm = new Form($formAction, 'POST', 'multipart/form-data');
		$photoForm->addFileField(
			'photo',
			'',
			[
				'required' => true,
				'class' => 'button button-cta'
			]
		)->addHiddenField(
				'csrf_token',
				AuthMiddleware::generateCsrfToken()
			)
			->addSubmitButton('Upload Photo', ['class' => 'button']);
	}
}
