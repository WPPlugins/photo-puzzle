<?php
/**
 * Template Name: recipePagesTemplate
 */

/** Exit if accessed directly */
if ( ! defined( 'ABSPATH' ) ) exit( 'Cheatin&#8217; uh?' );


  
get_header(); 

//$puzzle_post_type = new PhotoPuzzleCustomPostType();

//$page_title  = get_post_meta( get_the_ID(), '_jqpuzzle_title', true );

$puzzle_url  = get_post_meta( get_the_ID(), '_photo_puzzle_url', true );

$page_description  = get_post_meta( get_the_ID(), '_photo_puzzle_description', true );



?>


<div id="primary" class="content-area recipe-content-area wrap">


<h1 class="page-title"><?php //echo $page_title; 
?></h1>


    <main id="main" class="site-main" role="main">

		<div><?php include plugin_dir_path( __FILE__ ).'photoPuzzleTemplate.php'; ?>
		</div>
		<div class="page-description"><?php echo wpautop($page_description); ?></div>


		</div>

    </main><!-- .site-main -->

    <?php get_sidebar( ); ?>

</div><!-- .content-area -->

<?php get_footer(); ?>