<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-19
 * Time: 14:22
 */

namespace App\WPSettings;

use App\Interfaces\RegisterInterface;

class ConvertImagesToWebp implements RegisterInterface {
	private array $supportedFileTypes = [
		'jpg',
		'jpeg',
		'png'
	];
	// TODO: get these settings from DB
	private int $maxWidth = 2560; // Maximum width for conversion
	private int $maxHeight = 2560; // Maximum height for conversion
	private int $quality = 100;

	public function register(): void {
		add_filter('wp_handle_upload', function (array $file) {
			// Check if we can use the required function before convert
			if (function_exists('imagewebp')) {
				$filePath = $file['file'];
				$extension = strtolower(pathinfo($filePath)['extension']);

				if (!in_array($extension, $this->supportedFileTypes)) {
					return ['error' => "File format is not supported. Supported types: " . implode(', ', $this->supportedFileTypes)];
				}

				// Original image dimensions
				list($width, $height) = getimagesize($filePath);

				// Check whether the image exceeds the size limit or not
				if ($width > $this->maxWidth || $height > $this->maxHeight) {
					return ['error' => "Maximum file size: {$this->maxWidth}x{$this->maxHeight}, your file: {$width}x{$height}"];
				}

				$image = imagecreatefromstring(file_get_contents($filePath));
				// Replace the extension in the new file name
				$newFileName = str_replace($extension, 'webp', $filePath);

				if (!imagewebp($image, $newFileName, $this->quality)) {
					return ['error' => "Failed to convert the image to WebP format."];
				}

				// Clean up the GD image resources
				imagedestroy($image);

				// Delete the original image
				unlink($filePath);

				// Update the file array with the WebP file path and MIME type
				return [
					'file' => $newFileName,
					'type' => 'image/webp',
					'ext' => 'webp',
				];
			}

			// Return the original file if GD Library is not supported by the server
			return $file;
		});
	}
}