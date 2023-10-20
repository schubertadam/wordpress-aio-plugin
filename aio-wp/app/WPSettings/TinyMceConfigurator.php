<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-20
 * Time: 11:31
 */

namespace App\WPSettings;

use App\Interfaces\RegisterInterface;
use DirectoryIterator;

class TinyMceConfigurator implements RegisterInterface {
	private array $fileNames;

	public function __construct() {
		$this->fileNames = [];

		$files = new DirectoryIterator(AIO_ROOT . 'assets/js/tiny-mce');

		/** @var DirectoryIterator $file */
		foreach ( $files as $file ) {
			if (!$file->isDot()) {
				$this->fileNames[] = pathinfo($file->getFilename(), PATHINFO_FILENAME);
			}
		}
	}

	public function register(): void {
		// Attach buttons to tinyMCE editor
		add_filter('mce_buttons', function (array $buttons) {
			foreach ( $this->fileNames as $fileName ) {
				$buttons[] = $fileName;
			}

			return $buttons;
		});

		// Register the buttons functionalities
		add_filter('mce_external_plugins', function (array $functions) {
			foreach ( $this->fileNames as $fileName ) {
				$js = "mce_$fileName";
				$functions[$js] = AIO_URL . "assets/js/tiny-mce/$fileName.js";
			}

			return $functions;
		});
	}
}