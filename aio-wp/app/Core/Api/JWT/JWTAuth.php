<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-23
 * Time: 08:22
 */

namespace App\Core\Api\JWT;

use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use WP_REST_Request;
use WP_User;

class JWTAuth {
	private static ?JWTAuth $instance = null;
	private array $supportedAlgorithms = [
		'HS256', 'RS256', 'ES256', 'PS256',
		'HS384', 'RS384', 'ES384', 'PS384',
		'HS512', 'RS512', 'ES512', 'PS512'
	];

	public static function getInstance(): JWTAuth {
		return self::$instance ?? new JWTAuth();
	}

	public function __construct() {
		if (!in_array(JWT_AUTH_ALGORITHM, $this->supportedAlgorithms)) {
			// TODO: throw error and handle it during requests
		}
	}

	public function generateToken( WP_User $user ): string {
		$payload = Token::getPayload(time(), get_bloginfo('url'), $user);

		return JWT::encode($payload, JWT_AUTH_SECRET_KEY, JWT_AUTH_ALGORITHM);
	}

	/**
	 * Decode the token and return the data from it
	 * @param WP_REST_Request $request
	 *
	 * @return Token
	 */
	public function getTokenData( WP_REST_Request $request ): Token {
		$header = sanitize_text_field($request->get_header('Authorization'));
		$token = sscanf($header, 'Bearer %s')[0];
		$key = new Key(JWT_AUTH_SECRET_KEY, JWT_AUTH_ALGORITHM);
		$payload = JWT::decode($token, $key);

		return Token::convertPayloadToToken($payload);
	}
}