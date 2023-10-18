<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-17
 * Time: 17:00
 *
 * Plugin Name: AIO WP Extension
 * Description: A plugin that helps developers to extend the WordPress functionality and use JS frontend
 * Author: Adam Schubert
 * Author URI: https://github.com/schubertadam
 * Version: 1.0
 * Text Domain: aio-wp
 */

use App\Init;

define('AIO_ROOT', plugin_dir_path(__FILE__));
define('AIO_URL', plugin_dir_url(__FILE__));

if (file_exists(AIO_ROOT . 'vendor/autoload.php')) {
	require_once AIO_ROOT . "vendor/autoload.php";

	if (class_exists(Init::class)) {
		(new Init())->registerServices();
	} else {
		add_action('admin_notices', function () {
			echo '<div class="notice notice-error"><p>Init class does not exist!</p></div>';
		});
	}
} else {
	add_action('admin_notices', function () {
		echo '<div class="notice notice-error"><p>AIO WP Extension: please run the composer</p></div>';
	});
}

register_activation_hook(__FILE__, function () {
	flush_rewrite_rules();
});

register_deactivation_hook(__FILE__, function () {
	flush_rewrite_rules();
});