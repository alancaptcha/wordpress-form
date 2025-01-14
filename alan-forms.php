<?php
/*
Plugin Name:  Alan Captcha Forms
Plugin URI:   https://alancaptcha.com/
Description:  The captcha plugin to further secure Kontaktfrom 7, Elementor Metforms and Elementor Proforms
Version:      1.0
Author:       web&co
*/


if (!defined("ABSPATH")) {
    die;
}


defined("ABSPATH") or die("What are you trying to do?");

if (!function_exists("add_action")) {
    echo "What are you trying to do?";
    exit;
}


class AlanCaptchaWPForms
{
    //the name used by wordpress to identify the admin settings page
    private $settings_page_name = "alan_captcha_forms_plugin";

    private $plugin_name;

    function __construct()
    {
        $this->plugin_name = plugin_basename(__FILE__);

        add_filter(
            "plugin_action_links_" . $this->plugin_name,
            array($this, 'settings_link')
        );

        require_once __DIR__ . "/integrations/Integration.php";
        require_once __DIR__ . "/src/Settings.php";
    }

    function settings_link($links)
    {
        $settings_link = "<a href=\"admin.php?page=alan_captcha_forms_plugin\">Settings</a>";
        array_unshift($links, $settings_link);
        return $links;
    }
}
$alanCaptchaWPLogin = new AlanCaptchaWPForms();
