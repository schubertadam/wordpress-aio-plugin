<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-18
 * Time: 07:14
 */

namespace App\Core;

use App\Interfaces\RegisterInterface;

class Loader implements RegisterInterface {

	public function register(): void {
		// Register API routes
		iterateTroughFilesAndCallRegisterMethod(AIO_ROOT . "/app/Routes/", "App\\Routes\\");

		// Register WP specific settings
		iterateTroughFilesAndCallRegisterMethod(AIO_ROOT . "/app/WPSettings/", "App\\WPSettings\\");
	}
}