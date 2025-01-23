<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Form;
use Exception;

class AuthController extends Controller
{
	public function register(): void
	{
		$form = new Form('/register');
		$form->addTextField('username', 'Username', '', [
			'required' => 'required',
			'placeholder' => 'Enter your username',
			'class' => 'form-control border border-dark mt-2'
		])->addRadioField(
			'Test',
			'Please select one option.',
			[
				'First Option' => '1',
				'Second Option' => 'Two',
				'Third Option' => 'Three'
			],
			[
				'required' => 'required',
				'class' => 'form-control border border-dark mt-2'
			])
			->addSelectField(
				'country',
				'Country',
				[
					'America' => 'USA',
					'Canada' => 'Canada',
					'UK' => 'UK',
					'Australia' => 'Australia'
				],
				[
					'required' => 'required',
					'class' => 'form-control border border-dark mt-2'
				]
			)->addSubmitButton(
				'Register',
				[
					'class' => 'btn btn-primary mt-2'
				]
			);

		$data = [
			'title' => 'Register User',
			'form' => $form
		];
		$this->loadView('auth/register', $data);
	}

	/**
	 * @throws Exception
	 */
	public function store(): void
	{
		$userModel = $this->loadModel('AuthModel');
		$isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
		if ($isSubmitted) {
			// Filter input data
			$data = filter_input_array(INPUT_POST, $_POST);
			unset($data['submit']);
//			$userModel->createUser($data);
		}
	}
}
