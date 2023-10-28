<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-24
 * Time: 07:31
 */

use App\Core\Api\HttpResponse;

/**
 * Create a WP_Rest_Response for frontend development
 *
 * @param bool $success
 * @param int $status
 * @param int $httpResponseCode
 * @param string $message
 * @param array $data
 *
 * @return WP_REST_Response
 */
function response(bool $success, int $status, int $httpResponseCode, string $message, array $data): WP_REST_Response {
	return new WP_REST_Response([
		'success' => $success,
		'status' => $status,
		'message' => $message,
		'data' => $data
	], $httpResponseCode);
}

/**
 * Create an error response for an API request
 *
 * @param int $httpResponseCode
 * @param string $message
 * @param array $data
 * @param int $status
 *
 * @return WP_REST_Response
 */
function error(int $httpResponseCode, string $message, array $data = [], int $status = 1): WP_REST_Response {
	return response(false, $status, $httpResponseCode, $message, $data);
}

/**
 * Create a success response for an API request
 *
 * @param string $message
 * @param array $data
 * @param int $httpResponseCode
 * @param int $status
 *
 * @return WP_REST_Response
 */
function success(string $message, array $data = [], int $httpResponseCode = HttpResponse::HTTP_OK, int $status = 0): WP_REST_Response {
	return response(true, $status, $httpResponseCode, $message, $data);
}

/**
 * Check whether the current request is made by API or not
 * @return bool
 */
function isApiRequest(): bool {
	$requestUri = $_SERVER['REQUEST_URI'];

	// Check for the presence of the specific WordPress API URL
	if (str_contains($requestUri, '/wp-json/')) {
		return true;
	}

	// Check for custom headers
	if (isset($_SERVER['HTTP_X_API_KEY'])) {
		return true;
	}

	return false;
}