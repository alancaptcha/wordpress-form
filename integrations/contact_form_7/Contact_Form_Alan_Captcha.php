<?php
class Contact_Form_Alan_Captcha extends WP_Widget
{

    function __construct()
    {
        wpcf7_add_form_tag(
            'alancaptcha*',
            array($this, 'custom_cf7_tag_handler'),
        );
        add_filter('wpcf7_validate_alancaptcha*', [$this, 'alancaptcha_validation_filter'], 20, 2);
    }

    function alancaptcha_validation_filter($result, $tag)
    {

        if (!PuzzleValidator::validate($_POST['alan_solution'])) {
            $result->invalidate($tag, "The solution provided to Alan-Captcha is not correct");
        }
        return $result;
    }

    function custom_cf7_tag_handler($tag)
    {
        $html = sprintf(
            Renderer::render(true),
        );
        return $html;
    }
}


