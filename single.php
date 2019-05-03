<?php
/**
 * Plugin that allows developers to add custom HTML / CSS / JS code using shortcodes to the Wordpress widgets
 * @author DM+Team
 * @website http://dmarkweb.com
 * @package DMBlocks
 */

// TODO - add option to disable header and footer for themes where the preview does not work well
get_header(); ?>

<style>
    .dm_block_content-area{
        max-width: 1200px;
        margin: 10px auto 20px;
        display: table;
    }
</style>

	<div class="dm_block_wrap">
		<div class="dm_block_content-area">
			<main class="dm_block_site-main">

				<?php
				while ( have_posts() ) : the_post();

					if(function_exists('dm_content_block')) echo do_shortcode('[dm_block id='. get_the_ID() .']');
				endwhile; // End of the loop.
				?>

			</main><!-- #main -->
		</div><!-- #primary -->
	</div><!-- .wrap -->

<?php get_footer();