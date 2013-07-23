<?php
/**
 * Minifier interface for CF Asset Optimizer JavaScript
 */

class cfao_js_minifier {
	
	public static function class_name() {
		return 'cfao_js_minifier';
	}

	public static function register($handles) {
		$class_name = self::class_name();
		if (!empty($class_name)) {
			$handles = array_merge($handles, array($class_name));
		}
		return $handles;
	}

	public static function listItem() {
		return array(
			'title' => __('CF JavaScript Minfier'),
			'description' => __('This plugin minifies the output of the CF JavaScript Optimizer prior to caching using the PHP Minify library.'),
		);
	}
	
	public static function setHooks() {
		// We want to minify based on single hook contents here
		add_action('cfao_single_contents', 'cfao_js_minifier::minify', 10, 3);
	}
	
	public static function minify($string, $type = 'js', $handle = '') {
		if ($type == 'js') {
			// Run the minification
			if (!class_exists('JSMin')) {
				set_include_path(CFAO_PLUGIN_DIR.'lib/minify/min/lib');
				include 'JSMin.php';
				restore_include_path();
			}
			$minified = JSMin::minify($string);
			if (!empty($minified)) {
				$string = $minified;
			}
		}
		return $string;
	}
	
}
add_action('cfao_minifiers', 'cfao_js_minifier::register');