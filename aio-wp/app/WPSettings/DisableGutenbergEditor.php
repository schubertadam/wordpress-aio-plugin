<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-19
 * Time: 14:08
 */

namespace App\WPSettings;

use App\Interfaces\RegisterInterface;

class DisableGutenbergEditor implements RegisterInterface {

	public function register(): void {
		add_filter('use_block_editor_for_post', '__return_false');
	}
}