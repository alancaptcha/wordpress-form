<?php
namespace AlanForms;
class PuzzleValidator
{
	public static function validate($inputValue): bool
	{
		$apiKey = sanitize_text_field(get_option("alanforms_api_key_field"));

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

	public static function nonceSuccessfull(): bool
	{
		$value = wp_verify_nonce(
					sanitize_text_field(wp_unslash($_POST['alanforms_solution_nonce'])), 
					'alanforms_field_verification'
		);
		if (
			isset($_POST['alanforms_solution_nonce']) &&
			in_array(
				$value,
				[1, 2],
				true
			)
		) {
			return true;
		}
		return false;

	}

}