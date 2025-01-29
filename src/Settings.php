<?php

require_once(__DIR__ . "/ApiKeyChecker.php");

add_action('admin_menu', 'show_register_menu');
add_action('admin_init', 'initialize');

function show_register_menu()
{
    add_menu_page("Alan Captcha Forms", "Alan Captcha Forms", "manage_options", "alan_captcha_forms_plugin", "register_forms_setting_function");
}


function initialize()
{
    add_settings_section(
        "license_option",
        "License Options",
        "header_option_callback",
        "alan_captcha_forms_plugin"
    );

    add_settings_section(
        "general_option",
        "General Options",
        "header_option_callback",
        "alan_captcha_forms_plugin"
    );


    register_setting('alan_forms_settings', "forms_site_key_field");
    add_settings_field(
        "forms_site_key_field",
        "Site-Key",
        "renderField",
        "alan_captcha_forms_plugin",
        "license_option",
        array(
            'type' => 'text',
            'id' => "forms_site_key_field"
        )
    );

    register_setting('alan_forms_settings', "forms_api_key_field");
    add_settings_field(
        "forms_api_key_field",
        "API-Key",
        "renderField",
        "alan_captcha_forms_plugin",
        "license_option",
        array(
            'type' => 'text',
            'id' => "forms_api_key_field"
        )
    );

    register_setting('alan_forms_settings', "metforms_alan_integration");
    add_settings_field(
        "metforms_enabled",
        "Metforms Integration",
        "renderField",
        "alan_captcha_forms_plugin",
        "general_option",
        array(
            'type' => 'checkbox',
            'id' => "metforms_alan_integration"
        )
    );

    register_setting('alan_forms_settings', "contact_form_7_alan_integration");
    add_settings_field(
        "contact_form_7_enabled",
        "Contact-Form-7 Integration",
        "renderField",
        "alan_captcha_forms_plugin",
        "general_option",
        array(
            'type' => 'checkbox',
            'id' => "contact_form_7_alan_integration"
        )
    );

    register_setting('alan_forms_settings', "elementor_pro_alan_integration");
    add_settings_field(
        "elementor_pro_enabled",
        "Elementor Pro Integration",
        "renderField",
        "alan_captcha_forms_plugin",
        "general_option",
        array(
            'type' => 'checkbox',
            'id' => "elementor_pro_alan_integration"
        )
    );
    /*
            $id = "language";
            register_setting('alan_settings', $id);
            add_settings_field(
                "language_field",
                "Language",
                array($this, "renderField"),
                "register_setting",
                "license_option",
                array(
                    'type' => 'text',
                    'id' => "api_key"
                )
            );*/
}


function header_option_callback()
{

}

function renderField($args)
{
    $value = get_option($args["id"]);
    if ($args['type'] == "checkbox") {

        $value = ($value) ? 'checked' : '';
        echo "<input type={$args['type']} name={$args["id"]} $value>";
    } else {
        echo "<input type={$args['type']} name={$args["id"]} value='{$value}'>";
    }
}

function register_forms_setting_function()
{
    //check credential validity
    if (!ApiKeyChecker::getCredentialValidity(true)) {
        if (get_option("forms_api_key_field") == "" && get_option("forms_site_key_field") == "") {
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
        <p>Add your Site-Key and API-Key in order for the plugin to work. If not valid, Alan-Captcha will have no effect.
        </p>
        <form action="options.php" method="post">
            <?php echo settings_fields("alan_forms_settings"); ?>
            <?php echo do_settings_sections("alan_captcha_forms_plugin"); ?>
            <?php submit_button();
            ?>
        </form>
    </div>
    <?php
}
