<?php


class ApiKeyChecker{
    public static function getCredentialValidity($override = false)
    {
        if ($override || wp_cache_get("alanCredentialValidity") === false) {
            wp_cache_set("alanCredentialValidity", ["validity" => self::checkCredentials()], "", 60);
        }
        $cacheGet = wp_cache_get("alanCredentialValidity");
        return $cacheGet["validity"];
    }

    private static function checkCredentials()
    {
        $requestBody = ["siteKey" => get_option("site_key")];

        $requestBody["key"] = get_option("api_key");

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