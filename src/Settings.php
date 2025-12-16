<?php

namespace AlanForms;

if (!defined('ABSPATH'))
    exit;

require_once ALAN_FORMS_PATH . "src/ApiKeyChecker.php";

use AlanForms\ApiKeyChecker;


class Settings
{

    private static $settings_page_name = "alanforms_settings_page";

    public static function init()
    {
        add_action('admin_menu', [__CLASS__, 'show_register_menu']);
        add_action('admin_init', [__CLASS__, 'create_fields']);
        add_action('admin_enqueue_scripts', [__CLASS__, 'admin_scripts']);
    }
    public static function show_register_menu()
    {
        add_menu_page(
            "Alan Captcha Forms",
            "Alan Captcha Forms",
            "manage_options",
            Settings::$settings_page_name,
            [__CLASS__, 'register_forms_setting_function']
        );
    }

    public static function create_fields()
    {
        add_settings_section(
            "alanforms_license_option",
            "License Options",
            [__CLASS__, 'alanforms_header_option_callback'],
            Settings::$settings_page_name
        );

        add_settings_section(
            "alanforms_implementation_option",
            "Implementation Options",
            [__CLASS__, 'alanforms_header_option_callback'],
            Settings::$settings_page_name
        );

        add_settings_section(
            "alanforms_widget_option",
            "Widget Options",
            [__CLASS__, 'alanforms_header_option_callback'],
            Settings::$settings_page_name
        );

        register_setting(
            'alanforms_settings',
            "alanforms_site_key_field",
            ["sanitize_callback" => "sanitize_text_field"]
        );
        add_settings_field(
            "alanforms_site_key_field",
            "Site-Key",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_license_option",
            array(
                'type' => 'text',
                'id' => "alanforms_site_key_field"
            )
        );

        register_setting(
            'alanforms_settings',
            "alanforms_api_key_field",
            ["sanitize_callback" => "sanitize_text_field"]
        );
        add_settings_field(
            "alanforms_api_key_field",
            "API-Key",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_license_option",
            array(
                'type' => 'text',
                'id' => "alanforms_api_key_field"
            )
        );

        register_setting(
            'alanforms_settings',
            "alanforms_contact_form_7_integration",
            ["sanitize_callback" => [__CLASS__, 'sanitize_boolean']]
        );
        add_settings_field(
            "alanforms_contact_form_7_enabled",
            "Contact-Form-7 Integration",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_implementation_option",
            array(
                'type' => 'checkbox',
                'id' => "alanforms_contact_form_7_integration"
            )
        );

        register_setting(
            'alanforms_settings',
            "alanforms_elementor_pro_integration",
            ["sanitize_callback" => [__CLASS__, 'sanitize_boolean']]
        );
        add_settings_field(
            "alanforms_elementor_pro_enabled",
            "Elementor Pro Integration",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_implementation_option",
            array(
                'type' => 'checkbox',
                'id' => "alanforms_elementor_pro_integration"
            )
        );

        register_setting(
            'alanforms_settings',
            "alanforms_language",
            ["sanitize_callback" => [__CLASS__, "sanitize_language_choice_field"]]
        );
        add_settings_field(
            "alanforms_language",
            "Language",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_widget_option",
            array(
                'type' => 'select',
                'id' => "alanforms_language",
                'params' => [
                    'English' => 'en',
                    'German' => 'de',
                    'Spanish' => 'es',
                    'French' => 'fr',
                    'Italian' => 'it',
                    'Adapt to site' => '--',
                    'Custom' => 'custom'
                ]
            )
        );

        register_setting(
            'alanforms_settings',
            'alanforms_language_attribute_unverified',
            ["sanitize_callback" => "sanitize_text_field"]
        );
        add_settings_field(
            "alanforms_language_attribute_unverified",
            "Unverified",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_widget_option",
            array(
                'type' => 'text',
                'id' => "alanforms_language_attribute_unverified",
                'class' => "alanforms_language_custom_override",
                'placeholder' => "There was an error validating"
            )
        );

        register_setting(
            'alanforms_settings',
            'alanforms_language_attribute_verified',
            ["sanitize_callback" => "sanitize_text_field"]
        );
        add_settings_field(
            "alanforms_language_attribute_verified",
            "Verified",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_widget_option",
            array(
                'type' => 'text',
                'id' => "alanforms_language_attribute_verified",
                'class' => "alanforms_language_custom_override",
                'placeholder' => "Verification successful!"
            )
        );

        register_setting(
            'alanforms_settings',
            'alanforms_language_attribute_working',
            ["sanitize_callback" => "sanitize_text_field"]
        );
        add_settings_field(
            "alanforms_language_attribute_working",
            "Working",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_widget_option",
            array(
                'type' => 'text',
                'id' => "alanforms_language_attribute_working",
                'class' => "alanforms_language_custom_override",
                'placeholder' => "Verification in progress"
            )
        );

        register_setting(
            'alanforms_settings',
            'alanforms_language_attribute_start',
            ["sanitize_callback" => "sanitize_text_field"]
        );
        add_settings_field(
            "alanforms_language_attribute_start",
            "Start",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_widget_option",
            array(
                'type' => 'text',
                'id' => "alanforms_language_attribute_start",
                'class' => "alanforms_language_custom_override",
                'placeholder' => "Start verification"
            )
        );

        register_setting(
            'alanforms_settings',
            'alanforms_language_attribute_retry',
            ["sanitize_callback" => "sanitize_text_field"]
        );
        add_settings_field(
            "alanforms_language_attribute_retry",
            "Retry",
            [__CLASS__, 'alanforms_render_field'],
            Settings::$settings_page_name,
            "alanforms_widget_option",
            array(
                'type' => 'text',
                'id' => "alanforms_language_attribute_retry",
                'class' => "alanforms_language_custom_override",
                'placeholder' => "Retry"
            )
        );
    }

