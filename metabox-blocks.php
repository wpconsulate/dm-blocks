<?php
/**
 * Plugin that allows developers to add custom HTML / CSS / JS code using shortcodes to the Wordpress widgets
 * @author DM+Team
 * @website http://dmarkweb.com
 * @package DMBlocks
 */

defined( 'ABSPATH' ) or die( 'Time for a U turn!' );

add_action('add_meta_boxes', 'dm_block_prepend_page_metaboxes', 10, 2);
add_action('save_post', 'dm_block_prepend_save_display_metabox');

function dm_block_prepend_page_metaboxes() {
	add_meta_box('content-code', 'Content Block Code', 'dm_block_prepend_draw_display_metabox', 'dm_block', 'content-code', 'high');
}

function dm_block_prepend_draw_display_metabox($post) {
	global $post;
	if ( current_user_can( 'unfiltered_html' ) ) {
		$data        = get_post_custom( $post->ID );
		$dm_block_html = isset( $data['dm_block_html'] ) ? esc_html($data['dm_block_html'][0]) : '';
		$dm_block_css  = isset( $data['dm_block_css'] ) ? esc_html( $data['dm_block_css'][0] ) : '';
		$dm_block_js   = isset( $data['dm_block_js'] ) ? esc_html( $data['dm_block_js'][0] ) : '';

		wp_nonce_field( 'dm_block_prepend_display_metabox_nonce', 'dm_block_display_metabox_nonce' );
		?>

        <style>
            .dm_block_editor_container {
                width: 100%;
                height: 250px;
                position: relative;
                max-width: 100%;
                min-width: 100%;
            }
            .dm_blocks_ace_editor {
                position: absolute;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                outline: 1px solid #d0d0d0;
            }
            .dm_block_mask.scrolling {
                position: absolute;
                top: 0;
                bottom: 0;
                left: 0;
                right: 0;
                z-index: 999;
            }
        </style>
        <div class="dm_block_mask"></div>
        <p>
            <label for="dm_blocks_html_editor" style="font-weight: bold;"><?php esc_attr_e( 'HTML Code', 'dev_cb' ); ?></label>
        </p>
        <p>Please Note. This plugin lets you use raw HTML, JS, and CSS therefore be careful if copying and pasting from random web pages as in order to allow you maximum control with this plugin, you will be able to paste JS that is not entirely validated.</p>
        <div class="dm_block_editor_container dm_blocks_html_editor_container">
            <div id="dm_blocks_html_editor" class="dm_blocks_ace_editor"></div>
        </div>
		<?php
		?>
        <textarea title="HTML Code" style="display:none;" name="dm_block_html" id="dm_block_html"><?php echo $dm_block_html ?></textarea>

        <p>
            <label for="dm_blocks_css_editor" style="font-weight: bold;"><?php esc_attr_e( 'CSS Code', 'dev_cb' ); ?></label>
        </p>
        <div class="dm_block_editor_container dm_blocks_css_editor_container">
            <div id="dm_blocks_css_editor" class="dm_blocks_ace_editor"></div>
        </div>
        <textarea title="CSS Code" style="display:none;" name="dm_block_css" id="dm_block_css"><?php echo $dm_block_css ?></textarea>

        <p>
            <label for="dm_blocks_js_editor" style="font-weight: bold;"><?php esc_attr_e( 'JS Code', 'dev_cb' ); ?></label>
        </p>
        <div class="dm_block_editor_container dm_blocks_js_editor_container">
            <div id="dm_blocks_js_editor" class="dm_blocks_ace_editor"></div>
        </div>
        <textarea title="JS Code" style="display:none;" name="dm_block_js" id="dm_block_js"><?php echo $dm_block_js; ?></textarea>

		<?php
	}

}

function dm_block_prepend_save_display_metabox($page_id) {
	if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
	if(!isset($_POST['dm_block_display_metabox_nonce']) || !wp_verify_nonce($_POST['dm_block_display_metabox_nonce'], 'dm_block_prepend_display_metabox_nonce' )) return;
	if(!current_user_can('edit_pages', $page_id)) return;

	if(isset($_POST['dm_block_html'])) {
		$dm_block_html = $_POST['dm_block_html'];
		$dm_block_html = trim($dm_block_html);
		$dm_block_html = apply_filters( 'format_to_edit', $dm_block_html );
		$dm_block_html = esc_textarea( $dm_block_html );
		update_post_meta($page_id, 'dm_block_html', $dm_block_html);
	}
	if(isset($_POST['dm_block_css'])) {
		$dm_block_css = $_POST['dm_block_css'];
		$dm_block_css = trim($dm_block_css);
		$dm_block_css = apply_filters( 'format_to_edit', $dm_block_css );
		$dm_block_css = esc_textarea( $dm_block_css );
		update_post_meta($page_id, 'dm_block_css', $dm_block_css);
	}
	if(isset($_POST['dm_block_js'])) {
		$dm_block_js = $_POST['dm_block_js'];
		$dm_block_js = trim($dm_block_js);
		$dm_block_js = apply_filters( 'format_to_edit', $dm_block_js );
		$dm_block_js = esc_textarea( $dm_block_js );
		update_post_meta($page_id, 'dm_block_js', $dm_block_js);
	}
}

function dm_block_move_deck() {
	# Get the globals:
	global $post, $wp_meta_boxes;

	# Output the "advanced" meta boxes:
	do_meta_boxes( get_current_screen(), 'content-code', $post );

	# Remove the initial "advanced" meta boxes:
	unset($wp_meta_boxes['post']['content-code']);
}

add_action('edit_form_after_title', 'dm_block_move_deck');