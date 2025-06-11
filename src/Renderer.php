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
        $clientIdentifier = "";

        $siteKey = get_option("forms_site_key_field");

        //###############---Language-Settings---###############
        $lang = get_option("alan_forms_language");
        if ($lang == "--") {
            $pageLang = substr(get_locale(),0,2);

            $languageArray = ['en', 'de','es','fr','it'];
            

            if (in_array($pageLang, $languageArray)) {
                $dataLang= "data-lang='$pageLang'";
            }else {
                $dataLang= "data-lang='en'";
            }

        } else if ($lang == "custom") {
            $dataLang = "data-unverifiedtext='" . esc_html(get_option("alan_forms_language_attribute_unverified")) .
                "' data-verifiedtext='" . esc_html(get_option("alan_forms_language_attribute_verified")) .
                "' data-retrytext='" . esc_html(get_option("alan_forms_language_attribute_retry")) .
                "' data-workingtext='" . esc_html(get_option("alan_forms_language_attribute_working")) .
                "' data-starttext='" . esc_html(get_option("alan_forms_language_attribute_start")) . "'";
        } else {
            $dataLang = "data-lang='$lang'";
        }


        $dataEndpoint = "";

        $dataClientIdentifier = "data-clientidentifier='" . $clientIdentifier . "_Captcha_Plugin_" . "1.0.0" . "'";

        return "<div class='alan-captcha' $dataClientIdentifier $dataLang $dataEndpoint data-autotrigger='true' data-autorun='true' data-sitekey='$siteKey'></div>";
    }
}