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
		])->addTextField('email', 'Email', '', [
			'required' => 'required',
			'placeholder' => 'Enter your email',
			'class' => 'form-control border border-dark mt-2'
		])->addPasswordField('password', 'Password', [
			'required' => 'required',
			'placeholder' => 'Enter your password',
			'class' => 'form-control border border-dark mt-2'
		])->addSubmitButton('Register', [
			'class' => 'btn btn-dark mt-2',
			'name' => 'submit'
		]);

		$data = [
			'title' => 'Register User',
			'form' => $form
		];
		$this->loadView('auth/register', $data);
	}

	/**
	 * @throws Exception
	 */
	public function  store(): void
	{
		$userModel = $this->loadModel('AuthModel');
		$isSubmitted = isset($_POST['submit']) || Form::isSubmitted();
		if ($isSubmitted) {
			$data = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
			unset($data['submit']);
			$userModel->createUser($data);
		}
	}

}
