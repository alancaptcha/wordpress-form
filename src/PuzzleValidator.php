<?php

class Validator
{
	public static function validate($inputValue): bool
	{
		$apiKey = sanitize_text_field(get_option("forms_api_key_field"));

		if (empty($inputValue)) {
			return false;
		}

		$payload = json_decode(stripslashes($inputValue), true);
		if (!isset($payload["jwt"], $payload["solutions"])) {
			return false;
		}

		$response = wp_remote_post('https://api.alancaptcha.com/challenge/validate', [
			"headers" => ["Content-Type" => "application/json"],
			"body" => json_encode([
				"key" => $apiKey,
				"puzzleSolutions" => $payload["solutions"],
				"jwt" => $payload["jwt"]
			])
		]);

		$responseBody = wp_remote_retrieve_body($response);
		$apiBody = json_decode($responseBody, true);
		if (isset($apiBody["success"]) && $apiBody["success"]) {
			return true;
		}
		return false;
	}

}