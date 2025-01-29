<?php
namespace App\Models;
use App\Core\Model;

/*
 * AuthModel class
 * This class is used to interact with the users table
 * The main functionality of this class is to manage all the user-related data and interactions with the database.
 * This class extends the Model class, which is a base class for all the models in the application.
 * The Model class provides basic database operations such as insert, update, delete, and select.
 */

class AuthModel extends Model
{
	public function __construct()
	{
		parent::__construct('users');
	}

	/**
	 * This method is used to create a new user.
	 * @param array $data
	 * @return void
	 */
	public function createUser(array $data): void
	{
		parent::insert($data);
	}
}
