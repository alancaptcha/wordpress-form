<?php

class Renderer
{
    public static function render(bool $includeScriptTag): string
    {
        $string = "<div>";
        //create a div container and asign properties to it, then print it
        $string .= self::constructWidget();

        //load Alan Captcha from cdn
        if ($includeScriptTag)
            $string .= "<script src=\"https://api.alancaptcha.com/widget/1.0.0/widget.bundle.js?ver=1.0.0&r=" . uniqid() . "\" crossorigin=\"anonymous\" ></script>";

        return $string . "</div>";
    }
    private static function constructWidget()
    {
        //TODO implement clientIdentifier
        $clientIdentifier = "";

        $siteKey = get_option("forms_site_key_field");

        $lang = explode("_", get_locale())[0];
        $dataLang = "data-lang='$lang'";

        $dataEndpoint = "";
        $apiHost = "api.alancaptcha.com";


        $dataClientIdentifier = "data-clientidentifier='" . $clientIdentifier . "_Captcha_Plugin_" . "1.0.0" . "'";

        return "<div class='alan-captcha' $dataClientIdentifier $dataLang $dataEndpoint data-autotrigger='true' data-autorun='true' data-sitekey='$siteKey'></div>";
    }

}