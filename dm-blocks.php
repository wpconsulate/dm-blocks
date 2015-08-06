<?php
/*
Plugin Name: DM Blocks
Description: Register Custom Content Section as "DM Blocks"
Author: Deepak Oberoi
Version: 0.4.0
Author URI: http://www.deepakoberoi.com
Plugin URI: http://www.deepakoberoi.com/plugins/single-post-widget
Domain Path: /languages
Text Domain: dm-blocks
*/
/**
* 
* @author Deepak Oberoi
* @link http://www.deepakoberoi.com
*/

add_action('init', 'dm_blocks_init' );

function dm_blocks_init(){
    

    register_post_type('dm_blocks', 
                                array(    
                                    'label' => 'Blocks',
                                    'description' => 'Create content blocks which can be used in posts, pages and widgets.',
                                    'public' => true,
                                    'show_ui' => true,
                                    'show_in_menu' => true,
                                    'capability_type' => 'page',
                                    'hierarchical' => true,
                                    'rewrite' => array('slug' => ''),
                                    'query_var' => true,
                                    'has_archive' => true,
                                    'exclude_from_search' => true,
                                    'supports' => array('title','editor','custom-fields','revisions','author',),
                                    'labels' => array (
                                                        'name' => 'DM Blocks',
                                                        'singular_name' => 'DM Block',
                                                        'menu_name' => 'DM Blocks',
                                                        'add_new' => 'Add New',
                                                        'add_new_item' => 'Add New block',
                                                        'new_item' => 'New block',
                                                        'edit' => 'Edit',
                                                        'edit_item' => 'Edit block',
                                                        'view' => 'View block',
                                                        'view_item' => 'View block',
                                                        'search_items' => 'Search Blocks',
                                                        'not_found' => 'No Blocks Found',
                                                        'not_found_in_trash' => 'No Blocks Found in Trash',
                                                        'parent' => 'Parent block',
                                                ),
                                    ) 
                    );
                    

}



add_action( 'manage_blocks_posts_custom_column', 'my_manage_blocks_columns', 10, 2 );
function my_manage_blocks_columns( $column, $post_id ) {
    global $post;

     $post_data = get_post($post_id, ARRAY_A);
     $slug = $post_data['post_name'];

    switch( $column ) {
        case 'shortcode' :
            echo '<span style="background:#eee;font-weight:bold;"> [dm_blocks id="'.$slug.'"] </span>';
        break;
    }
}


add_filter( 'manage_edit-blocks_columns', 'my_edit_blocks_columns' ) ;

function my_edit_blocks_columns( $columns ) {

    $columns = array(
        'cb' => '<input type="checkbox" />',
        'title' => __( 'Title' ),
        'shortcode' => __( 'Shortcode' ),
        'date' => __( 'Date' )
    );

    return $columns;

}


add_shortcode( 'dm_block', 'dm_block_shortcode' );
function dm_block_shortcode($atts, $content = null) {
    
    // get content by slug
    global $wpdb;
    
    extract( shortcode_atts(
                        array(
                            'id' => 0,
                            'slug' => ''
                        ), 
                        $atts
                        )
    );
    
    $post_id = $id ;
    
    if( $post_id == 0 ){
        
        $post_id = $wpdb->get_var( "SELECT ID FROM $wpdb->posts WHERE post_name = '$slug'" );
    
    }
    
    if($post_id){
        
        $args = array(
            'post_type' => 'dm_blocks',
            'posts_per_page ' => 1,
            'post__in' => explode(',', $post_id)
        );
        
        if ( function_exists('icl_object_id') ) 
            $args['language']  = ICL_LANGUAGE_NAME_EN;
        
        $_query = new WP_Query( $args );
        
        
        /*$html =    get_post_field('post_content', $post_id);*/
        $_html =    $_query->post->post_content;

        // add edit link for admins
        if (current_user_can('edit_posts')) {
            $edit_link = get_edit_post_link( $post_id ); 
            $html = '<div class="dm_block">';
            $html .= '<small><a class="edit-link" href="'.$edit_link.'">Edit Block</a></small>';
            $html .= $_html;
            $html .= '</div>';
        }else{
            $edit_link = get_edit_post_link( $post_id ); 
            $html = '<div class="dm_block">';
            $html .= $_html;
            $html .= '</div>';
        }

        $html = do_shortcode( $html );

    } else{

        $html = '<p><mark>Specified DM Block not found! Wrong ID/slug?</mark></p>';    
    
    }

    return $html;
    
}





if( !function_exists( 'vc_dm_block' ) ){
    
    function vc_dm_block(){
        
        if( !function_exists( 'vc_map' ) )  return;
        
        $args = array(
            'name'    =>    __('DM Blocks','dm'),
            'base'    =>    'dm_block',
            'category'    =>    __('DM+', 'dm'),
            'class'    =>    'vc_dm_block',
            'icon'    =>    'dm_block',
//            'admin_enqueue_css' => array(get_template_directory_uri().'/assets/css/vc.css'),
            'description'    =>    __('Include Contents from DM Blocks using ID?Slug','dm'),
            'params'    =>    array(
                                    array(
                                        'type'    =>    'textfield',
                                        'holder'    =>    'div',
                                        'class'    =>    '',
                                        'heading'    =>    __('Title','dm'),
                                        'param_name'    =>    'title'
                                    ),
                                    array(
                                        'type'    =>    'textfield',
                                        'holder'    =>    'div',
                                        'class'    =>    '',
                                        'heading'    =>    __('ID','dm'),
                                        'param_name'    =>    'id'
                                    ),
                                    array(
                                        'type'    =>    'textfield',
                                        'holder'    =>    'div',
                                        'class'    =>    '',
                                        'heading'    =>    __('Slug / Post Name','dm'),
                                        'param_name'    =>    'slug'
                                    ),
                                    array(
                                        'type' => 'textfield',
                                        'heading' => __( 'Extra class name', 'dm' ),
                                        'param_name' => 'el_class',
                                        'description' => __( 'If you wish to style particular content element differently, then use this field to add a class name and then refer to it in your css file.', 'dm' )
                                    )                    
                                )
        );
        
        vc_map( $args );
        
    }

    add_action( 'init' , 'vc_dm_block');

}




require_once( dirname(__FILE__) . '/widget.php' );
