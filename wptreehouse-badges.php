<?php
/*
   Plugin Name: WP Team Tree House Plugin
   Plugin URI: http://www.awesome-plugin-of-the-death.com
   Description: Provides widgets and shortcodes to help display of treehouse profile page
   Version: 1.0
   Author: Sylvain Noelie
   Author URI: http://misstotallyawesome.com
   License: GPL2
*/

/*
 * Assign global variables
 */
$plugin_url = WP_PLUGIN_URL . '/wptreehouse-badges'; // CREATE GLOBAL TO GET PROPER URL OF PLUGIN TO LINK IMG
$options = array();
$display_json = false;

/*
 * Add a link to our plugin in the admin menu
 * under "Settings > TreeHouse Badges"
 */
function wptreehouse_badges_menu() {

	/*
	 * Use the add_options_page function
	 * add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function )
	 */
	add_options_page('TreeHouse Badges Plugin', 'treehouse Badges', 'manage_options', 'wptreehouse_badges', 'wptreehouse_badges_options_page');
}

/*
 * Now we tie this function wptreehouse_badges_menu() to a proper hook so that our information show up in the admin menu
 */
add_action( 'admin_menu', 'wptreehouse_badges_menu' );

/*
 * This is the option page
 */
function wptreehouse_badges_options_page() {

	if ( !current_user_can( 'manage_options' )) {
		wp_die( 'You do not have sufficent permission to access this page' );
	}


	global $plugin_url; /* MAKES THE GLOBAL VARIABLE ACCESSIBLE IN inc/options-page-wrapper.php */
	global $options;
	global $display_json;

	if ( isset( $_POST['wptreehouse_form_submitted']) ) {
		$hidden_field = esc_html($_POST['wptreehouse_form_submitted']); /* wrap for security */

		if ( $hidden_field == 'Y') {
			$wptreehouse_username = esc_html($_POST['wptreehouse_username']);

			$wptreehouse_profile = wptreehouse_badges_get_profile( $wptreehouse_username ); // <------ for JSON

			$options['wptreehouse_username'] = $wptreehouse_username;
			$options['wptreehouse_profile'] = $wptreehouse_profile;
			$options['last_updated'] = time();

			update_option( 'wptreehouse_badges', $options ); // update DB
		}
	}

	$options = get_option( 'wptreehouse_badges' );
	if ($options != '') {
		$wptreehouse_username = $options['wptreehouse_username'];
		$wptreehouse_profile = $options['wptreehouse_profile'];
	}

	require ( 'inc/options-page-wrapper.php' );
}


/* CREATE A NEW CLASS FOR OUR WIDGET */
class Wptreehouse_Badges_Widget extends WP_Widget {

	function __construct() {
		// Instantiate the parent object
		parent::__construct( false, 'Official Treehouse Badges Widget' );
	}

	function widget( $args, $instance ) {
		// Widget output. Display content on front end

		extract( $args ); // extract the arguments for it to work
		$title = apply_filters( 'widget_title', $instance['title'] );
		// variable title that will show up on the page. We let the user the ability to modify it by using the widget_title hook


		$num_badges = $instance['num_badges'];
		$display_tooltip = $instance['display_tooltip'];


		$options = get_option( 'wptreehouse_badges' ); // get the options of the databases
		$wptreehouse_profile = $options['wptreehouse_profile']; // get OUR plugin database

		require ('inc/front-end.php');
	}

	function update( $new_instance, $old_instance ) {
		// Save widget options. Update content the user save for the option
		// setting the new instance to replace the old instance

		$instance = $old_instance;
		$instance['title'] = strip_tags($new_instance['title']);
		$instance['num_badges'] = strip_tags($new_instance['num_badges']);
		$instance['display_tooltip'] = strip_tags($new_instance['display_tooltip']);

		return $instance;
	}

	function form( $instance ) {
		// Output admin widget options form. How the widget looks like on the admin side
		$title = esc_attr($instance['title']);
		$num_badges = esc_attr($instance['num_badges']);
		$display_tooltip = esc_attr($instance['display_tooltip']);

		$options = get_option( 'wptreehouse_badges' ); // reget the options of the databases
		$wptreehouse_profile = $options['wptreehouse_profile']; // reget OUR plugin database

		require ('inc/widget-fields.php' );
	}
}

