<?php
/*
Plugin Name: Ajax Load More
Plugin URI: http://connekthq.com/plugins/ajax-load-more
Description: A simple solution for lazy loading WordPress posts and pages with Ajax.
Author: Darren Cooney
Twitter: @KaptonKaos
Author URI: http://connekthq.com
Version: 2.7.3
License: GPL
Copyright: Darren Cooney & Connekt Media
*/	
	
define( 'ALM_VERSION', '2.7.3' );
define( 'ALM_RELEASE', 'August 6, 2015' );
define( 'ALM_STORE_URL', 'https://connekthq.com' ); // EDD CONSTANT - Store URL


/*
*  alm_install
*  
*  Activation hook
*  Create table for storing repeater
*
*  @since 2.0.0
*/

function alm_install($network_wide) {   
	
   global $wpdb;
	add_option( "alm_version", ALM_VERSION ); // Add to WP Option tbl	   	
	
   if ( is_multisite() && $network_wide ) {      
      
      // Get all blogs in the network and activate plugin on each one
      $blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
      foreach ( $blog_ids as $blog_id ) {
         switch_to_blog( $blog_id );
         alm_create_table();
         restore_current_blog();
      }
   } else {
      alm_create_table();
   }
   	
}
register_activation_hook( __FILE__, 'alm_install' );
add_action( 'wpmu_new_blog', 'alm_install' );

/* Create table function */
function alm_create_table(){
	
	global $wpdb;	
	$table_name = $wpdb->prefix . "alm";
	$blog_id = $wpdb->blogid;
	
	$defaultRepeater = '<li <?php if (!has_post_thumbnail()) { ?> class="no-img"<?php } ?>><?php if ( has_post_thumbnail() ) { the_post_thumbnail(array(100,100));}?><h3><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></h3><p class="entry-meta"><?php the_time("F d, Y"); ?></p><?php the_excerpt(); ?></li>';	
	
	/* MULTISITE */
   /* if this is a multisite blog and it's not id = 1, create default template */
   if($blog_id > 1){	   
	   
	   $dir = ALM_PATH. 'core/repeater/'. $blog_id;
	   if( !is_dir($dir) ){
	      mkdir($dir);
	   }
	   
	   $file = ALM_PATH. 'core/repeater/'. $blog_id .'/default.php';
   	if( !file_exists($file) ){
         $tmp = fopen($file, 'w');
			$w = fwrite($tmp, $defaultRepeater);
			fclose($tmp);
   	}
   	
	} 
		
	//Create table, if it doesn't already exist.	
	if($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {	
		
		$sql = "CREATE TABLE $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			name text NOT NULL,
			repeaterDefault longtext NOT NULL,
			repeaterType text NOT NULL,
			pluginVersion text NOT NULL,
			UNIQUE KEY id (id)
		);";		
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $sql );
		
		//Insert the default data in created table
		$wpdb->insert($table_name , array('name' => 'default', 'repeaterDefault' => $defaultRepeater, 'repeaterType' => 'default', 'pluginVersion' => ALM_VERSION));
		
	}	
	
}




