<?php
namespace AlanForms\Integration\ElementorPro;


use AlanForms\PuzzleValidator;
use AlanForms\Renderer;
use ElementorPro\Modules\Forms\Classes;
use Elementor\Controls_Manager;
use ElementorPro\Modules\Forms\Fields\Field_Base;
use ElementorPro\Plugin;

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}
/**
 * Elementor Form Field - AlanCaptcha
 *
 * @since 1.0.0
 */
class Elementor_Alan_Captcha_Field extends Field_Base
{

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();

		// Only needed for editor preview
		add_action('elementor/preview/init', [$this, 'editor_preview_footer']);
	}

	/**
	 * Return the field type (unique slug).
	 */
	public function get_type()
	{
		return 'alan-captcha-field';
	}


	/**
	 * Return the field labelshown in Elementor form field dropdown.
	 */
	public function get_name()
	{
		return esc_html__('Alan Captcha', 'alan-captcha-forms');
	}

	/**
	 * Render field output on the frontend.
	 */
	public function render($item, $item_index, $form)
	{
		// Render captcha HTML
		echo wp_kses_post(Renderer::render(true));
	}

	/**
	 * Field validation.
	 */
	public function validation($field, $record, $ajax_handler)
	{
		if (!PuzzleValidator::nonceSuccessfull()) {
			$ajax_handler->add_error(
				$field['id'],
				esc_html__("AlanCaptcha couldn't verify this request.", 'alan-captcha-forms')
			);
			return;
		}

		$solution = isset($_POST['alan-solution'])
			? sanitize_text_field(wp_unslash($_POST['alan-solution']))
			: '';

		if (!PuzzleValidator::validate($solution)) {
			$ajax_handler->add_error(
				$field['id'],
				esc_html__("AlanCaptcha couldn't verify this request.", 'alan-captcha-forms')
			);
		}
	}

	/**
	 * Update Elementor form widget controls.
	 */
	public function update_controls($widget)
	{
		$elementor = Plugin::elementor();
		$control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');

		if (is_wp_error($control_data)) {
			return;
		}

		$field_controls = [
			'monitor-tag' => [
				'name' => 'monitor-tag',
				'label' => esc_html__('Monitor Tag', 'alan-captcha-forms'),
				'type' => Controls_Manager::TEXT,
				'default' => 'general',
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			],
		];

		$control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);

		// Remove required & label fields for captcha
		foreach ($control_data['fields'] as $index => $field) {
			if (in_array($field['name'], ['required', 'field_label'], true)) {
				$control_data['fields'][$index]['conditions']['terms'][] = [
					'name' => 'field_type',
					'operator' => '!in',
					'value' => [$this->get_type()],
				];
			}
		}

		$widget->update_control('form_fields', $control_data);
	}


	/**
	 * Editor preview footer script.
	 */
	public function editor_preview_footer()
	{
		add_action('wp_footer', [$this, 'content_template_script']);
	}
	/**
	 * Content template script for Elementor editor.
	 */
	public function content_template_script()
	{
		wp_enqueue_script(
			'elementor_content_template_script',
			plugins_url('../../assets/js/elemContentTemplateScript.js', __FILE__),
			[],
			'1.0',
			true
		);

		$data = ['typeUrl' => esc_url($this->get_type())];

		wp_add_inline_script(
			'elementor_content_template_script',
			'const data = ' . wp_json_encode($data) . ';',
			'before'
		);
	}
}