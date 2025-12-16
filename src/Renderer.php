<?php
namespace AlanForms;
class Renderer
{
    public static function render(bool $includeScriptTag, $name = ""): string
    {
        $string = "<div>";
        //create a div container and asign properties to it, then print it
        $string .= self::constructWidget($name);

        //load Alan Captcha from cdn
        if ($includeScriptTag)
            wp_enqueue_script(
                "alan_widget_script",
                "https://api.alancaptcha.com/widget/1.0.0/widget.bundle.js?ver=1.0.0&r=" . uniqid() . "\"",
                [],
                "1.0.0",
                ["in_footer" => true]
            );

        return $string . "</div>";
    }
    private static function constructWidget($fieldName)
    {
        $clientIdentifier = "";

        $siteKey = get_option("alanforms_site_key_field");

        //###############---Language-Settings---###############
        $lang = get_option("alanforms_language");
        if ($lang == "--") {
            $pageLang = substr(get_locale(), 0, 2);
            $languageArray = ['en', 'de', 'es', 'fr', 'it'];

            if (in_array($pageLang, $languageArray)) {
                $dataLang = "data-lang='".esc_attr($pageLang)."'";
            } else {
                $dataLang = "data-lang='en'";
            }

        } else if ($lang == "custom") {
            $dataLang = sprintf(
                "data-unverifiedtext='%s' data-verifiedtext='%s' data-retrytext='%s' data-workingtext='%s' data-starttext='%s'",
                esc_attr(get_option("alanforms_language_attribute_unverified")),
                esc_attr(get_option("alanforms_language_attribute_verified")),
                esc_attr(get_option("alanforms_language_attribute_retry")),
                esc_attr(get_option("alanforms_language_attribute_working")),
                esc_attr(get_option("alanforms_language_attribute_start"))
            );
        } else {
            $dataLang = "data-lang='".esc_attr($lang)."'";
        }

        $dataEndpoint = "";

        $dataClientIdentifier = "data-clientidentifier='" . esc_attr($clientIdentifier) . "_Captcha_Plugin_" . "1.0.0" . "'";

        $dataName = ($fieldName == "") ? "" : "data-name='" . esc_attr($fieldName) . "'";

        $nonce = wp_create_nonce("alanforms_field_verification");

        return "<div class='alan-captcha' " . $dataClientIdentifier ." ".$dataLang ." ". $dataName ." ". $dataEndpoint ." data-autotrigger='true' data-autorun='true' data-sitekey='".esc_attr($siteKey)."'></div> " . 
        wp_nonce_field('alanforms_field_verification', 'alanforms_solution_nonce');
    }
}