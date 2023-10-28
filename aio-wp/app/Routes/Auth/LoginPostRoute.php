<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-24
 * Time: 07:28
 */

namespace App\Routes\Auth;

use App\Core\Api\HttpResponse;
use App\Core\Api\JWT\JWTAuth;
use App\Core\Api\Route;
use App\Interfaces\RegisterInterface;
use App\Requests\Auth\LoginRequest;
use WP_Error;
use WP_REST_Request;
use WP_User;

class LoginPostRoute extends Route implements RegisterInterface {

	public function register(): void {
		$this->api->post('login', function ( WP_REST_Request $request) {
			$jwt = JWTAuth::getInstance();
			$form = new LoginRequest($request);

			if (!$form->validate()) {
				return error(HttpResponse::HTTP_EXPECTATION_FAILED, 'Error', $form->errors);
			}

			/** @var WP_User $user */
			$user = wp_authenticate($request->get_param('username'), $request->get_param('password'));

			if ($user instanceof WP_Error) {
				$form->addErrorMessage('username', 'The required user does not exists!');
				return error(HttpResponse::HTTP_EXPECTATION_FAILED, 'Error', $form->errors);
			}

			$token = $jwt->generateToken($user);

			return success("Login successful", [
				'token' => $token
			]);
		});
	}
}