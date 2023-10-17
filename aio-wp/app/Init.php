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
			callRegisterMethodIfExist($service);
		}
	}

	/**
	 * Here you can store all the classes you want to register on load
	 * @return array
	 */
	private function getServices(): array {
		return [];
	}
}