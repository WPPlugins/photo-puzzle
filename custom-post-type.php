<?php


class PhotoPuzzleCustomPostType{

private $post_type = 'photopuzzle';
private $post_label = 'Photo Puzzle';
private $prefix = '_photo_puzzle_';
function __construct() {
	
	add_filter( 'template_include', array($this,"include_puzzle_template"), 1 );
	add_action("init", array(&$this,"create_post_type"));
	add_action( 'init', array(&$this, 'photo_puzzle_register_shortcodes'));
	add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue_styles'));
	add_action( 'wp_enqueue_scripts', array(&$this, 'enqueue_scripts'));
	
	add_action( 'cmb2_init', array(&$this,'photopuzzle_register_metabox' ));	
	register_activation_hook( __FILE__, array(&$this,'activate' ));
}

function create_post_type(){
	register_post_type($this->post_type, array(
	         'label' => _x($this->post_label, $this->post_type.' label'), 
	         'singular_label' => _x('All '.$this->post_label, $this->post_type.' singular label'), 
	         'public' => true, // These will be public
	         'show_ui' => true, // Show the UI in admin panel
	         '_builtin' => false, // This is a custom post type, not a built in post type
	         '_edit_link' => 'post.php?post=%d',
	         'capability_type' => 'page',
	         'hierarchical' => false,
	         'rewrite' => array("slug" => $this->post_type), // This is for the permalinks
	         'query_var' => $this->post_type, // This goes to the WP_Query schema
	         //'supports' =>array('title', 'editor', 'custom-fields', 'revisions', 'excerpt'),
	         'supports' =>array('title', 'author'),
	         'add_new' => _x('Add New', 'Event')
	         ));
}

/**************************************************
**********************CMB2*************************
*/


/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_init' hook.
 */

function photopuzzle_register_metabox() {

	/**
	 * Sample metabox to demonstrate each field type included
	 */
	$cmb_demo = new_cmb2_box( array(
		'id'            => $this->prefix . 'metabox',
		'title'         => __( 'Photo Puzzle', 'cmb2' ),
		'object_types'  => array( $this->post_type, ), // Post type
		'context'       => 'normal',
		'priority'      => 'high',
		'show_names'    => true, // Show field names on the left
	) );


	
	$cmb_demo->add_field( array(
		'name' => __( 'Puzzle Url', 'cmb2' ),
		//'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $this->prefix . 'url',
		'type' => 'file',
	) );

	$cmb_demo->add_field( array(
		'name' => __( 'Description', 'cmb2' ),
		//'desc' => __( 'field description (optional)', 'cmb2' ),
		'id'   => $this->prefix . 'description',
		'type'    => 'wysiwyg',
		'options' => array( 'textarea_rows' => 5, ),
	) );


}





/************************************************
*******************End CMB2**********************
*/


function include_puzzle_template( $template_path ) {
	

        if ( is_singular($this->post_type) ) {
        		
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'photoPuzzleFullTemplate.php' ) ) ) {

                $template_path = $theme_file;
            } else {

                $template_path = plugin_dir_path( __FILE__ ) . '/template/photoPuzzleFullTemplate.php';

            }
        }
    return $template_path;

}





function photo_puzzle_shortcode($atts){
		extract( shortcode_atts( array(
			'url'=> '',
			'id' => '',
		), $atts ) );
		$dir = plugin_dir_path( __FILE__ );
		$puzzle_url = $url;
		ob_start();
		include $dir.'template/photoPuzzleTemplate.php';
		return ob_get_clean();
}



function photo_puzzle_register_shortcodes(){
		add_shortcode( 'photo_puzzle', array(&$this,'photo_puzzle_shortcode' ));
	}


function activate() {
	// register taxonomies/post types here
	$this->create_post_type();
	//global $wp_rewrite;
	//$wp_rewrite->flush_rules();
	flush_rewrite_rules(false);
}

function enqueue_styles(){
	wp_register_style( 'photo-puzzle-css', plugin_dir_url(__FILE__).'css/photoPuzzle.css' );
	wp_enqueue_style('photo-puzzle-css');
	wp_register_style( 'jqpuzzle-css', plugin_dir_url(__FILE__).'jqPuzzle/jquery.jqpuzzle.css' );
	wp_enqueue_style('jqpuzzle-css');
}

function enqueue_scripts(){
	wp_enqueue_script('photo-puzzle-js', plugin_dir_url(__FILE__).'js/photoPuzzle.js');
	wp_enqueue_script('jqpuzzle-js', plugin_dir_url(__FILE__).'jqPuzzle/jquery.jqpuzzle.full.js');
}



}// end PhotoPuzzleCustomPostType class

new PhotoPuzzleCustomPostType();


?>