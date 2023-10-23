<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-23
 * Time: 18:08
 */

namespace App\Core\Api\JWT;

use stdClass;
use WP_User;

class Token {
	private string $issuer;
	private int $issuedAt;
	private int $notBefore;
	private int $expiration;
	private array $data;

	public function __construct( stdClass $payload ) {
		$this->issuer = $payload->iss;
		$this->issuedAt = $payload->iat;
		$this->notBefore = $payload->nbf;
		$this->expiration = $payload->exp;
		$this->data = (array)$payload->data;
	}

	public static function getPayload( int $time, string $issuer, WP_User $user ): array {
		$iat = $time; // issued at
		$nbf = $iat; // not before
		$exp = $iat + JWT_AUTH_TOKEN_EXPIRATION; // expiration

		return [
			'iss' => $issuer, // https://mojoauth.com/glossary/jwt-issuer/
			'iat' => $iat,
			'nbf' => $nbf, // https://mojoauth.com/glossary/jwt-not-before/
			'exp' => $exp,
			'data' => [
				'userId' => $user->ID
			]
		];
	}

	/**
	 * Convert the payload data into readable Token class format.
	 * @param stdClass $payload
	 *
	 * @return Token
	 */
	public static function convertPayloadToToken( stdClass $payload ): Token {
		return new Token($payload);
	}

	public function getIssuer(): string {
		return $this->issuer;
	}

	public function getIssuedAt(): int {
		return $this->issuedAt;
	}

	public function getNotBefore(): int {
		return $this->notBefore;
	}

	public function getExpiration(): int {
		return $this->expiration;
	}

	public function getData(): array {
		return $this->data;
	}
}