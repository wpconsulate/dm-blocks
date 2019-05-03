<?php
/**
 * Plugin that allows developers to add custom HTML / CSS / JS code using shortcodes to the Wordpress widgets
 * @author DM+Team
 * @website http://dmarkweb.com
 * @package DMBlocks
 */

defined( 'ABSPATH' ) or die( 'Time for a U turn!' );

function dm_block_meta_options( $post_type ) {
	add_meta_box(
		'content_editor_meta',
		'Content Editor',
		'dm_block_content_editor_meta',
		'dm_block',
		'content_editor_meta', // change to something other then normal, advanced or side
		'high'
	);
}
add_action('add_meta_boxes', 'dm_block_meta_options');

function dm_block_content_editor_meta($post){
	global $post;
	$data = get_post_custom($post->ID);
	$dcb_show_post = isset($data['dm_block_show_post']) ? esc_attr($data['dm_block_show_post'][0]) : '';
	if($dcb_show_post == 'on'){
		$checked = 'checked';
	} else {
		$checked = '';
	}
	wp_nonce_field('dm_block_options_show_post_nonce', 'dm_block_show_post_nonce');

	if($dcb_show_post != 'on'){ ?>
		<style>
			div#postdivrich {
				display: none;
			}
		</style>
	<?php } ?>
	<div id="dm_blocks_enable_content">
		<div class="dm_block_meta_heading_wrapper">
			<div>
				<h4>Enable content Editor</h4>
				<div class="dm_block_tooltip">!
					<span class="dm_block_tooltiptext">
						<p>
							Grabs "the_content()" from this content block and outputs it in the shortcode.<br>
							The ideal and cleanest option is to use the DM Blocks HTML editor box above and forgo the default Wordpress content editor however if you want to use the WP editor instead of or before the HTML block output, enable it here.<br>
							Take into account that Worpress has the habit of breaking HTML markup from the content editor so if you want to use raw HTML reliably, then leave this option disabled and use the HTML box above in the "Content Block Code" section!
						</p>
					</span>
				</div>
			</div>
			<div>
				<h4>Using a Builder?</h4>
				<div class="dm_block_tooltip">!
					<span class="dm_block_tooltiptext">
						<p>
							Page builders are not recommended as they can clash when being used from within a shortcode or on other pages. It is best to use the Wordpress HTML or WYSIWYG editor or even better just use the HTML block provided with this plugin. If you want to try using a page builder, it still may work in some cases but you may lose compatibility in the future. (You have been warned :) )
						</p>
						<p>
							If you use a page builder and do not see the option to use it here, you may need to enable it for this post type: <strong>DM Blocks.</strong> (Depends if your page builder supports custom post types).
						</p>
						<p>
							Take into account that some page builders output the style per page and not inline meaning that styling may not be visible in the shortcode output.<br>
							Tested with Divi Builder it works if you also enable Divi Builder on the destination page itself. <br>
							Tested with Elementor the styling done in Elementor does not come through as it is only generated on the page the element was generated on. (The
                            content block page not the destination page using the shortcode pointing to the content block.)
						</p>
					</span>
				</div>
			</div>
		</div>
		<div class="dm_block_options-note">
			It is recommended to not enable this option and to use the HTML block above however this option is available if you want it.
            <br><i>*Note if you enable this option and add content here as well as in the HTML box, the content here will be above the HTML box output on the site.</i>
		</div><br>
		<input class="dm_block_tgl dm_block_tgl-flat dm_block_show_post" id="dm_block_show_post" name="dm_block_show_post" type="checkbox" <?php echo $checked; ?> />
		<label class="dm_block_tgl-btn" for="cb4"></label>
	</div>
	<?php
}

function dm_block_options_move_deck() {
	# Get the globals:
	global $post, $wp_meta_boxes;

	# Output the "advanced" meta boxes:
	do_meta_boxes( get_current_screen(), 'content_editor_meta', $post );

	# Remove the initial "advanced" meta boxes:
	unset($wp_meta_boxes['post']['content_editor_meta']);
}

add_action('edit_form_after_title', 'dm_block_options_move_deck');

function dm_block_save_meta_options($page_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['dm_block_show_post_nonce']) || !wp_verify_nonce($_POST['dm_block_show_post_nonce'], 'dm_block_options_show_post_nonce' )) return;
	if(!current_user_can('edit_pages', $page_id)) return;

	if(isset($_POST['dm_block_show_post']) ) {
	    if($_POST['dm_block_show_post'] == 'on') {
		    update_post_meta( $page_id, 'dm_block_show_post', 'on' );
	    }
	} else {
		update_post_meta($page_id, 'dm_block_show_post', 'off');
	}
}

add_action('save_post', 'dm_block_save_meta_options');
