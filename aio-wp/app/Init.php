<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-17
 * Time: 21:55
 */

namespace App;

class Init {
	/**
	 * Loop through the classes and initialize them
	 * If register methods exists inside class then calls it
	 * @return void
	 */
	public function registerServices(): void {
		foreach ( self::getServices() as $service ) {
			$service = $this->instantiate($service);

			if (method_exists($service, 'register')) {
				$service->register();
			}
		}
	}

	/**
	 * Create an object from the given class
	 * @param string $class
	 *
	 * @return mixed the instance
	 */
	private function instantiate( string $class ): mixed {
		return new $class();
	}

	/**
	 * Here you can store all the classes you want to register on load
	 * @return array
	 */
	private function getServices(): array {
		return [];
	}
}