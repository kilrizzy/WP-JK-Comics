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

//Require JK PostDeveloper
if(!class_exists('WPDeveloper')){
    require_once( 'classes/WPDeveloper.php' );
}
$wpDeveloper = new WPDeveloper();
$wpDeveloper->verify();

//Create Post Type
add_action( 'init', 'jkcomics_init' );
function jkcomics_init(){
    jkcomics_create_comic_post_type();
    jkcomics_create_comic_type_taxonomy();
    //Admin Page
    add_action( 'admin_menu', 'jkcomics_plugin_menu' );
}

function jkcomics_create_comic_post_type(){
    $postType = new PostType();
    $postType->name = 'jkcomic';
    $postType->urlSlug = 'comic';
    $postType->labelPlural = 'Comics';
    $postType->labelSingular = 'Comic';
    $postType->excerptTitle = 'Description';
    $postType->excerptHelp = 'Comic Alt Text / Description';
    $postType->create();
}

function jkcomics_create_comic_type_taxonomy(){
    $taxonomy = new Taxonomy();
    $taxonomy->post_type = 'jkcomic';
    $taxonomy->name = 'comic_types';
    $taxonomy->urlSlug = 'comic-type';
    $taxonomy->labelPlural = 'Comic Types';
    $taxonomy->labelSingular = 'Comic Type';
    $taxonomy->create();
}

//Menu
function jkcomics_plugin_menu() {
    add_options_page( 'JK Comic Import', 'JK Comics Import', 'manage_options', 'jk-comics-import', 'jkcomics_import_page' );
}

//Import Page
function jkcomics_import_page() {
    if ( !current_user_can( 'manage_options' ) )  {
        wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
    }
    echo '<div class="wrap">';
    echo '<p>Here is where the form would go if I actually had options.</p>';
    echo '</div>';
}