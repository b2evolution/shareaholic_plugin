<?php 

class shareaholic_plugin extends Plugin
{
	/**
	 * Variables below MUST be overriden by plugin implementations,
	 * either in the subclass declaration or in the subclass constructor.
	 */

	var $name = 'shareaholic';
	var $code = 'evo_shareaholic';
	var $priority = 50;
	var $version = '1.5';
	var $author = 'The b2evo Group';
	var $group = 'rendering';
    var $number_of_installs = 1;


	/**
	 * Init
	 */
	function PluginInit( & $params )
	{
		$this->name = T_( 'Shareaholic Share' );
		$this->short_desc = T_('Share contents to your favorite social networks using the Shareaholic service.');
		$this->long_desc = T_('Share contents to your favorite social networks using the Shareaholic service.');
	}


	function get_coll_setting_definitions( & $params )
	{
		$default_params = array_merge(
            $params,
            array(
                'default_post_rendering' => 'opt-out'
            )
        );

		$plugin_settings = array(
            'shareaholic_enabled' => array(
                    'label' => T_('Enabled'),
                    'type' => 'checkbox',
                    'note' => 'Is the plugin enabled for this collection?',
                ),
            'shareaholic_site_id' => array(
                'label' => T_('Site ID'),
                'size' => 70,
                'defaultvalue' => '',
                'note' => T_('The ID that you get from your social sharing service.'),
            ),
            'shareaholic_applocation_app_id' => array(
                'label' => T_('Location APP ID'),
                'size' => 70,
                'defaultvalue' => '',
                'note' => T_('The Id of the location created for your site in the Shareaholic\'s Dashboard. See documentation for details.'),
            ),
        );

		return array_merge( $plugin_settings, parent::get_coll_setting_definitions( $default_params ) );
			
	}


	function SkinBeginHtmlHead( & $params )
	{
        global $Blog;

        global $Blog;

        if( $this->get_coll_setting('shareaholic_enabled', $Blog) ) {

            $script = "
//<![CDATA[
  (function() {
    var shr = document.createElement('script');
    shr.setAttribute('data-cfasync', 'false');
    shr.src = '//dsms0mj1bbhn4.cloudfront.net/assets/pub/shareaholic.js';
    shr.type = 'text/javascript'; shr.async = 'true';
    shr.onload = shr.onreadystatechange = function() {
      var rs = this.readyState;
      if (rs && rs != 'complete' && rs != 'loaded') return;
      var site_id = '" . $this->get_coll_setting('shareaholic_site_id', $Blog) . "';
      try { Shareaholic.init(site_id); } catch (e) {}
    };
    var s = document.getElementsByTagName('script')[0];
    s.parentNode.insertBefore(shr, s);
  })();
//]]>";
            add_js_headline($script);
        }
	}

	function RenderItemAsHtml( & $params )
	{
        global $Blog;

        if( $this->get_coll_setting('shareaholic_enabled', $Blog) && $this->get_coll_setting('shareaholic_applocation_app_id', $Blog) ) {
            $content = & $params['data'];

            $content .= "\n"
                .'<div class="shareaholic-canvas" data-app="share_buttons" data-app-id="' . $this->get_coll_setting('shareaholic_applocation_app_id', $Blog) . '"></div>'
                ."\n";
        }

	}
}