<?php

require_once(__DIR__ . '/../src/Renderer.php');
require_once(__DIR__ . '/../src/Validator.php');
function integrateCaptcha()
{
    if (get_option("metforms_alan_integration")) {
        add_action('wp_enqueue_scripts', 'elementor_add_widgets_dependencies');
        add_action(
            'elementor/widgets/register',
            function ($widgets_manager) {
                require_once(__DIR__ . '/metform/Metform-Alan-Captcha.php');
                $widgets_manager->register(new \Metform_Alan_Captcha());
            }
        );

        add_action('rest_request_before_callbacks', 'authenticate_metforms_rest_request', 10, 3);
    }

    if (get_option("contact_form_7_alan_integration")) {
        add_action(
            'wpcf7_init',
            function () {
                require_once(__DIR__ . '/contact_form_7/Contact_Form_Alan_Captcha.php');
                new \Contact_Form_Alan_Captcha();
            }
        );
    }

    if (get_option("elementor_pro_alan_integration")) {
        add_action(
            'elementor_pro/forms/fields/register',
            function ($form_fields_register) {
                require_once(__DIR__ . '/elementorpro/class-alan-captcha-elementor.php');
                $form_fields_register->register(new \Elementor_Alan_Captcha_Field());
            }
        );
    }
}

function authenticate_metforms_rest_request($response, $handler, WP_REST_Request $request)
{
    if (str_starts_with($request->get_route(), "/metform/v1/entries/insert")) {
        if (!Validator::validate($request->get_param("alan-solution"))) {
            return new WP_Error('authorization', 'Unauthorized access.', array( 'status' => 401 ));
        }
    }
    return $response;
}

function elementor_add_widgets_dependencies()
{
    wp_register_script('alan-captcha', "https://api.alancaptcha.com/widget/1.0.0/widget.bundle.js?ver=1.0.0&r=" . uniqid());
}

integrateCaptcha();