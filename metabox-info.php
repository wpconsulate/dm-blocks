<?php
/**
 * Plugin that allows developers to add custom HTML / CSS / JS code using shortcodes to the Wordpress widgets
 * @author DM+Team
 * @website http://dmarkweb.com
 * @package DMBlocks
 */

defined( 'ABSPATH' ) or die( 'Time for a U turn!' );

add_action('add_meta_boxes', 'dm_block_prepend_info_metabox', 10, 2);

function dm_block_prepend_info_metabox() {
	add_meta_box('cb-shortcodes', 'Content Block Shortcodes', 'dm_block_draw_shortcode_metabox', 'dm_block', 'info_metabox', 'high');
}

function dm_block_draw_shortcode_metabox(){
	global $post;
	$postID = $post->ID;
	$postName = $post->post_name;
	?>
	<script>
        function selectText( containerid ) {

            var node = document.getElementById( containerid );

            if ( document.selection ) {
                var range = document.body.createTextRange();
                range.moveToElementText( node  );
                range.select();
            } else if ( window.getSelection ) {
                var range = document.createRange();
                range.selectNodeContents( node );
                window.getSelection().removeAllRanges();
                window.getSelection().addRange( range );
            }
        }
	</script>
	<strong>Copy any one of these shortcodes and paste into a post or widget to display the content:</strong><br>
    <i>Recommended to use the first one (ID) which will not change if you change the slug the content block, however you can use the slug (name) if you prefer</i><br>
	<div class="copy_shortcodes">
		<div id="dm_block_selectable" onclick="selectText('dm_block_selectable')">[dm_block id=<?php echo $postID; ?>]</div>
		<div id="dm_block_selectable2" onclick="selectText('dm_block_selectable2')">[dm_block name=<?php echo $postName; ?>]</div>
        <div id="dm_block_selectable3" onclick="selectText('dm_block_selectable3')">[dm_block slug=<?php echo $postName; ?>]</div>
        <br><strong>To use in your theme:</strong>
        <div id="dm_block_selectable4" onclick="selectText('dm_block_selectable4')"><&#63;php if(function_exists('dm_content_block'))echo do_shortcode('[dm_block id=<?php echo $postID; ?>]'); ?></div>
        <div id="dm_block_selectable5" onclick="selectText('dm_block_selectable5')"><&#63;php if(function_exists('dm_content_block'))echo do_shortcode('[dm_block name=<?php echo $postName; ?>]'); ?></div>
        <div id="dm_block_selectable6" onclick="selectText('dm_block_selectable6')"><&#63;php if(function_exists('dm_content_block'))echo do_shortcode('[dm_block slug=<?php echo $postName; ?>]'); ?></div>
	</div>
	<?php
}

function dm_block_info_move_deck() {
	# Get the globals:
	global $post, $wp_meta_boxes;

	# Output the "advanced" meta boxes:
	do_meta_boxes( get_current_screen(), 'info_metabox', $post );

	# Remove the initial "advanced" meta boxes:
	unset($wp_meta_boxes['post']['info_metabox']);
}

add_action('edit_form_after_title', 'dm_block_info_move_deck');