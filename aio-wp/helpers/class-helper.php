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

/**
 * Iterate through $root directory and call the register method
 * If the given element is a directory, then iterate through it as well
 * @param string $root
 * @param string $namespace
 *
 * @return void
 */
function iterateTroughFilesAndCallRegisterMethod( string $root, string $namespace ): void {
	$items = new DirectoryIterator($root);

	/** @var DirectoryIterator $item */
	foreach ( $items as $item ) {
		if (!$item->isDot()) {
			if ($item->isFile() && $item->getExtension() === 'php') {
				$fileName = pathinfo($item->getFilename(), PATHINFO_FILENAME);
				callRegisterMethodIfExist("{$namespace}{$fileName}");
			}

			// In case of directory we will scan it as well
			if ($item->isDir()) {
				$subdirectory = "{$root}/{$item->getFilename()}";
				$subNamespace = "{$namespace}{$item->getFilename()}\\";
				iterateTroughFilesAndCallRegisterMethod($subdirectory, $subNamespace);
			}
		}
	}
}