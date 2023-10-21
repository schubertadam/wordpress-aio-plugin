<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-21
 * Time: 18:54
 */

namespace App\Core\Api;

use Closure;
use WP_REST_Server;

class RestClient {
	private static ?RestClient $instance = null;
	private string $namespace = 'api';

	public static function getInstance(): RestClient {
		return self::$instance ?? new RestClient();
	}

	/**
	 * Register a GET api route for the application
	 * @param string $routeName
	 * @param Closure $callback
	 *
	 * @return void
	 */
	public function get( string $routeName, Closure $callback ): void {
		$this->registerRoute(WP_REST_Server::READABLE, $routeName, $callback);
	}

	/**
	 * Register a POST api route for the application
	 * @param string $routeName
	 * @param Closure $callback
	 *
	 * @return void
	 */
	public function post( string $routeName, Closure $callback ): void {
		$this->registerRoute(WP_REST_Server::CREATABLE, $routeName, $callback);
	}

	/**
	 * Register a PUT api route for the application
	 * @param string $routeName
	 * @param Closure $callback
	 *
	 * @return void
	 */
	public function put( string $routeName, Closure $callback ): void {
		$this->registerRoute(WP_REST_Server::EDITABLE, $routeName, $callback);
	}

	/**
	 * Register a DELETE api route for the application
	 * @param string $routeName
	 * @param Closure $callback
	 *
	 * @return void
	 */
	public function delete( string $routeName, Closure $callback ): void {
		$this->registerRoute(WP_REST_Server::DELETABLE, $routeName, $callback);
	}

	private function registerRoute( string $method, string $routeName, Closure $callback ): void {
		add_action('rest_api_init', function () use ($method, $routeName, $callback) {
			register_rest_route($this->namespace, "/$routeName", [
				'methods' => $method,
				'callback' => $callback
			]);
		});
	}
}