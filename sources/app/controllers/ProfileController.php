<?php

namespace App\Controllers;

use App\Middlewares\AuthMiddleware;
use App\Core\Controller;
use Exception;
use App\Core\Form;
use App\Utility\FileManager;
use App\Utility\FlashMessage;

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

		$data = [
			'title' => $user['first_name'] . ' ' . $user['last_name'],
			'user' => $user,
			'form' => $photoForm
		];

		$this->loadView('profile/index', $data);
	}

	public function updateProfileImage(): void
	{
		AuthMiddleware::requireLogin();
		$user = AuthMiddleware::getSessionUser();
		$authModel = $this->loadModel('AuthModel');

		if (isset($_FILES['photo']) && !empty($_FILES['photo']['name'])) {
			// Récupération de la photo
			$image = $_FILES['photo'];

			// Ajout de la photo dans le dossier uploads/profiles/	
			$imagePath = FileManager::uploadProfileImage($image, $user['id']);

			// Ajout de la photo dans la base de données
			$authModel->updateProfileImage($user['id'], $imagePath);

			// Ajout d'un message de succès
			FlashMessage::add('Photo mise à jour avec succès', 'success');
		}

		$this->redirect('/profile');
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
