<?php
/**
 * @package   Date_Time
 * @author    Donna Peplinskie <donnapep@gmail.com>
 * @license   GPL-2.0+
 * @link      http://donnapeplinskie.com
 * @copyright 2014 Donna Peplinskie
 *
 * @wordpress-plugin
 * Plugin Name:       Date and Time Widget
 * Plugin URI:        http://donnapeplinskie.com/wordpress-date-and-time-widget/
 * Description:       Widget that displays the local date and/or time.
 * Version:           1.1.0
 * Author:            Donna Peplinskie
 * Author URI:        http://donnapeplinskie.com
 * Text Domain:       date-time
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * GitHub Plugin URI: https://github.com/donnapep/wordpress-date-and-time-widget
 */

class Date_Time extends WP_Widget {
  /**
   * Unique identifier for your widget.
   *
   *
   * The variable name is used as the text domain when internationalizing strings
   * of text. Its value should match the Text Domain file header in the main
   * widget file.
   *
   * @since    1.1.0
   *
   * @var      string
   */
  protected $widget_slug = 'date-time';

  /*--------------------------------------------------*/
  /* Constructor
  /*--------------------------------------------------*/

  /**
   * Specifies the classname and description, instantiates the widget,
   * loads localization files, and includes necessary stylesheets and JavaScript.
   */
  public function __construct() {
    // Load plugin text domain
    add_action( 'init', array( $this, 'widget_textdomain' ) );

    // Hooks fired when the Widget is activated and deactivated
    register_activation_hook( __FILE__, array( $this, 'activate' ) );
    register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );

    parent::__construct(
      $this->get_widget_slug(),
      __( 'Date and Time', $this->get_widget_slug() ),
      array(
        'classname'  => 'widget_date_time',
        'description' => __( 'Show the local date and/or time.',
          $this->get_widget_slug() )
      )
    );

