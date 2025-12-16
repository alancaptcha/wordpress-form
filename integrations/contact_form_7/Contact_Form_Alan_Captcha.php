<?php
namespace AlanForms\Integration\Contact_Form;

use AlanForms\PuzzleValidator;
use AlanForms\Renderer;
use WP_Widget;
use WPCF7_FormTag;
class Contact_Form_Alan_Captcha
{

    function __construct()
    {
        wpcf7_add_form_tag(
            ['alancaptcha', 'alancaptcha*'],
            [$this, 'alanforms_handler'],
            ['name-attr'=> false,
            'not_for_mail' => true]
        );

        add_filter('wpcf7_validate_alancaptcha', [$this, 'validate'], 20, 2);
        add_filter('wpcf7_validate_alancaptcha*', [$this, 'validate'], 20, 2);
    }

    function validate($result, $tag)
    {

        $solution = isset($_POST['alan-solution'])
            ? sanitize_text_field(wp_unslash($_POST['alan-solution']))
            : '';

        if (!PuzzleValidator::validate($solution)) {

            $result->invalidate($tag, "The solution provided to Alan-Captcha is not correct.");
        }
        return $result;
    }

    function alanforms_handler($tag)
    {
        $tag = new WPCF7_FormTag($tag);
        return Renderer::render(true, $tag->name);
    }
}