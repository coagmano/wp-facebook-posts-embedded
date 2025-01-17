<?php
/**
 * Plugin Name: Facebook Posts Embedded LaborHerald Fork
 *
 * Description: Permits to add embedded posts from Facebook almost everywhere. Forked from https://github.com/mircobabini/wp-facebook-posts-embedded
 *
 * Plugin URI: https://github.com/coagmano/wp-facebook-posts-embedded
 * Version: 1.0.0
 * Author: Frederick Stark
 * Author URI: https://github.com/coagmano
 * License: GPLv2
 * @package wp-facebook-posts-embedded-fork
 */


class fb_embed_posts_fork {
	public function __construct() {
		add_shortcode ('fb_embed_post', array (&$this, 'shortcode') );
		add_action ('admin_init', array (&$this, 'add_shortcode_button'));
	}

	public function shortcode ($atts) {
		extract( shortcode_atts( array(
			 'href' => false,
			 'width' => '550',
		), $atts ) );

		if (!$href)
			return '';

		ob_start ();
		?>
		<div class="embed-container">
			<div id="fb-root"></div>
			<script>
			  (function(d, s, id) { var js, fjs = d.getElementsByTagName(s)[0]; if (d.getElementById(id)) return; js = d.createElement(s); js.id = id; js.src = "//connect.facebook.net/en_US/all.js#xfbml=1"; fjs.parentNode.insertBefore(js, fjs); }(document, 'script', 'facebook-jssdk'));
				  </script>
			<fb:post href="<?php echo $href?>" width="<?php echo $width?>"></fb:post>
		</div>
		<?php

		$c = ob_get_contents (); ob_clean ();
		return $c;
	}

	function add_shortcode_button () {
		if (current_user_can('edit_posts') && current_user_can ('edit_pages')) {
			 add_filter ('mce_external_plugins', array ($this, 'add_shortcode_plugin'));
			 add_filter ('mce_buttons', array ($this, 'register_shortcode_button'));
		}
	}
	function register_shortcode_button ($buttons) {
		array_push ($buttons, "fb_embed_post");
		return $buttons;
	}
	function add_shortcode_plugin ($plugin_array) {
		if (function_exists ('wp_enqueue_media')) {
			wp_enqueue_media ();
		} else {
			wp_enqueue_style ('thickbox');
			wp_enqueue_script ('media-upload');
			wp_enqueue_script ('thickbox');
		}

		$plugin_array['fb_embed_post'] = plugins_url ('js/shortcode.js', plugin_basename (__FILE__));
		return $plugin_array;
	}
}

global $fb_embed_posts_fork;
$fb_embed_posts_fork = new fb_embed_posts_fork();
