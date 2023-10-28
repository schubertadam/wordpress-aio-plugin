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
		// TODO: add option to enable or disable API
		$apiEnabled = true;

		if ($apiEnabled) {
			if (get_option('permalink_structure') !== '/%postname%/') {
				showErrorMessage("Set permalink to the following: /%postname%/");
			} else {
				iterateTroughFilesAndCallRegisterMethod(AIO_ROOT . "/app/Routes/", "App\\Routes\\");
			}
		}

		// Register WP specific settings
		iterateTroughFilesAndCallRegisterMethod(AIO_ROOT . "/app/WPSettings/", "App\\WPSettings\\");
	}
}