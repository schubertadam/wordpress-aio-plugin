<?php
/**
 * Created by Adam Schubert.
 * Date: 2023-10-28
 * Time: 09:38
 */

namespace App\Core\Api;

use WP_REST_Request;
use wpdb;

abstract class Request {
	public const RULE_REQUIRED = 'required';
	public const RULE_EMAIL = 'email';
	// usage: [RULE_MIN, 'min' => number]
	public const RULE_MIN = 'min';
	// usage: [RULE_MAX, 'max' => number]
	public const RULE_MAX = 'max';
	// usage: [RULE_MATCH, 'match' => 'theInputName']
	public const RULE_MATCH = 'match'; // for passwords, etc.
	// usage: [RULE_UNIQUE, 'table' => 'tableName']
	public const RULE_UNIQUE = 'unique';
	public const RULE_EXISTS = 'exists';
	private wpdb $db;

	// Contain errors by input name
	public array $errors = [];

	public function __construct( WP_REST_Request $request ) {
		global $wpdb;
		$this->loadData($request->get_body());

		$this->db = $wpdb;
	}

	/** Return field rules by array
	 *
	 * e.g.: ['input' => [RULE_MIN, 'min' => 5 ]]
	 * @return array
	 */
	abstract public function rules(): array;

	/** Create variables from $_POST and $_GET
	 * if that property exists in XYModel
	 * e.g.: $_POST['username'] --> $username
	 *
	 * @param string $requestData
	 */
	public function loadData(string $requestData): void {
		$data = json_decode($requestData);

		foreach($data as $key => $value) {
			if(property_exists($this, $key)) {
				$this->{$key} = $value; // from key $_POST['username'] --> $username
			}
		}
	}

	public function validate(): bool {
		// get rules from specific model
		$data = $this->rules();

		// 'username' => [RULE_REQUIRED, etc.]
		foreach($data as $attribute => $rules) {
			// $value = $attribute NOT WORKING
			$value = $this->{$attribute}; // $value = value of the input

			foreach($rules as $rule) {
				$ruleName = $rule;

				if(!is_string($ruleName)) {
					$ruleName = $rule[0]; // e.g.: [RULE_MATCH, 'match' => 'input'] <-- 0 is the rule
				}

				if($ruleName === self::RULE_REQUIRED && !$value) {
					$this->addErrorRule($attribute, self::RULE_REQUIRED);
				}

				if($ruleName === self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
					$this->addErrorRule($attribute, self::RULE_EMAIL);
				}

				if($ruleName === self::RULE_MIN && strlen($value) < $rule['min']) {
					$this->addErrorRule($attribute, self::RULE_MIN, $rule);
				}

				if($ruleName === self::RULE_MAX && strlen($value) > $rule['max']) {
					$this->addErrorRule($attribute, self::RULE_MAX, $rule);
				}

				if($ruleName === self::RULE_MATCH && $value !== $this->{$rule['match']}) {
					// [RULE_MATCH, 'match' => 'input_name']
					$this->addErrorRule($attribute, self::RULE_MATCH);
				}

				if($ruleName === self::RULE_UNIQUE || $ruleName === self::RULE_EXISTS) {
					$tableName = $rule['table'];
					$column = $rule['column'] ?? $attribute;

					$sql = $this->db->prepare("SELECT * FROM $tableName WHERE $column = %s;", $this->{$attribute});
					$this->db->query($sql);

					if ($ruleName === self::RULE_UNIQUE) {
						if($this->db->num_rows > 0) {
							$this->addErrorRule($attribute, self::RULE_UNIQUE, ['field' => $attribute]);
						}
					}

					if ($ruleName === self::RULE_EXISTS) {
						if($this->db->num_rows < 1) {
							$this->addErrorRule($attribute, self::RULE_EXISTS, ['field' => $attribute]);
						}
					}
				}
			}
		}

		return empty($this->errors);
	}

	/** Add default rule error message for errors array
	 * @param string $attribute
	 * @param string $rule
	 * @param array $params
	 */
	private function addErrorRule(string $attribute, string $rule, array $params = []): void {
		$message = $this->errorMessage()[$rule] ?? 'There is an unknown error!';

		// in case of match, min, max and unique
		if(!empty($params)) {
			foreach($params as $key => $value) {
				$message = str_replace(":$key", $value, $message);
			}
		}

		$this->errors[$attribute][] = $message; // this can hold multiple errors for one attribute
	}

	/** Create custom error message for input
	 * @param string $attribute
	 * @param string $message
	 */
	public function addErrorMessage(string $attribute, string $message): void {
		$this->errors[$attribute][] = $message;
	}

	public function hasError(string $attribute): bool {
		return isset($this->errors[$attribute]);
	}

	public function getFirstError(string $attribute): string {
		return $this->errors[$attribute][0] ?? '';
	}

	private function errorMessage(): array {
		return [
			self::RULE_REQUIRED => 'This field is required!',
			self::RULE_EMAIL => 'This field must contain a valid email address',
			self::RULE_MIN => 'This field must be at least :min characters',
			self::RULE_MAX => 'This field must be at most :max characters',
			self::RULE_MATCH => 'This field must be the same as: :match',
			self::RULE_UNIQUE => ':field already exists in the database!',
			self::RULE_EXISTS => ':field doesn\'t exists in the database!'
		];
	}
}