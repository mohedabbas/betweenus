<?php

namespace App\Controllers;
use App\Core\Controller;
use App\Core\Form;




class GalleryController extends Controller
{

	/**
	 * Display a listing of the gallery to show on the dashboard with all the galleries of which user is a part.
	 * @return void
	 */
	public function index(): void
	{
		// Temporary code
		$authModel = $this->loadModel('AuthModel');
		$user = $authModel->findUserByUsernameOrEmail('mohed332@betweenus.com');


		$galleryModel = $this->loadModel('GalleryModel');
		$galleries = $galleryModel->getUserGalleriesAndContent($user->id);

		$data = [
			'title' => 'My Galleries',
			'galleries' => $galleries
		];
		$this->loadView('gallery/index', $data);
	}


	/**
	 * Show the form for creating a new gallery.
	 * @return void
	 */

	public function createGallery(): void
	{

		$galleryForm = new Form('/gallery/create', 'POST');

		$galleryForm->addTextField(
			'name',
			'',
			$data['name'] ?? '',
			[
				'required' => true,
				'placeholder' => 'Gallery Name',
				'class' => 'form-control'
			]
		)->addSubmitButton('Create Gallery', ['class' => 'btn btn-primary']);


		$data = [
			'title' => 'Create Gallery',
			'form' => $galleryForm
		];

		$this->loadView('gallery/create', $data);
	}


	/**
	 * Store a newly created gallery in storage.
	 * @return void
	 */
	public function storeGallery(): void
	{
		$galleryModel = $this->loadModel('GalleryModel');

		if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
			// redirect to the create gallery page
			header('Location: /gallery/create');
		}

		// Sanitize the POST data
		$_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

		$authModel = $this->loadModel('AuthModel');
		$user = $authModel->findUserByUsernameOrEmail('mohed332@betweenus.com');

		try {
			$galleryModel->createGallery([
				'name' => $_POST['name'],
				'created_by' => $user->id
			]);
			// redirect to the gallery page
			header('Location: /gallery');
		} catch (\Exception $e) {
			// redirect to the create gallery page
			header('Location: /gallery/create');
		}

	}


}
