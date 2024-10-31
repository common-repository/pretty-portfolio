<?php 
    defined( 'CS_ACTIVE_FRAMEWORK' )   or  define( 'CS_ACTIVE_FRAMEWORK',   false  );
    defined( 'CS_ACTIVE_METABOX'   )   or  define( 'CS_ACTIVE_METABOX',     true  );
    defined( 'CS_ACTIVE_TAXONOMY'   )  or  define( 'CS_ACTIVE_TAXONOMY',    false  );
    defined( 'CS_ACTIVE_SHORTCODE' )   or  define( 'CS_ACTIVE_SHORTCODE',   false  );
    defined( 'CS_ACTIVE_CUSTOMIZE' )   or  define( 'CS_ACTIVE_CUSTOMIZE',   false  );
    defined( 'CS_ACTIVE_LIGHT_THEME' ) or  define( 'CS_ACTIVE_LIGHT_THEME', true );

    function preety_slider_metabox(){
    	$options      = array(); // remove old options
    	$options[]      = array(
    		'id'            => '_content_meta',
    		'title'         => 'Content Information',
  'post_type'     => 'preetty_portfolio', // or post or CPT or array( 'page', 'post' )
  'context'       => 'normal',
  'priority'      => 'default',
  'sections'      => array(
    // begin section
  	array(
  		'name'      => 'content_info',
  		'title'     => 'Content Information',
  		'icon'      => 'fa fa-wifi',
  		'fields'    => array(

        // a field
  			array(
  				'id'    => 'title',
  				'type'  => 'text',
  				'title' => 'Sub Title'
  			),

        // a field
  			array(
  				'id'    => 'item_number',
  				'type'  => 'text',
  				'title' => 'Item Number'
  			),

  		),
  	),
  ),
);

  return $options;

    }
    add_filter('cs_metabox_options','preety_slider_metabox');
?>