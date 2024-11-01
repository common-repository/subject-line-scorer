<?php
/*
Plugin Name: Subject Line Scorer
Plugin URI: https://automizy.com/
Description: Show off your copywriting skills! This AI-powered plugin grades the email subject lines in your blog post and displays the score next to each subject.
Version: 1.2
Author: Automizy
Author URI: https://automizy.com/
Stable tag: 1.2
*/

//error_reporting(E_ALL);
//ini_set('display_errors', 'On');


// core initiation

class vooMainStartSLSC{
		var $locale;
		function __construct( $locale, $includes, $path ){
			$this->locale = $locale;

			// include files
			foreach( $includes as $single_path ){
				include( $path.$single_path );
			}
			// calling localization
			add_action('plugins_loaded', array( $this, 'myplugin_init' ) );
		}
		function myplugin_init() {
		 $plugin_dir = basename(dirname(__FILE__));
		 load_plugin_textdomain( $this->locale , false, $plugin_dir );
		}
	}



// initiate main class
new vooMainStartSLSC('wta', array(
	'modules/scripts.php',
	'modules/hooks.php',
	'modules/ajax.php',
	'modules/shortcodes.php',
	'modules/settings.php',

), dirname(__FILE__).'/' );



#include('modules/functions.php');
#include('modules/shortcodes.php');
#include('modules/settings.php');
#include('modules/meta_box.php');
#include('modules/widgets.php');
#include('modules/hooks.php');
#include('modules/cpt.php');
#include('modules/scripts.php');
#include('modules/ajax.php');

?>
