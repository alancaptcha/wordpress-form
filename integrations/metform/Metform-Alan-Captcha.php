<?php

class Metform_Alan_Captcha extends \Elementor\Widget_Base
{
	use \MetForm\Widgets\Widget_Notice;
	public function __construct($data = [], $args = null)
	{
		parent::__construct($data, $args);


	}
	public function get_name()
	{
		return 'alan-captcha-widget';
	}

	public function get_title()
	{
		return esc_html__('Alan Captcha', 'elementor-addon');
	}

	public function get_icon()
	{
		return 'eicon-lock';
	}

	public function get_categories()
	{
		return ['metform'];
	}

	public function get_keywords()
	{
		return ['captcha', 'alan', 'security', 'input'];
	}

	public function get_script_depends(): array
	{
		return ['alan-captcha'];
	}

	public function get_custom_help_url()
	{
		return 'https://alancaptcha.com/';
	}

	protected function render()
	{
		echo Renderer::render(false);
	}

	protected function content_template()
	{
		echo "<p>Alan Captcha will show up here. This is just the placeholder.</p>";
	}
}