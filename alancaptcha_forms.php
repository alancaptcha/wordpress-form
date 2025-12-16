<?php
/*
Plugin Name:  ALAN Captcha Forms
Plugin URI:   https://alancaptcha.com/en
Description:  The captcha plugin to further secure Kontaktfrom 7, Elementor Metforms and Elementor Proforms
Version:      1.0
Author:       web&co
Author URI:  https://webundco.com/
License:    GPLv2 or later
*/


if (!defined("ABSPATH")) {
    die;
}


defined("ABSPATH") or die("What are you trying to do?");

if (!function_exists("add_action")) {
    echo "What are you trying to do?";
    exit;
}

define('ALAN_FORMS_PATH', plugin_dir_path(__FILE__));


require_once plugin_dir_path(__FILE__)."src/Settings.php";
require_once plugin_dir_path(__FILE__)."integrations/Integrator.php";


class AlanCaptchaWPForms
{
    //the name used by wordpress to identify the admin settings page
    

    private $plugin_name;

    function __construct()
    {
        $this->plugin_name = plugin_basename(__FILE__);

        add_filter(
            "plugin_action_links_" . $this->plugin_name,
            array($this, 'settings_link')
        );

        AlanForms\Integration\Integrator::integrateCaptcha();
        AlanForms\Settings::init();
    }

    function settings_link($links)
    {
        $settings_link = "<a href=\"admin.php?page=alanforms_settings_page\">Settings</a>";
        array_unshift($links, $settings_link);
        return $links;
    }
}
new AlanCaptchaWPForms();
