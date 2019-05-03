<?php
/**
 * Plugin Name: DM Content Blocks
 * Description: This plugin adds content blocks with HTML, JS, and CSS blocks to be called by using a shortcode.
 * Version: 0.5.0
 * Author: DM+ Team
 * Author URI: http://dmarkweb.com
 * License: GPL2
 */

/**
 * Plugin that allows developers to add custom HTML / CSS / JS code using shortcodes to the Wordpress widgets
 * @author DM+Team
 * @website http://dmarkweb.com
 * @package DMBlocks
 */

defined( 'ABSPATH' ) or die( 'Tada! You got caught ;)' );
define("DM_BLOCKS_VERSION", "0.5.0");

include "post-type.php";
include "revisions.php";
include "metabox-info.php";
include "metabox-blocks.php";
include "metabox-options.php";
include "shortcode.php";
