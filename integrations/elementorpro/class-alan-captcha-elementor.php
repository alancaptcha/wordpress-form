<?php
if (!defined('ABSPATH') || !defined('ELEMENTOR_PRO_VERSION')) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor Form Field - AlanCaptcha
 *
 * Add a new "Credit Card Number" field to Elementor form widget.
 *
 * @since 1.0.0
 */
class Elementor_Alan_Captcha_Field extends \ElementorPro\Modules\Forms\Fields\Field_Base
{



	/**
	 * Field constructor.
	 *
	 * Used to add a script to the Elementor editor preview.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
		add_action('elementor/preview/init', [$this, 'editor_preview_footer']);
	}

	/**
	 * Get field type.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Field type.
	 */
	public function get_type()
	{
		return 'alan-captcha-field';
	}

	/**
	 * Get field name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string Field name.
	 */
	public function get_name()
	{
		return esc_html__('Alan Captcha', 'elementor-form-alan-captcha-field');
	}

	/**
	 * Render field output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param mixed $item
	 * @param mixed $item_index
	 * @param mixed $form
	 * @return void
	 */
	public function render($item, $item_index, $form)
	{
		$form_id = $form->get_id();
		$form->add_render_attribute(
			'input' . $item_index,
			[
				'style' => 'display: none;',
				'for' => $form_id . $item_index,
				'type' => 'text',
			]
		);

		echo Renderer::render(true);
		echo '<input ' . $form->get_render_attribute_string('input' . $item_index) . '>';
	}

	/**
	 * Field validation.
	 *
	 * Validate credit card number field value to ensure it complies to certain rules.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \ElementorPro\Modules\Forms\Classes\Field_Base   $field
	 * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
	 * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
	 * @return void
	 */
	public function validation($field, $record, $ajax_handler)
	{

		$success = PuzzleValidator::validate($_POST['alan-solution']);
		if (!$success) {
			$ajax_handler->add_error(
				$field['id'],
				esc_html__("AlanCaptcha couldn't verify this request.")
			);
		}
	}

	/**
	 * Update form widget controls.
	 *
	 * Add input fields to allow the user to customize the credit card number field.
	 *
	 * @since 1.0.0
	 * @access public
	 * @param \Elementor\Widget_Base $widget The form widget instance.
	 * @return void
	 */
	public function update_controls($widget)
	{
		$elementor = \ElementorPro\Plugin::elementor();

		$control_data = $elementor->controls_manager->get_control_from_stack($widget->get_unique_name(), 'form_fields');
		if (is_wp_error($control_data)) {
			return;
		}

		$field_controls = [
			'monitor-tag' => [
				'name' => 'monitor-tag',
				'label' => esc_html__('Monitor Tag', 'elementor-form-alan-captcha-field'),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => 'general',
				'condition' => [
					'field_type' => $this->get_type(),
				],
				'tab' => 'content',
				'inner_tab' => 'form_fields_content_tab',
				'tabs_wrapper' => 'form_fields_tabs',
			]
		];

		$control_data['fields'] = $this->inject_field_controls($control_data['fields'], $field_controls);
		// remove required field / copied from honeypot file
		foreach ($control_data['fields'] as $index => $field) {
			if ('required' === $field['name'] || 'label' === $field['name']) {
				$control_data['fields'][$index]['conditions']['terms'][] = [
					'name' => 'field_type',
					'operator' => '!in',
					'value' => [
						'alan-captcha-field',
					],
				];
			}
		}
		$widget->update_control('form_fields', $control_data);
	}


	/**
	 * Elementor editor preview.
	 *
	 * Add a script to the footer of the editor preview screen.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function editor_preview_footer() {
		   add_action( 'wp_footer', [ $this, 'content_template_script' ] );
	   }
   
	/**
	 * Content template script.
	 *
	 * Add content template alternative, to display the field in Elementor editor.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return void
	 */
	public function content_template_script() {
		   ?>
		   <script>
			   jQuery( document ).ready( () => {

				   elementor.hooks.addFilter(
					   'elementor_pro/forms/content_template/field/<?php echo $this->get_type(); ?>',
					   function ( inputField, item, i ) {
						   return "<div class='alan-captcha' data-showcase='true'></div><script>window.alanInitInstances();<\/script>";
					   }, 10, 3
				   );


			   });
		   </script>
		   <?php
	   }
}