    // Register admin styles and scripts
    add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
    add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );

    // Register site styles and scripts
    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_styles' ) );
    add_action( 'wp_enqueue_scripts', array( $this, 'register_widget_scripts' ) );

    // Refreshing the widget's cached output with each new post
    add_action( 'save_post',    array( $this, 'flush_widget_cache' ) );
    add_action( 'deleted_post', array( $this, 'flush_widget_cache' ) );
    add_action( 'switch_theme', array( $this, 'flush_widget_cache' ) );

  } // end constructor


  /**
   * Return the widget slug.
   *
   * @since    1.0.0
   *
   * @return    Plugin slug variable.
   */
  public function get_widget_slug() {
    return $this->widget_slug;
  }

  /*--------------------------------------------------*/
  /* Widget API Functions
  /*--------------------------------------------------*/

  /**
   * Outputs the content of the widget.
   *
   * @param array args  The array of form elements
   * @param array instance The current instance of the widget
   */
  public function widget( $args, $instance ) {
    // Check if there is a cached output
    $cache = wp_cache_get( $this->get_widget_slug(), 'widget' );

    if ( !is_array( $cache ) ) {
      $cache = array();
    }

    if ( !isset( $args['widget_id'] ) ) {
      $args['widget_id'] = $this->id;
    }

    if ( isset( $cache[ $args['widget_id'] ] ) ) {
      return print $cache[ $args['widget_id'] ];
    }

    //Widget settings
    $time_format = $instance['time_format'];
    $date_format = $instance['date_format'];
    $font_family = $instance['font_family'];
    $font_size = $instance['font_size'];
    $text_color = $instance ['text_color'];
    $background_color = $instance ['background_color'];

    extract( $args, EXTR_SKIP );

    $widget_string = $before_widget;

    ob_start();
    include( plugin_dir_path( __FILE__ ) . 'views/widget.php' );
    $widget_string .= ob_get_clean();
    $widget_string .= $after_widget;

    $cache[ $args['widget_id'] ] = $widget_string;

    wp_cache_set( $this->get_widget_slug(), $cache, 'widget' );

    print $widget_string;

  } // end widget


  public function flush_widget_cache() {
    wp_cache_delete( $this->get_widget_slug(), 'widget' );
  }
  /**
   * Processes the widget's options to be saved.
   *
   * @param   array   new_instance    The new instance of values to be
   *                                  generated via the update.
   * @param   array   old_instance    The previous instance of values before
   *                                  the update.
   */
  public function update( $new_instance, $old_instance ) {

    $instance = $old_instance;

    $instance['time_format'] = $new_instance['time_format'];
    $instance['date_format'] = $new_instance['date_format'];
    $instance['font_family'] = $new_instance['font_family'];
    $instance['font_size'] = $new_instance['font_size'];
    $instance['text_color'] = $new_instance['text_color'];
    $instance['background_color'] = $new_instance['background_color'];

    return $instance;

  } // end widget

  /**
   * Generates the administration form for the widget.
   *
   * @param   array   instance        The array of keys and values for the
   *                                  widget.
   */
  public function form( $instance ) {
    // Define default values for variables.
    $instance = wp_parse_args(
      (array) $instance,
      array(
        'time_format' => '12-hour-seconds',
        'date_format' => 'long',
        'font_family' => 'Arial, Arial, Helvetica, sans-serif',
        'font_size' => '20px',
        'text_color' => '#000',
        'background_color' => 'transparent'
      )
    );

    // Store the values of the widget in their own variables.
    $text_color = esc_attr( $instance['text_color'] );
    $background_color = esc_attr( $instance['background_color'] );

    // Display the admin form
    include( plugin_dir_path(__FILE__) . 'views/admin.php' );
  } // end form

  /*--------------------------------------------------*/
  /* Public Functions
  /*--------------------------------------------------*/

  /**
   * Loads the Widget's text domain for localization and translation.
   */
  public function widget_textdomain() {
    load_plugin_textdomain( $this->get_widget_slug(), false,
      plugin_dir_path( __FILE__ ) . 'lang/' );
  } // end widget_textdomain

  /**
   * Fired when the plugin is activated.
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Activate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       activated on an individual blog.
   */
  public function activate( $network_wide ) {
    // TODO define activation functionality here
  } // end activate

 /**
   * Fired when the plugin is deactivated.
   *
   * @since    2.0.0
   *
   * @param    boolean    $network_wide    True if WPMU superadmin uses
   *                                       "Network Deactivate" action, false if
   *                                       WPMU is disabled or plugin is
   *                                       deactivated on an individual blog.
   */
  public function deactivate( $network_wide ) {
    // TODO define deactivation functionality here
  } // end deactivate

  /**
   * Registers and enqueues admin-specific styles.
   */
  public function register_admin_styles() {
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_style( $this->get_widget_slug().'-admin-styles', plugins_url(
      'css/admin.css', __FILE__ ) );
  } // end register_admin_styles

  /**
   * Registers and enqueues admin-specific JavaScript.
   */
  public function register_admin_scripts() {
    wp_enqueue_script( $this->get_widget_slug().'-admin-script', plugins_url(
      'js/admin.js', __FILE__ ), array('jquery', 'wp-color-picker') );
  } // end register_admin_scripts

  /**
   * Registers and enqueues widget-specific styles.
   */
  public function register_widget_styles() {
    wp_enqueue_style( $this->get_widget_slug().'-widget-styles', plugins_url(
      'css/widget.css', __FILE__ ) );
  } // end register_widget_styles

  /**
   * Registers and enqueues widget-specific scripts.
   */
  public function register_widget_scripts() {
    wp_enqueue_script( $this->get_widget_slug().'-script', plugins_url(
      'js/widget.js', __FILE__ ), array('jquery') );
  } // end register_widget_scripts

  /**
   * Render options in the Time Format dropdown.
   *
   * @since     1.1.0
   */
  public function render_time_format( $instance ) {
    $formats = array(
      "none" => "None",
      "12-hour" => date("g:i A", current_time( 'timestamp', 0 ) ),
      "12-hour-seconds" => date("g:i:s A", current_time( 'timestamp', 0 ) ),
      "24-hour" => date("G:i", current_time( 'timestamp', 0 ) ),
      "24-hour-seconds" => date("G:i:s", current_time( 'timestamp', 0 ) ),
    );

    foreach( $formats as $key => $value ) {
      $selected = ( $instance['time_format'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }

  /**
   * Render options in the Date Format dropdown.
   *
   * @since     1.1.0
   */
  public function render_date_format( $instance ) {
    $formats = array(
      "none" => "None",
      "short" => date( "n/j/Y", current_time( 'timestamp', 0 ) ),
      "european" => date( "j/n/Y", current_time( 'timestamp', 0 ) ),
      "medium" => date( "M j Y", current_time( 'timestamp', 0 ) ),
      "long" => date( "F j, Y", current_time( 'timestamp', 0 ) ),
    );

    foreach( $formats as $key => $value ) {
      $selected = ( $instance['date_format'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }

  /**
   * Render options in the Font Family dropdown.
   *
   * @since     1.1.0
   */
  public function render_font_family( $instance ) {
    $font_families = array(
      "Arial, Arial, Helvetica, sans-serif" => "Arial",
      "Comic Sans MS, Comic Sans MS, cursive" => "Comic Sans MS",
      "Courier New, Courier New, Courier, monospace" => "Courier New",
      "Georgia, Georgia, serif" => "Georgia",
      "Lucida Sans Unicode, Lucida Grande, sans-serif" => "Lucida Sans Unicode",
      "Tahoma, Geneva, sans-serif" => "Tahoma",
      "Times New Roman, Times, serif" => "Times New Roman",
      "Trebuchet MS, Helvetica, sans-serif" => "Trebuchet MS",
      "Verdana, Verdana, Geneva, sans-serif" => "Verdana",
    );

    foreach( $font_families as $key => $value ) {
      $selected = ( $instance['font_family'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }

  /**
   * Render options in the Font Size dropdown.
   *
   * @since     1.1.0
   */
  public function render_font_size( $instance ) {
    $font_sizes = array(
      "8px" => "8",
      "9px" => "9",
      "10px" => "10",
      "11px" => "11",
      "12px" => "12",
      "14px" => "14",
      "16px" => "16",
      "18px" => "18",
      "20px" => "20",
      "22px" => "22",
      "24px" => "24",
      "26px" => "26",
      "28px" => "28",
      "36px" => "36",
      "48px" => "48",
      "72px" => "72",
    );

    foreach( $font_sizes as $key => $value ) {
      $selected = ( $instance['font_size'] == $key ) ?
        'selected="selected"' : '';
      echo '<option value="' . $key . '" ' . $selected . '>' . $value .
        '</option>';
    }
  }
} // end class

add_action( 'widgets_init', create_function( '',
  'register_widget("Date_Time");' ) );
