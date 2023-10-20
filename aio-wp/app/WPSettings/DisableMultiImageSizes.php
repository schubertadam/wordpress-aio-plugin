<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-20
 * Time: 06:19
 */

namespace App\WPSettings;

use App\Interfaces\RegisterInterface;

class DisableMultiImageSizes implements RegisterInterface {

	public function register(): void {
		/** Use these settings for advance customization
		 *
		 * unset($sizes['thumbnail']);  // Remove thumbnail size
		 * unset($sizes['medium']);     // Remove medium size
		 * unset($sizes['medium_large']); // Remove medium_large size
		 * unset($sizes['large']);      // Remove large size
		 *
		 * return $sizes;
		 *
		 */

		add_filter('intermediate_image_sizes_advanced', function (array $sizes) {
			return ['full'];
		});

		add_filter('image_size_names_choose', function (array $sizes) {
			return ['full'];
		});
	}
}