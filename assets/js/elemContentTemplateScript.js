jQuery(document).ready(() => {
				elementor.hooks.addFilter(
					'elementor_pro/forms/content_template/field/'+data.typeUrl+'',
					function (inputField, item, i) {
						return "<div class='alan-captcha' data-showcase='true'></div><script>window.alanInitInstances();<\/script>";
					}, 10, 3
				);
			});