<?php

class WPDeveloper {
	public function verify(){
		$loaderPath = false;
		if(is_file(WPMU_PLUGIN_DIR.'/jk-wp-developer/loader.php')){
			$loaderPath = WPMU_PLUGIN_DIR.'/jk-wp-developer/loader.php';
		}else if (is_file(WP_PLUGIN_DIR . '/jk-wp-developer/loader.php')){
			$loaderPath = WP_PLUGIN_DIR . '/jk-wp-developer/loader.php';
		}
		if(!$loaderPath){
			add_action('admin_notices', array($this,'_requirement_notice'));
		}else{
			require_once($loaderPath);
		}
	}
	public function _requirement_notice(){
		$plugin = get_plugin_data( __FILE__);
		echo '<div class="error"><p>Error. '.$plugin['Name'].' requires <strong>JK WP Developer Kit</strong> plugin</p></div>';
	}
}