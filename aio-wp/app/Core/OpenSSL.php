<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-11-03
 * Time: 01:14
 */

namespace App\Core;

class OpenSSL {
	private const DIGEST_ALGO = 'SHA256';
	private const CIPHER_ALGO = 'AES-128-CBC';
	private const DIGEST_ALGO_LENGTH = 32;

	public static function encrypt( string $text ): string {
		$passphrase = openssl_digest(self::getSalt(), self::DIGEST_ALGO, true);
		$iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length(self::CIPHER_ALGO));
		$encryptedData = openssl_encrypt($text, self::CIPHER_ALGO, $passphrase, OPENSSL_RAW_DATA, $iv);
		$hmac = hash_hmac(self::DIGEST_ALGO, $encryptedData, $passphrase, true);

		return base64_encode("{$iv}{$hmac}{$encryptedData}");
	}

	public static function decrypt( string $encryptedText ): ?string {
		$passphrase = openssl_digest(self::getSalt(), self::DIGEST_ALGO, true);
		$decodedData = base64_decode($encryptedText);

		// Use to remove unnecessary data from the encryption
		$ivLength = openssl_cipher_iv_length(self::CIPHER_ALGO);

		$iv = substr($decodedData, 0, $ivLength);
		$hmac = substr($decodedData, $ivLength, self::DIGEST_ALGO_LENGTH);
		$dataToDecrypt = substr($decodedData, $ivLength + self::DIGEST_ALGO_LENGTH);

		$decryptedData = openssl_decrypt($dataToDecrypt, self::CIPHER_ALGO, $passphrase, OPENSSL_RAW_DATA, $iv);

		if (hash_equals($hmac, hash_hmac(self::DIGEST_ALGO, $dataToDecrypt, $passphrase, true))) {
			return $decryptedData;
		}

		return null;
	}

	/**
	 * Check and retrieve ENCRYPT_SALT, if not exists throw an error
	 * @return string
	 */
	private static function getSalt(): string {
		if (!defined('ENCRYPT_SALT')) {
			// TODO: throw WP_Error
		}

		return ENCRYPT_SALT;
	}
}