    public static function sanitize_boolean($str)
    {
        return filter_var($str, FILTER_VALIDATE_BOOLEAN);
    }

    public static function sanitize_language_choice_field($str)
    {
        $choices = ["en", "de", "es", "fr", "it", "--", "custom"];
        return in_array($str, $choices) ? $str : "en";
    }

    public static function alanforms_header_option_callback()
    {
    }

    public static function alanforms_render_field($args)
    {
        $value = esc_html(get_option($args["id"]));
        switch ($args["type"]) {

            case "checkbox":
                $value = ($value) ? 'checked' : '';
                echo "<input type='" . esc_html($args['type']) . "' name='" . esc_html($args["id"]) . "' " . esc_html($value) . ">";
                break;

            case "text":
                echo "<input " .
                    (isset($args['class']) ? "class='" . esc_html($args['class']) . "'" : "") .
                    (isset($args['placeholder']) ? "placeholder='" . esc_html($args['placeholder']) . "'" : "") .
                    " type='" . esc_html($args['type']) . "' name='" . esc_html($args["id"]) . "' value='" . esc_html($value) . "'>";
                break;

            case "select":

                echo "<select name='" . esc_html($args["id"]) . "' >";

                foreach ($args["params"] as $key => $option_value) {
                    $selected = ($option_value == $value) ? "selected" : "";
                    echo "<option value='" . esc_html($option_value) . "' " . esc_html($selected) . " >" . esc_html($key) . "</option>";
                }

                echo "</select>";
                break;

        }
    }

    public static function admin_scripts($hook)
    {
        if ($hook == 'toplevel_page_' . Settings::$settings_page_name)
            wp_enqueue_script(
                "alanforms_admin_language_script",
                plugins_url("../assets/js/adminLanguageVisibility.js", __FILE__),
                [],
                "1.0.0",
                ['in_footer' => true]
            );
    }

    public static function register_forms_setting_function()
    {
        //check credential validity
        if (!ApiKeyChecker::getCredentialValidity(true)) {
            if (sanitize_text_field(get_option("alanforms_api_key_field")) === "" && sanitize_text_field(get_option("alanforms_site_key_field")) === "") {
                add_settings_error("API-Key", "credential-error", "No keys configured. Configure them for AlanCaptcha Forms to work.");
            } else {
                add_settings_error("API-Key", "credential-error", "AlanCaptcha credentials are wrong. Make sure they are correct before using AlanCaptcha.");
            }
        }

        ?>
        <div class="wrap">
            <a style="box-shadow: none;" href="https://alancaptcha.com/" target="_blank">
                <svg style="width: 200px;" width="100%" height="100%" viewBox="0 0 801 245" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve"
                    xmlns:serif="http://www.serif.com/"
                    style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;">
                    <g>
                        <path class="section-page-header__logo-image-logo"
                            d="M103.159,10.376c-0.442,0.264 -22.028,8.76 -47.97,18.881c-25.94,10.12 -48.853,19.1 -50.917,19.956l-3.751,1.556l-0.438,5.631c-4.654,59.869 22.402,124.272 66.56,158.433c11.427,8.84 33.744,21.106 38.392,21.1c3.458,-0.003 17.196,-6.991 29.761,-15.138c46.911,-30.415 76.991,-93.089 75.397,-157.095c-0.316,-12.662 -0.343,-12.813 -2.399,-13.651c-12.361,-5.037 -101.517,-39.776 -102.49,-39.935c-0.738,-0.12 -1.702,-0.003 -2.145,0.262Zm40.804,178.318c-9.822,9.721 -20.94,18.037 -33.159,24.601l-5.854,3.146l-6.876,-3.79c-12.534,-6.908 -23.07,-14.498 -32.224,-23.382l0.021,0l39.344,-119.251l38.748,118.676Zm-52.566,-153.63l-43.749,132.262c-2.425,-3.614 -4.75,-7.412 -6.999,-11.415c-13.61,-24.226 -22.844,-59.57 -22.781,-87.194l0.012,-5.348l43.544,-16.808c13.919,-5.373 23.425,-9.033 29.973,-11.497Zm100.796,28.929l0.01,4.505c0.084,36.422 -10.848,70.885 -30.071,98.54l-43.327,-132.01c8.022,3.091 19.292,7.44 31.521,12.165l38.86,15.014l3.007,1.786Z"
                            style="fill-rule:nonzero;fill:#09f;"></path>
                        <path class="section-page-header__logo-image-text"
                            d="M713.272,38.948l0.167,-0.554l61.747,109.931l-0,-109.377l26.066,0l0,158.682l-26.066,0l-61.747,-109.373l-0,109.373l-26.066,0l-0,-158.682l25.899,0Zm-270.685,0l0,139.089l72.43,0l0,19.593l-98.496,0l-0,-158.682l26.066,0Zm-47.489,158.682l-26.493,0l-40.809,-118.85l-41.236,118.85l-26.707,0l55.124,-158.466l25.425,-0l54.696,158.466Zm272.058,0l-26.494,0l-40.808,-118.85l-41.236,118.85l-26.708,0l55.124,-158.466l25.426,-0l54.696,158.466Z"
                            style="fill-rule:nonzero;"></path>
                    </g>
                </svg>
            </a>
            <?php settings_errors(); ?>
            <p>Add your Site-Key and API-Key in order for the plugin to work. If not valid, Alan-Captcha will have no
                effect.
            </p>
            <form action="options.php" method="post">
                <?php echo esc_html(settings_fields("alanforms_settings")); ?>
                <?php echo esc_html(do_settings_sections(Settings::$settings_page_name)); ?>
                <?php submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}