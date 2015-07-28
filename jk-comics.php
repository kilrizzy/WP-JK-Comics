<?php
/**
 * Plugin Name: JK Comic Manager
 * Plugin URI: http://jeffkilroy.com
 * Description: Manage and view comic posts
 * Version: 1.0.0
 * Author: Jeffrey Kilroy
 * Author URI: http://jeffkilroy.com
 * License: GPL2
 */
global $jkSettings;
require('classes/Comic.php');

$jkSettings = array(
    'comicName' => 'jkcomic',
    'comicURL' => 'comic',
    'comicPlural' => 'Comics',
    'comicSingular' => 'Comic',
);

add_action( 'init', 'jkcomic_create_comic_type' );
function jkcomic_create_comic_type() {
    global $jkSettings;
    register_post_type( 'jkcomic',
        array(
            'labels' => array(
                'name' => __( $jkSettings['comicPlural'] ),
                'singular_name' => __( $jkSettings['comicSingular'] ),
                'add_new_item' => __( 'Add New '.$jkSettings['comicSingular'] ),
                'new_item' => __( 'New '.$jkSettings['comicSingular'] ),
                'view_item' => __( 'View '.$jkSettings['comicSingular'] ),
                'search_items' => __( 'Search '.$jkSettings['comicPlural'] ),
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => $jkSettings['comicURL']),
            'supports' => array(
                'title',
                'excerpt',
                'thumbnail',
            ),
        )
    );
}

add_filter('gettext','jkcomic_override_comic_post_title');
function jkcomic_override_comic_post_title( $input ) {
    global $post_type, $jkSettings;
    if( is_admin() && 'Enter title here' == $input && $post_type == $jkSettings['comicName'] )
        return 'Comic Title';
    return $input;
}

add_filter('gettext','jkcomic_override_comic_post_excerpt');
function jkcomic_override_comic_post_excerpt( $input ) {
    global $post_type, $jkSettings;
    if( is_admin() && 'Excerpts are optional hand-crafted summaries of your content that can be used in your theme. <a href="https://codex.wordpress.org/Excerpt" target="_blank">Learn more about manual excerpts.</a>' == $input && $post_type == $jkSettings['comicName'] )
        return 'Comic Alt Text / Description';
    return $input;
}

add_filter('gettext','jkcomic_override_comic_post_featured_image');
function jkcomic_override_comic_post_featured_image( $input ) {
    global $post_type, $jkSettings;
    if( is_admin() && 'Featured Image' == $input && $post_type == $jkSettings['comicName'] )
        return 'Comic Panel Image';
    if( is_admin() && 'Set featured image' == $input && $post_type == $jkSettings['comicName'] )
        return 'Set Comic Panel Image';
    return $input;
}