<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-22
 * Time: 08:40
 */

namespace App\Core\Api;

use wpdb;

class Route {
	protected wpdb $db;
	protected RestClient $api;

	public function __construct() {
		global $wpdb;

		$this->db = $wpdb;
		$this->api = RestClient::getInstance();
	}
}