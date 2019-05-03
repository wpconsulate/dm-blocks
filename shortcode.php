<?php
/**
 * Plugin that allows developers to add custom HTML / CSS / JS code using shortcodes to the Wordpress widgets
 * @author DM+Team
 * @website http://dmarkweb.com
 * @package DMBlocks
 */

defined( 'ABSPATH' ) or die( 'Time for a U turn!' );

function dm_content_block($args = array()) {

	$defaults = array(
		'id' => '',
		'slug' => '',
		'name' => ''
	);
	$args = wp_parse_args($args, $defaults);

	if ( $args['slug'] != '' && $post = get_page_by_path( $args['slug'], OBJECT, 'dm_block' ) ){
		$args['id'] = $post->ID;
		$cb_content = $post->post_content;
	} elseif ( $args['name'] != '' && $post = get_page_by_path( $args['name'], OBJECT, 'dm_block' ) ){
		$args['id'] = $post->ID;
		$cb_content = $post->post_content;
	} else {
		$cb_content = get_post_field( 'post_content', $args['id'] );
	}

	$data = get_post_custom($args['id']);
	$dm_block_html = isset($data['dm_block_html']) ? html_entity_decode(esc_html($data['dm_block_html'][0]), ENT_QUOTES) : '';
	$dm_block_css = isset($data['dm_block_css']) ? html_entity_decode(esc_html($data['dm_block_css'][0]), ENT_QUOTES) : '';
	$dm_block_js = isset($data['dm_block_js']) ? html_entity_decode(esc_html($data['dm_block_js'][0]), ENT_QUOTES) : '';

	ob_start();

	$dcb_show_post = isset($data['dm_block_show_post']) ? $data['dm_block_show_post'][0] : '';
	if($dcb_show_post == 'on'){
		echo apply_filters( 'the_content', do_shortcode($cb_content));
	}

	if($dm_block_html != '') {
		?>
		<?php
		echo do_shortcode($dm_block_html);
	}


	if($dm_block_css != '') {
		?><style><?php
		echo $dm_block_css;
		?></style><?php
	}

	if($dm_block_js != '') {
		?><script>
            if(typeof(jQuery) !== 'undefined') {
                $ = jQuery.noConflict();
            }
			<?php
			echo $dm_block_js;
			?></script><?php
	}

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode( 'dm_block', 'dm_content_block' );