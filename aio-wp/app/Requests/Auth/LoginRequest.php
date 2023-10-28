<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-28
 * Time: 12:17
 */

namespace App\Requests\Auth;

use App\Core\Api\Request;

class LoginRequest extends Request {
	public string $username =  '';
	public string $password = '';

	/**
	 * @inheritDoc
	 */
	public function rules(): array {
		return [
			'username' => [self::RULE_REQUIRED, [self::RULE_EXISTS, 'table' => 'wp_users', 'column' => 'user_login']],
			'password' => [self::RULE_REQUIRED]
		];
	}
}