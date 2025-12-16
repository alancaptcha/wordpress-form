<?php
namespace AlanForms\Integration;

if (!defined('ABSPATH'))
    exit;

require_once ALAN_FORMS_PATH . "src/PuzzleValidator.php";
require_once ALAN_FORMS_PATH . "src/Renderer.php";


class Integrator
{
    public static function integrateCaptcha(): void
    {
        //contact form 7 integration
        if (get_option("alanforms_contact_form_7_integration")) {
            add_action(
                'wpcf7_init',
                function () {

                    if (class_exists("WPCF7_FormTag")) {
                        require_once ALAN_FORMS_PATH . "integrations/contact_form_7/Contact_Form_Alan_Captcha.php";
                        new \AlanForms\Integration\Contact_Form\Contact_Form_Alan_Captcha();
                    }
                }
            );
        }

        // Elementor Pro integration
        if (get_option("alanforms_elementor_pro_integration")) {

            add_action(
                'elementor_pro/forms/fields/register',
                function ($form_fields_register) {
                    if (
                        class_exists('\ElementorPro\Plugin') &&
                        class_exists('ElementorPro\Modules\Forms\Fields\Field_Base')
                    ) {
                        require_once ALAN_FORMS_PATH . "integrations/elementorpro/Elementor_Alan_Captcha_Field.php";
                        $form_fields_register->register(new \AlanForms\Integration\ElementorPro\Elementor_Alan_Captcha_Field());
                    }
                }
            );


        }

    }
}