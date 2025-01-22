<?php

namespace App\Core;

use Exception;


class Controller
{
	/**
	 * This method is used to load the model
	 * @param string $model model Name.
	 * @return mixed
	 * @throws Exception
	 */
	public function loadModel(string $model): mixed
	{
		$modelPath = __DIR__ . "/../models/$model.php";
		if (file_exists($modelPath)) {
			require_once $modelPath;
			$modelClass = "App\\Models\\$model";
			return new $modelClass();
		} else {
			throw new Exception("Model file not found: $model");
		}
	}

	/**
	 * This method is used to load the view
	 * @param string $view
	 * @param array $data
	 */
	public function loadView(string $view, array $data = []): void
	{
		extract($data);
		require_once __DIR__ . "/../views/$view.php";
	}

}
