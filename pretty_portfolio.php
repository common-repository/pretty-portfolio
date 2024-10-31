<?php 
/*
Plugin Name: pretty portfolio
Plugin URI:https://sheulyshila.in/smartconst
Version: 1.0.0
Description:Pretty portfolio was a easy  portfolio plugin.
Author: sheulyshila
Slug: preety-portfolio
Author URI: sheulyshila2017@gmail.com
License: GPLv2 or later
Tags:portfolio
Text Domain: preety
*/
require_once(plugin_dir_path(__FILE__).'/lib/csf/cs-framework.php');
require_once(plugin_dir_path(__FILE__).'/inc/metabox.php');

class pretty_portfolios{
	public function __construct(){
		add_action( 'init', array($this,'pretty_portfolio_main'), 0 );
		add_action( 'wp_enqueue_scripts', array($this,'preety_portfolio_assets'), 0 );
		add_shortcode('preety_portfolios',array($this,'preety_portfolio_shortcode'));
		add_action('admin_menu',array($this,'preety_portfolio_custom_menu_page'));
		add_action('admin_init',array($this,'preety_portfolio_custom_field'));
		add_action( 'init', array($this,'preety_textdomain_load'));

	}
	function preety_textdomain_load(){
		load_plugin_textdomain( 'preety', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
	}
	function preety_portfolio_custom_field(){
		register_setting('field_group','value_group');
		add_settings_section('number_item',' ',array($this,'post_show_callback_function'),'preety_portfolio_seettings_options');
		add_settings_field('show_post_item','Number of item show in page',array($this,'number_item_show_callback_func'),'preety_portfolio_seettings_options','number_item');
	}

	function post_show_callback_function(){
		echo " ";
	}

	function number_item_show_callback_func(){
		$value= get_option('value_group');
		$number_item = $value['show_text'];
		?>
		<input type="number" name="value_group[show_text]" value="<?php echo esc_attr($number_item);?>">
	<?php }

	function preety_portfolio_custom_menu_page(){
		
		add_submenu_page('edit.php?post_type=preetty_portfolio','Settings options','Settings options','manage_options','preety_portfolio_seettings_options',
			array($this,'display_callbacks_function'));
	}
	function display_callbacks_function()
	{?>
		<div class="wrap">
			<h2><?php _e('Options','preety');?></h2>
			<?php settings_errors(); ?>
			<form action="options.php" method="POST">
				<?php
				settings_fields('field_group');
				do_settings_sections('preety_portfolio_seettings_options');
				submit_button();
				?>
			</form>
			
		</div>
		
	<?php }

	public function preety_portfolio_assets(){
		wp_enqueue_style('base',plugins_url('assets/css/base.css',__FILE__));

		wp_enqueue_script('imagesloaded',plugins_url('assets/js/imagesloaded.pkgd.min.js',__FILE__),array('jquery'));
		wp_enqueue_script('masonry',plugins_url('assets/js/masonry.pkgd.min.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('charming',plugins_url('assets/js/charming.min.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('TweenMax',plugins_url('assets/js/TweenMax.min.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('demo',plugins_url('assets/js/demo.js',__FILE__),array('jquery'),'1.0.0',true);
		wp_enqueue_script('test',plugins_url('assets/js/test.js',__FILE__),array('jquery'),'1.0.0',true);
	}
	public function preety_portfolio_shortcode(){ 
		?>
		<main>
			<div class="grid-wrap">
				<div class="grid">
					<?php 
					$value  = get_option('value_group');
					$number_item = $value['show_text'];
					$portfolios = new wp_query(array(
						'post_type'=>'preetty_portfolio',
						'posts_per_page'=>$number_item,
					));
					?>
					<?php while($portfolios->have_posts()):$portfolios->the_post();?>
						<a href="<?php the_permalink();?>" class="grid__item">
							<div class="grid__item-bg"></div>
							<div class="grid__item-wrap">
								<img class="grid__item-img" src="<?php the_post_thumbnail_url('large');?>" alt="Some image" />
							</div>
							<h3 class="grid__item-title"><?php the_title();?></h3>
							<?php 
							$item_number  =" ";
							if(function_exists('get_post_meta')){
								$content_meta = get_post_meta(get_the_ID(),'_content_meta',true);
								$item_number= isset($content_meta['item_number']) ? 
								$content_meta['item_number']:array();
							}?>
							<h4 class="grid__item-number"><?php  echo esc_html($item_number);?></h4>
						</a>
					<?php endwhile;?>
				</div>
			</div><!-- /grid-wrap -->
			<div class="content">
				<?php while($portfolios->have_posts()):$portfolios->the_post();
					$sub_title  =" ";
					if(function_exists('get_post_meta')){
						$content_meta = get_post_meta(get_the_ID(),'_content_meta',true);
						$sub_title = isset($content_meta['title']) ? $content_meta['title']:array();
					}
					?>
					<div class="content__item">
						<div class="content__item-intro">
							<img class="content__item-img" src="<?php the_post_thumbnail_url('full');?>" alt="Some image" />
							<h2 class="content__item-title"><?php the_title();?></h2>
						</div>
						<h3 class="content__item-subtitle">"<?php echo esc_html($sub_title);?>"</h3> 
						<div class="content__item-text">
							<p><?php echo get_the_content();?></p>
						</div>
					</div><!-- /content__item -->
				<?php endwhile;?>
				<?php wp_reset_query();?>
				<button class="content__close"><?php _e('Close','preety');?></button>
				<svg class="content__indicator icon icon--caret"><use xlink:href="#icon-caret"></use></svg>
			</div>
		</main>
	<?php }



	// Register Custom Post Type
	function pretty_portfolio_main() {

		$labels = array(
			'name'                  => _x( 'Preety portfolio', 'Post Type General Name', 'preety' ),
			'singular_name'         => _x( 'Preety portfolio', 'Post Type Singular Name', 'preety' ),
			'menu_name'             => __( 'Preety portfolio', 'preety' ),
			'name_admin_bar'        => __( 'Post Type', 'preety' ),
			'archives'              => __( 'Item Archives', 'preety' ),
			'attributes'            => __( 'Item Attributes', 'preety' ),
			'parent_item_colon'     => __( 'Parent Item:', 'preety' ),
			'all_items'             => __( 'All Items', 'preety' ),
			'add_new_item'          => __( 'Add New Item', 'preety' ),
			'add_new'               => __( 'Add New', 'preety' ),
			'new_item'              => __( 'New Item', 'preety' ),
			'edit_item'             => __( 'Edit Item', 'preety' ),
			'update_item'           => __( 'Update Item', 'preety' ),
			'view_item'             => __( 'View Item', 'preety' ),
			'view_items'            => __( 'View Items', 'preety' ),
			'search_items'          => __( 'Search Item', 'preety' ),
			'not_found'             => __( 'Not found', 'preety' ),
			'not_found_in_trash'    => __( 'Not found in Trash', 'preety' ),
			'featured_image'        => __( 'Featured Image', 'preety' ),
			'set_featured_image'    => __( 'Set featured image', 'preety' ),
			'remove_featured_image' => __( 'Remove featured image', 'preety' ),
			'use_featured_image'    => __( 'Use as featured image', 'preety' ),
			'insert_into_item'      => __( 'Insert into item', 'preety' ),
			'uploaded_to_this_item' => __( 'Uploaded to this item', 'preety' ),
			'items_list'            => __( 'Items list', 'preety' ),
			'items_list_navigation' => __( 'Items list navigation', 'preety' ),
			'filter_items_list'     => __( 'Filter items list', 'preety' ),
		);
		$args = array(
			'label'                 => __( 'Preety portfolio', 'preety' ),
			'description'           => __( 'Post Type Description', 'preety' ),
			'labels'                => $labels,
			'supports'              => array( 'title','thumbnail','editor'),
			'taxonomies'            => array( 'category', 'post_tag' ),
			'hierarchical'          => false,
			'public'                => true,
			'show_ui'               => true,
			'show_in_menu'          => true,
			'menu_position'         => 5,
			'menu_icon'             => 'dashicons-tickets-alt',
			'show_in_admin_bar'     => true,
			'show_in_nav_menus'     => true,
			'can_export'            => true,
			'has_archive'           => true,
			'exclude_from_search'   => false,
			'publicly_queryable'    => true,
			'capability_type'       => 'page',
		);
		register_post_type( 'preetty_portfolio', $args );
	}

}
new pretty_portfolios;