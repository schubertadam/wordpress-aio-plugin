<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-19
 * Time: 14:12
 */

namespace App\WPSettings;

use App\Interfaces\RegisterInterface;

class OpenLinksOnNewTab implements RegisterInterface {

	public function register(): void {
		add_filter('the_content', function ($content) {
			// Define the regex to match <a> tags
			$pattern = '/<a(.*?)href=["\'](https?:\/\/.*?)["\'](.*?)>/i';

			// Replace the matched <a> tags with the same content plus target="_blank"
			$replacement = '<a$1href="$2"$3 target="_blank">';

			// Apply the replacement to the post content
			return preg_replace($pattern, $replacement, $content);
		});
	}
}