<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-17
 * Time: 22:00
 */

/**
 * Create an object from the given class
 * @param string $class
 *
 * @return mixed the instance
 */
function instantiate( string $class ): mixed {
	return new $class();
}

/**
 * Create an object from the given class
 * If register methods exists inside class then calls it
 *
 * @param mixed $class
 *
 * @return void
 */
function callRegisterMethodIfExist( mixed $class ): void {
	$object = instantiate($class);

	if (method_exists($object, 'register')) {
		$object->register();
	}
}