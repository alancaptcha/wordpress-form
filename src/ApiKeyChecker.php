<?php
namespace AlanForms;

class ApiKeyChecker{
    public static function getCredentialValidity($override = false)
    {
        if ($override || wp_cache_get("alanFormsCredentialValidity") === false) {
            wp_cache_set("alanFormsCredentialValidity", ["validity" => self::checkCredentials()], "", 60);
        }
        $cacheGet = wp_cache_get("alanFormsCredentialValidity");
        return $cacheGet["validity"];
    }

    private static function checkCredentials()
    {
        $requestBody = ["siteKey" => get_option("alanforms_site_key_field")];

        $requestBody["key"] = get_option("alanforms_api_key_field");

        $response = wp_remote_post("https://api.alancaptcha.com/credentials/validate", [
            "headers" => ["Content-Type" => "application/json"],
            "body" => json_encode($requestBody)
        ]);

        $responseBody = wp_remote_retrieve_body($response);
        $apiBody = json_decode($responseBody, true);


        if (isset($apiBody["success"]) && $apiBody["success"]) {
            return true;
        }
        return false;
    }
}