function wptreehouse_register_widgets() { // register the widget
	register_widget( 'Wptreehouse_Badges_Widget' );
}

add_action( 'widgets_init', 'wptreehouse_register_widgets' ); // hook it up !


function wptreehouse_badges_shortcode($atts, $content = null) {
	global $post; /* get the global post info in with the shortcode appears */
	extract(shortcode_atts(array( /*the array of option and their default args(8 and on)*/
		'num_badges' => '8',
		'tooltip' => 'on'
		), $atts) );
	if ( $tooltip == 'on') $tooltip = 1;
	if ( $tooltip == 'off') $tooltip = 0;

	$display_tooltip = $tooltip; /*display_tooltip = variable of our frontend code */

	/*pull in our profile*/
	$options = get_option( 'wptreehouse_badges' ); // reget the options of the databases
	$wptreehouse_profile = $options['wptreehouse_profile']; // reget OUR plugin database

	/* Buffering: a bit ox extra code.
	** when we use short code and a require() statement, it will output the required statement
	** at the very top of the post. We want it to be output where the shortcode is used.
	** to do this we use ob_start
	** ob_start can be used to capture output and convert it to a string as follows
	*/
	ob_start();
	require ('inc/front-end.php');
	$content = ob_get_clean();
	return $content;
}
add_shortcode('wptreehouse_badges', 'wptreehouse_badges_shortcode'); /* first arg = what the user will type to show the short code */


function wptreehouse_badges_get_profile( $wptreehouse_username ) {

	$json_feed_url = 'http://teamtreehouse.com/' . $wptreehouse_username . '.json';
	$args = array( 'timeout' => 120 );
	$json_feed = wp_remote_get($json_feed_url, $args);


	$wptreehouse_profile = json_decode( $json_feed['body']); // majes it stored in an object instead of a string <3


	return $wptreehouse_profile;
}

function wptreehouse_badges_refresh_profile() {

	$options = get_option('wptreehouse_badges'); //get the option to get the last update time
	$last_updated = $options['last_updated'];

	$current_time = time();

	$update_difference = $current_time - $last_updated;
	if ($update_difference > 86400 ) { // one day = 86400 seconds

		$wptreehouse_username = $options['wptreehouse_username'];
		$options['profile'] = wptreehouse_badges_get_profile($wptreehouse_username);
		$options['last_updated'] = time();

		update_option( 'wptreehouse_badges', $options ); // update db
	}

	die(); /* To tell Ajax that the function is completed */

}
add_action('wp_ajax_wptreehouse_badges_refresh_profile', 'wptreehouse_badges_refresh_profile'); /* unique tag -> custom hook for ajax to make the function accessible on the front end*/

function wptreehouse_badges_enable_frontend_ajax() {
?>
	<script>
		var ajaxurl = '<?php echo admin_url('admin-ajax.php'); ?>';
		/*
		echo the admin php. It is instruction wordpress that when a ajax command is submitted:
		pass it throuth the admin-ajax page and work it's way down to find our wptreehouse_badges_refresh_profile function
		*/
	</script>

<?php
}
add_action('wp_head', 'wptreehouse_badges_enable_frontend_ajax'); /*to put the js in the header*/

function wptreehouse_badges_backend_styles() {
	// first arg: our function. second: the stylesheet
	wp_enqueue_style( 'wptreehouse_badges_backend_css', plugins_url( 'wptreehouse-badges/wptreehouse-badges.css' ) );
}
add_action( 'admin_head', 'wptreehouse_badges_backend_styles' );

function wptreehouse_badges_frontend_scripts_and_styles() {
	// first arg: our function. second: the stylesheet
	wp_enqueue_style( 'wptreehouse_badges_frontend_css', plugins_url( 'wptreehouse-badges/wptreehouse-badges.css' ) );
	wp_enqueue_script( 'wptreehouse_badges_fro ntend_js', plugins_url( 'wptreehouse-badges/wptreehouse-badges.js' ), array('jquery'), '', true /* in footer and not header */ );
}
add_action( 'wp_enqueue_scripts', 'wptreehouse_badges_frontend_scripts_and_styles' );
?>