if( !class_exists('AjaxLoadMore') ):
	class AjaxLoadMore {	
		
   	function __construct(){	   
   	
   		define('ALM_PATH', plugin_dir_path(__FILE__));
   		define('ALM_URL', plugins_url('', __FILE__));
   		define('ALM_ADMIN_URL', plugins_url('admin/', __FILE__));
   		define('ALM_NAME', '_ajax_load_more');
   		define('ALM_TITLE', 'Ajax Load More');		
   		
   		add_action( 'wp_ajax_alm_query_posts', array(&$this, 'alm_query_posts') );
   		add_action( 'wp_ajax_nopriv_alm_query_posts', array(&$this, 'alm_query_posts') );
   		add_action( 'wp_ajax_alm_query_total', array(&$this, 'alm_query_total') );
   		add_action( 'wp_ajax_nopriv_alm_query_total', array(&$this, 'alm_query_total') );
   		
   		add_action( 'wp_enqueue_scripts', array(&$this, 'alm_enqueue_scripts') );			
   		add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), array(&$this, 'alm_action_links') );
   		add_filter( 'plugin_row_meta', array(&$this, 'alm_plugin_meta_links'), 10, 2 );
   		   
   		add_shortcode( 'ajax_load_more', array(&$this, 'alm_shortcode') );		
   		
   		// Allow shortcodes in widget areas
   		add_filter( 'widget_text', 'do_shortcode' );
   		
   		// load text domain
   		load_plugin_textdomain( 'ajax-load-more', false, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );
   		
   		// Include ALM query functions
   		include_once( ALM_PATH . 'core/functions.php');
   		
   		// includes WP admin core
   		$this->alm_before_theme();	
   		
   	}	
   		
   	
   	/*
   	*  alm_before_theme
   	*  Load these files before the theme loads
   	*
   	*  @since 2.0.0
   	*/
   	
   	function alm_before_theme(){
   		if( is_admin()){
   			include_once('admin/editor/editor.php');
   			include_once('admin/admin.php');
   		}		
      }
      
      
      
   	/*
   	*  alm_action_links
   	*  Add plugin action links to WP plugin screen
   	*
   	*  @since 2.2.3
   	*/   
      
      function alm_action_links( $links ) {
         $links[] = '<a href="'. get_admin_url(null, 'admin.php?page=ajax-load-more') .'">'.__('Settings', ALM_NAME).'</a>';
         return $links;
      }
      
      
      
      /*
   	*  alm_plugin_meta_links
   	*  Add plugin meta links to WP plugin screen
   	*
   	*  @since 2.7.2.1
   	*/   
      
      function alm_plugin_meta_links( $links, $file ) {
         if ( strpos( $file, 'ajax-load-more.php' ) !== false ) {
      		$new_links = array(
					'<a href="admin.php?page=ajax-load-more-shortcode-builder">Shortcode  Builder</a>',
					'<a href="admin.php?page=ajax-load-more-add-ons">Add-ons</a>',
				);
      		
      		$links = array_merge( $links, $new_links );
      	}
	
         return $links;      
	   }
   
   
   
   	/*
   	*  alm_enqueue_scripts
   	*  Enqueue our scripts and create our localize variables
   	*
   	*  @since 2.0.0
   	*/
   
   	function alm_enqueue_scripts(){
   		
   		//wp_enqueue_script( 'ajax-load-more', plugins_url( '/core/js/ajax-load-more.js', __FILE__ ), array('jquery'),  '1.1', true );
   		wp_enqueue_script( 'ajax-load-more', plugins_url( '/core/js/ajax-load-more.min.js', __FILE__ ), array('jquery'),  '1.1', true );
   		
   		$options = get_option( 'alm_settings' );
   		
   		// Prevent loading of unnessasry posts - move user to top of page
   		$scrolltop = 'false';
   		if(!isset($options['_alm_scroll_top']) || $options['_alm_scroll_top'] != '1'){ // if unset or false
   			$scrolltop = 'false';
   		}else{ // if checked
      		$scrolltop = 'true';
   		}
   		
   		// Load CSS
   		if(!isset($options['_alm_disable_css']) || $options['_alm_disable_css'] != '1'){
   			wp_enqueue_style( 'ajax-load-more', plugins_url('/core/css/ajax-load-more.css', __FILE__ ));
   		}
   		
   		wp_localize_script(
   			'ajax-load-more',
   			'alm_localize',
   			array(
   				'ajaxurl'   => admin_url('admin-ajax.php'),
   				'alm_nonce' => wp_create_nonce( "ajax_load_more_nonce" ),
   				'pluginurl' => ALM_URL,
   				'scrolltop' => $scrolltop,
   			)
   		);
   		
   	}
   	
   
   
   	/*
   	*  alm_shortcode
   	*  The AjaxLoadMore shortcode
   	*
   	*  @since 2.0.0
   	*/
   
   	function alm_shortcode( $atts, $content = null ) {
   		$options = get_option( 'alm_settings' ); //Get plugin options
   		
   		extract(shortcode_atts(array(
				'cache' => 'false',		
				'cache_id' => '',	
				'paging' => 'false',
				'paging_controls' => 'false',
				'paging_show_at_most' => '7',
				'paging_classes' => '',
				'preloaded' => 'false',
				'preloaded_amount' => '5',
				'seo' => 'false',
				'repeater' => 'default',
				'theme_repeater' => 'null',
				'post_type' => 'post',
				'post_format' => '',
				'category' => '',	
				'category__not_in' => '',	
				'tag' => '',
				'tag__not_in' => '',
				'taxonomy' => '',
				'taxonomy_terms' => '',
				'taxonomy_operator' => '',	
				'meta_key' => '',
				'meta_value' => '',
				'meta_compare' => '',
				'meta_relation' => '',
				'year' => '',
				'month' => '',
				'day' => '',
				'author' => '',
				'search' => '',					
				'custom_args' => '',				
				'post_status' => '',					
				'order' => 'DESC',
				'orderby' => 'date',
				'post__in' => '',
				'exclude' => '',
				'offset' => '0',
				'posts_per_page' => '5',
				'scroll' => 'true',
				'scroll_distance' => '150',
				'max_pages' => '5',
				'pause' => 'false',
				'destroy_after' => '',
				'transition' => 'slide',
				'images_loaded' => 'false',
				'button_label' => __('Older Posts', ALM_NAME),	
				'css_classes' => '',		
			), $atts));
            
         // Get container elements (ul | div)
   		$container_element = 'ul';
   		if($options['_alm_container_type'] == '2')
   			$container_element = 'div';
   		
   		// Get extra classnames
   		$classname = '';
   		if(isset($options['_alm_classname']))
   			$classname = ' '.$options['_alm_classname'];
   		
   		// Get button color
   		$btn_color = '';
   		if(isset($options['_alm_btn_color']))
   			$btn_color = ' '.$options['_alm_btn_color'];
   		
   		// Get button color
   		$paging_color = '';
   		if(isset($options['_alm_paging_color']))
   			$paging_color = ' paging-'.$options['_alm_paging_color'];
   		
   		// Get btn classnames
   		$button_classname = '';
   		if(isset($options['_alm_btn_classname']))
   			$button_classname = $options['_alm_btn_classname'];
   		
   		
   		// Language support   		
   		$lang = defined('ICL_LANGUAGE_CODE') ? ICL_LANGUAGE_CODE : ''; // WPML - http://wpml.org   		
   		if (function_exists('pll_current_language')) // Polylang - https://wordpress.org/plugins/polylang/
   		   $lang = pll_current_language();   		   
         if (function_exists('qtrans_getLanguage')) // qTranslate - https://wordpress.org/plugins/qtranslate/
   		   $lang = qtrans_getLanguage();  
               
         
   		$wp_posts_per_page = get_option( 'posts_per_page' ); // Posts per page	- settings -> reading
   		
   		/* If $wp_posts_per_page > than shortcode value then $posts_per_page to $wp_posts_per_page */
   		if(has_action('alm_seo_installed') && $wp_posts_per_page > $posts_per_page && $seo === 'true')
      		$posts_per_page = $wp_posts_per_page;  
      	
      	$paging_container_class = '';
      	if($paging === 'true'){
         	$paging_container_class = ' alm-paging-wrap';
      	   $preloaded = 'false'; // temporarily set preloaded to false if paging is true
         }	      				   	
   		
   		// Start ALM object
   		$ajaxloadmore = '<div id="ajax-load-more" class="ajax-load-more-wrap '. $btn_color .''. $paging_color .'">';
   		
   		// Preload Posts
   		if(has_action('alm_preload_installed') && $preloaded === 'true'){   
   		   
   		   // If SEO then set $preloaded_amount to $posts_per_page
   		   if(has_action('alm_seo_installed') && $seo === 'true'){
   		      $preloaded_amount = $posts_per_page; 
            }
            
      		$preloaded_arr = array( // Create preload data array
         		'post_type'          => $post_type,
         		'post_format'        => $post_format,
         		'category'           => $category,
         		'category__not_in'   => $category__not_in,
         		'tag'                => $tag,
         		'tag__not_in'        => $tag__not_in,
         		'taxonomy'           => $taxonomy,
         		'taxonomy_terms'     => $taxonomy_terms,
         		'taxonomy_operator'  => $taxonomy_operator,
         		'meta_key'           => $meta_key,
         		'meta_value'         => $meta_value,
         		'meta_compare'       => $meta_compare,
               'meta_relation'      => $meta_relation,
         		'year'               => $year,
         		'month'              => $month,
         		'day'                => $day,
         		'author'             => $author,
         		'post__in'           => $post__in,
         		'search'             => $search,			
               'custom_args'        => $custom_args,
         		'post_status'        => $post_status,
         		'order'              => $order,
         		'orderby'            => $orderby,
         		'exclude'            => $exclude,
         		'offset'             => $offset,      		
         		'posts_per_page'     => $preloaded_amount,  
         		'lang'               => $lang,  
               'css_classes'        => $css_classes,		  		
            );   
                    		
      		$preloaded_type = preg_split('/(?=\d)/', $repeater, 2); // split $repeater at number to retrieve type
      		$preloaded_type = $preloaded_type[0]; // (default | repeater | template_)     		
      		
            // Create $args array and store it in $preloaded_arg_array
            $args = apply_filters('alm_preload_args', $preloaded_arr);
            
   			$alm_preload_query = new WP_Query($args);
   			$alm_total_posts = $alm_preload_query->found_posts - $offset;
            $output = '';
   			if ($alm_preload_query->have_posts()) :
   				$alm_loop_count = 0; // Count var
   				$alm_page = 0; // Set page to 0
   				$alm_found_posts = $alm_total_posts;
   			   while ($alm_preload_query->have_posts()) : $alm_preload_query->the_post();
   			   	$alm_loop_count++;
	   	         $alm_item = $alm_loop_count; // Get current item in loop  
	   	         if($theme_repeater != 'null' && has_filter('alm_get_theme_repeater')){
   	   	         $preloaded_type = null;
      			   	$output .= apply_filters('alm_preload_inc', $repeater, $preloaded_type, $theme_repeater, $alm_found_posts, $alm_page, $alm_item);
      			   }else{
      			   	$output .= apply_filters('alm_preload_inc', $repeater, $preloaded_type, $theme_repeater, $alm_found_posts, $alm_page, $alm_item);
      			   }
               endwhile;
               wp_reset_query();
   			endif;
   			$preloaded_output = '<'.$container_element.' class="alm-listing alm-preloaded'. $classname .' '. $css_classes .'" data-total-posts="'. $alm_total_posts .'">';
   			$preloaded_output .= $output;
   			$preloaded_output .= '</'.$container_element.'>';   			
   			
   			$ajaxloadmore .= $preloaded_output; // Add $preloadeded data to $ajaxloadmore
         }
         // End Preload Posts
            		
   		
   		$ajaxloadmore .= '<'.$container_element.' class="alm-listing alm-ajax'. $paging_container_class .' '. $classname . ' '. $css_classes .'"'; // Build ALM container 
   		
   		//Cache Add-on   		
   		if(has_action('alm_cache_installed') && $cache === 'true'){   		   
   		   $cache_return = apply_filters('alm_cache_shortcode', $cache, $cache_id, $options);   		   	
   			$ajaxloadmore .= $cache_return;		
         }
   		
   		// Paging Add-on
         if(has_action('alm_paging_installed') && $paging === 'true'){   		   
   		   $paging_return = apply_filters('alm_paging_shortcode', $paging, $paging_controls, $paging_show_at_most, $paging_classes, $options);   		   	
   			$ajaxloadmore .= $paging_return;		
         } 
   		
   		// Preloaded Add-on
         if(has_action('alm_preload_installed') && $preloaded === 'true'){
   		   $ajaxloadmore .= ' data-preloaded="'.$preloaded.'"';	
            $ajaxloadmore .= ' data-preloaded-amount="'.$preloaded_amount.'"';
   		}   
   		   			
   		// SEO Add-on
   		if(has_action('alm_seo_installed') && $seo === 'true'){   		   
   		   $seo_return = apply_filters('alm_seo_shortcode', $seo, $preloaded, $options);   		   	
   			$ajaxloadmore .= $seo_return;		
         } 
   		
   		$ajaxloadmore .= ' data-repeater="'.$repeater.'"';
   		if($theme_repeater != 'null') 
   			$ajaxloadmore .= ' data-theme-repeater="'.$theme_repeater.'"';
   		$ajaxloadmore .= ' data-post-type="'.$post_type.'"';
   		$ajaxloadmore .= ' data-post-format="'.$post_format.'"';
   		$ajaxloadmore .= ' data-category="'.$category.'"';
   		$ajaxloadmore .= ' data-category-not-in="'.$category__not_in.'"';
   		$ajaxloadmore .= ' data-tag="'.$tag.'"';
   		$ajaxloadmore .= ' data-tag-not-in="'.$tag__not_in.'"';
   		$ajaxloadmore .= ' data-taxonomy="'.$taxonomy.'"';
   		$ajaxloadmore .= ' data-taxonomy-terms="'.$taxonomy_terms.'"';
   		$ajaxloadmore .= ' data-taxonomy-operator="'.$taxonomy_operator.'"';
   		$ajaxloadmore .= ' data-meta-key="'.$meta_key.'"';
   		$ajaxloadmore .= ' data-meta-value="'.$meta_value.'"';
   		$ajaxloadmore .= ' data-meta-compare="'.$meta_compare.'"';
   		$ajaxloadmore .= ' data-meta-relation="'.$meta_relation.'"';
   		$ajaxloadmore .= ' data-year="'.$year.'"';
   		$ajaxloadmore .= ' data-month="'.$month.'"';
   		$ajaxloadmore .= ' data-day="'.$day.'"';
   		$ajaxloadmore .= ' data-author="'.$author.'"';
   		$ajaxloadmore .= ' data-post-in="'.$post__in.'"';
   		$ajaxloadmore .= ' data-exclude="'.$exclude.'"';
   		$ajaxloadmore .= ' data-search="'.$search.'"';
   		$ajaxloadmore .= ' data-custom-args="'.$custom_args.'"';
   		$ajaxloadmore .= ' data-post-status="'.$post_status.'"';
   		$ajaxloadmore .= ' data-order="'.$order.'"';
   		$ajaxloadmore .= ' data-orderby="'.$orderby.'"';
   		$ajaxloadmore .= ' data-offset="'.$offset.'"';	
   		$ajaxloadmore .= ' data-posts-per-page="'.$posts_per_page.'"';         
   		$ajaxloadmore .= ' data-lang="'.$lang.'"';
   		$ajaxloadmore .= ' data-scroll="'.$scroll.'"';
   		$ajaxloadmore .= ' data-scroll-distance="'.$scroll_distance.'"';
   		$ajaxloadmore .= ' data-max-pages="'.$max_pages.'"';
   		$ajaxloadmore .= ' data-pause="'.$pause.'"';
   		$ajaxloadmore .= ' data-button-label="'.$button_label.'"';
         $ajaxloadmore .= ' data-button-class="'.$button_classname.'"';
   		$ajaxloadmore .= ' data-destroy-after="'.$destroy_after.'"';
   		$ajaxloadmore .= ' data-transition="'.$transition.'"';
   		$ajaxloadmore .= ' data-images-loaded="'.$images_loaded.'"';
   		   		
   		$ajaxloadmore .= '></'.$container_element.'>';
   		
   		$ajaxloadmore .= '</div>';		
   		// End Build ALM container		
   		
   		return $ajaxloadmore;
   	}
   
   
   
   	/*
   	*  alm_query_posts
   	*  Ajax Load More Query
   	*
   	*  @since 2.0.0
   	*/
   
   	function alm_query_posts() {
   		
   		$nonce = $_GET['nonce'];
   		
   		$options = get_option( 'alm_settings' );
   		
   		if(!is_user_logged_in()){ // Skip nonce verification if user is logged in   		   
   		   
   		   $options = get_option( 'alm_settings' );
   		   
   		   // check alm_settings for _alm_nonce_security
   		   if(isset($options['_alm_nonce_security']) & $options['_alm_nonce_security'] == '1'){        		   		   
      		   if (! wp_verify_nonce( $nonce, 'ajax_load_more_nonce' )) // Check our nonce, if they don't match then bounce!
      		      die('Error, could not verify WP nonce.');      		      
            }
         }         
   
   		$queryType = (isset($_GET['query_type'])) ? $_GET['query_type'] : 'standard';	// 'standard' or 'totalposts'; totalposts returns $alm_found_posts
   		
   		$cache_id = (isset($_GET['cache_id'])) ? $_GET['cache_id'] : '';	
   		
   		$repeater = (isset($_GET['repeater'])) ? $_GET['repeater'] : 'default';		
   		$type = preg_split('/(?=\d)/', $repeater, 2); // split $repeater value at number to determine type
   		$type = $type[0]; // default | repeater | template_	
   		
   		$theme_repeater = (isset($_GET['theme_repeater'])) ? $_GET['theme_repeater'] : 'null';	
   		
   		$postType = (isset($_GET['post_type'])) ? $_GET['post_type'] : 'post';
   		$post_format = (isset($_GET['post_format'])) ? $_GET['post_format'] : '';
   		
   		$category = (isset($_GET['category'])) ? $_GET['category'] : '';
   		$category__not_in = (isset($_GET['category__not_in'])) ? $_GET['category__not_in'] : '';
   		$tag = (isset($_GET['tag'])) ? $_GET['tag'] : '';
   		$tag__not_in = (isset($_GET['tag__not_in'])) ? $_GET['tag__not_in'] : '';
   		
   		// Taxonomy
   		$taxonomy = (isset($_GET['taxonomy'])) ? $_GET['taxonomy'] : '';
   		$taxonomy_terms = (isset($_GET['taxonomy_terms'])) ? $_GET['taxonomy_terms'] : '';
   		$taxonomy_operator = $_GET['taxonomy_operator'];
   		if(empty($taxonomy_operator)) $taxonomy_operator = 'IN';
   		
   		// Date
   		$year = (isset($_GET['year'])) ? $_GET['year'] : '';
   		$month = (isset($_GET['month'])) ? $_GET['month'] : '';
   		$day = (isset($_GET['day'])) ? $_GET['day'] : '';
   		
   		// Custom Fields
   		$meta_key = (isset($_GET['meta_key'])) ? $_GET['meta_key'] : '';
   		$meta_value = (isset($_GET['meta_value'])) ? $_GET['meta_value'] : '';
   		$meta_compare = $_GET['meta_compare'];
   		if($meta_compare == '') $meta_compare = 'IN'; 
   		$meta_relation = $_GET['meta_relation'];
   		if($meta_relation == '') $meta_relation = 'AND'; 
   		
   		$s = (isset($_GET['search'])) ? $_GET['search'] : '';   		
   		$custom_args = (isset($_GET['custom_args'])) ? $_GET['custom_args'] : '';
   		$author_id = (isset($_GET['author'])) ? $_GET['author'] : '';
   		
   		// Ordering
   		$order = (isset($_GET['order'])) ? $_GET['order'] : 'DESC';
   		$orderby = (isset($_GET['orderby'])) ? $_GET['orderby'] : 'date';
   		
   		// Include, Exclude, Offset, Status
   		$post__in = (isset($_GET['post__in'])) ? $_GET['post__in'] : '';	
   		$exclude = (isset($_GET['exclude'])) ? $_GET['exclude'] : '';		
   		$offset = (isset($_GET['offset'])) ? $_GET['offset'] : 0;
   		$post_status = $_GET['post_status'];
   		if($post_status == '') $post_status = 'publish'; 
   		
   		// Page
   		$numPosts = (isset($_GET['posts_per_page'])) ? $_GET['posts_per_page'] : 5;		
   		$page = (isset($_GET['pageNumber'])) ? $_GET['pageNumber'] : 0;
   		
   		// Preload
   		$preloaded = (isset($_GET['preloaded'])) ? $_GET['preloaded'] : 'false'; 
   		$preloaded_amount = (isset($_GET['preloaded_amount'])) ? $_GET['preloaded_amount'] : '5';  
   		if(has_action('alm_preload_installed') && $preloaded === 'true'){   		
   		   // If preload - offset the ajax posts by posts_per_page + preload_amount val	 
   		   $old_offset = $preloaded_amount;  	
   		   $offset = $offset + $preloaded_amount;	
         }
         
         //SEO
   		$seo_start_page = (isset($_GET['seo_start_page'])) ? $_GET['seo_start_page'] : 1;         
   		
   		// Language (Is this needed?)   			
   		$lang = (isset($_GET['lang'])) ? $_GET['lang'] : '';
   
   		// Set up initial args      
         $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
   		$args = array(
   			'post_type' => $postType,
   			'posts_per_page' => $numPosts,
   			'offset' => $offset + ($numPosts*$page),
   			'order' => $order,
   			'orderby' => $orderby,	
   			'post_status' => $post_status,
   			'ignore_sticky_posts' => false,
   			'paged' => $paged,
   		);
         
   	   // Post Format & taxonomy
   		if(!empty($post_format) || !empty($taxonomy)){	
   		   $args['tax_query'] = array(			
   				'relation' => 'AND',
   		      alm_get_tax_query($post_format, $taxonomy, $taxonomy_terms, $taxonomy_operator)
   		   );
   	   }
         
         // Category
   		if(!empty($category)){
   			$args['category_name'] = $category;
   		}
         
         // Category Not In
   		if(!empty($category__not_in)){
   		   $exclude_cats = explode(",",$category__not_in);
   			$args['category__not_in'] = $exclude_cats;
   		}
         
         // Tag
   		if(!empty($tag)){
   			$args['tag'] = $tag;
   		}
         
         // Tag Not In
   		if(!empty($tag__not_in)){
   		   $exclude_tags = explode(",",$tag__not_in);
   			$args['tag__not_in'] = $exclude_tags;
   		}
   	    
   	   // Date (not using date_query as there was issue with year/month archives)
   		if(!empty($year)){
      		$args['year'] = $year;
   	   } 
   	   if(!empty($month)){
      		$args['monthnum'] = $month;
   	   }  
   	   if(!empty($day)){
      		$args['day'] = $day;
   	   } 
   	    
   	   // Meta Query
   		if(!empty($meta_key) && !empty($meta_value)){
      		
      		// Parse multiple meta query    
            $total = count(explode(":", $meta_key)); // Total meta_query objects
            $meta_keys = explode(":", $meta_key); // convert to array
            $meta_value = explode(":", $meta_value); // convert to array
            $meta_compare = explode(":", $meta_compare); // convert to array
            
            if($total == 1){
      			$args['meta_query'] = array(
      			   alm_get_meta_query($meta_keys[0], $meta_value[0], $meta_compare[0]),
      			);
   			}
   			if($total == 2){
      			$args['meta_query'] = array(
         			'relation' => $meta_relation,
      			   alm_get_meta_query($meta_keys[0], $meta_value[0], $meta_compare[0]),	
      			   alm_get_meta_query($meta_keys[1], $meta_value[1], $meta_compare[1]),		
      			);
   			}
   			if($total == 3){
      			$args['meta_query'] = array(
         			'relation' => $meta_relation,
      			   alm_get_meta_query($meta_keys[0], $meta_value[0], $meta_compare[0]),	
      			   alm_get_meta_query($meta_keys[1], $meta_value[1], $meta_compare[1]),	
      			   alm_get_meta_query($meta_keys[2], $meta_value[2], $meta_compare[2]),		
      			);
   			}
   			if($total == 4){
      			$args['meta_query'] = array(
         			'relation' => $meta_relation,
      			   alm_get_meta_query($meta_keys[0], $meta_value[0], $meta_compare[0]),	
      			   alm_get_meta_query($meta_keys[1], $meta_value[1], $meta_compare[1]),	
      			   alm_get_meta_query($meta_keys[2], $meta_value[2], $meta_compare[2]),	
      			   alm_get_meta_query($meta_keys[3], $meta_value[3], $meta_compare[3]),		
      			);
   			}
   			
   	   }
   	   
         // Meta_key, used for ordering by meta value
         if(!empty($meta_key)){
	         $meta_key_single = explode(":", $meta_key);
            $args['meta_key'] = $meta_key_single[0];
         } 
         
         // Author
   		if(!empty($author_id)){
   			$args['author'] = $author_id;
   		}     
         
   		// Include posts
   		if(!empty($post__in)){
   			$post__in = explode(",",$post__in);
   			$args['post__in'] = $post__in;
   		}  
         
   		// Exclude posts
   		if(!empty($exclude)){
   			$exclude = explode(",",$exclude);
   			$args['post__not_in'] = $exclude;
   		}
         
         // Search Term
   		if(!empty($s)){
   			$args['s'] = $s;
   		}
         
         // Custom Args         
   		if(!empty($custom_args)){
   			$custom_args_array = explode(";",$custom_args); // Split the $custom_args at ','
   			foreach($custom_args_array as $argument){ // Loop each $argument  
      			  
      			$argument = preg_replace('/\s+/', '', $argument); // Remove all whitespace 	      				
   			   $argument = explode(":",$argument);  // Split the $argument at ':' 
   			   $argument_arr = explode(",", $argument[1]);  // explode $argument[1] at ','
   			   if(sizeof($argument_arr) > 1){
   			      $args[$argument[0]] = $argument_arr;
   			   }else{
   			      $args[$argument[0]] = $argument[1];      			   
   			   }
   			   
   			}
   		}  
   		
         // Language
   		if(!empty($lang)){
   			$args['lang'] = $lang;
   		}
   		
   		// Set current page number for determining item number		
   		if($page == 0){
            $alm_page_count = 1;
   		}else{   		
   		   $alm_page_count = $page + 1;
   		}   		
   
   		// WP_Query()
   		$alm_query = new WP_Query( $args );	
   		
   		// If preload, set our loop count and total posts to
         if(has_action('alm_preload_installed') && $preloaded === 'true'){ 
            $alm_total_posts = $alm_query->found_posts - $offset + $preloaded_amount;
            if($old_offset > 0)
               $alm_loop_count = $old_offset;
            else
               $alm_loop_count = $offset;
         }else {
            $alm_total_posts = $alm_query->found_posts - $offset;
            $alm_loop_count = 0;
         }
         
         
         // Create cache directory 
         if(!empty($cache_id) && has_action('alm_cache_create_dir')){            
            $url = $_SERVER['HTTP_REFERER'];
            apply_filters('alm_cache_create_dir', $cache_id, $url);            
            $page_cache = ''; // set our page cache variable
         }
         
         
         if($queryType === 'standard'){
	   		// Run the loop
	   		if ($alm_query->have_posts()) : 
	            $alm_found_posts = $alm_total_posts;    		     		   
	   			while ($alm_query->have_posts()): $alm_query->the_post();	
	   				$alm_loop_count++;         
	   	         $alm_page = $alm_page_count; // Get page number      
	   	         $alm_item = ($alm_page_count * $numPosts) - $numPosts + $alm_loop_count; // Get current item            
	   				
	   				if($theme_repeater != 'null' && has_filter('alm_get_theme_repeater')){
		   				do_action('alm_get_theme_repeater', $theme_repeater);
						}else{
							include( alm_get_current_repeater($repeater, $type) );//Include repeater template
						}
	   				
	   				// If cache is enabled
	   				// Build cache include and store in $page_cache variable   				
	   				if(!empty($cache_id) && has_action('alm_cache_inc')){
	   				   $page_cache .= apply_filters('alm_cache_inc', $repeater, $type, $alm_page, $alm_found_posts, $alm_item);
	      			}
	   					   					
	            endwhile; wp_reset_query();
	         
	         // If cache is enabled and seo_start_page is 1 (meaning, a user has not requested /page/12/)
	         // - Only create cached files if the user visits pages in order 1, 2, 3 etc.
	         
	         if(!empty($cache_id) && has_action('alm_cache_installed') && $seo_start_page == 1){
	            apply_filters('alm_cache_file', $cache_id, $page, $page_cache);
	         }
	         
	   		endif;
   		
   		}elseif($queryType === 'totalposts'){
	   		echo $alm_total_posts;  
	   	}
	   	exit;
   	}
   	  	
   }
   
   
   /*
   *  AjaxLoadMore
   *  The main function responsible for returning the one true AjaxLoadMore Instance to functions everywhere.
   *
   *  @since 2.0.0
   */
   
   function AjaxLoadMore(){
   	global $ajax_load_more;
   
   	if( !isset($ajax_load_more) )
   	{
   		$ajax_load_more = new AjaxLoadMore();
   	}
   
   	return $ajax_load_more;
   }
   
   
   // initialize
   AjaxLoadMore();

endif; // class_exists check
