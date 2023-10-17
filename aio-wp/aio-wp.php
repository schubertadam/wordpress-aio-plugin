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

define('AIO_ROOT', plugin_dir_path(__FILE__));
define('AIO_URL', plugin_dir_url(__FILE__));

if (file_exists(AIO_ROOT . 'vendor/autoload.php')) {
	require_once AIO_ROOT . "vendor/autoload.php";
} else {
	add_action('admin_notices', function () {
		echo '<div class="notice notice-error"><p>AIO WP Extension: please run the composer</p></div>';
	});
}