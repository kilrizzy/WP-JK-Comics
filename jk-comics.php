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
add_action( 'init', 'jkcomics_create_comic_post_type' );
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