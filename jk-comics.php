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
    require_once __DIR__.'/classes/WPDeveloper.php';
}
if(!class_exists('Comic')){
    require_once __DIR__.'/classes/Comic.php';
}
$wpDeveloper = new WPDeveloper();
$wpDeveloper->verify();

add_action( 'init', 'jkcomics_init' );
function jkcomics_init(){
    jkcomics_create_comic_post_type();
    jkcomics_create_comic_type_taxonomy();
    //Admin Page
    add_action( 'admin_menu', 'jkcomics_plugin_menu' );
    //Shortcodes
    add_shortcode( 'comic', 'jkcomics_display_comic' );
    add_shortcode( 'comic-selector', 'jkcomics_display_comic_selector' );
    //Scripts
    add_action( 'wp_enqueue_scripts', 'jkcomics_scripts' );
}
function jkcomics_scripts(){
    wp_enqueue_script( 'comic-selector', plugins_url( 'js/comic-selector.js', __FILE__ ) , array('jquery') );
}
function jkcomics_display_comic_selector($atts){
    $a = shortcode_atts( array(
        'cat' => false,
    ), $atts );
    //
    $output = array();
    //
    $args = array();
    $args['orderby'] = 'date';
    $args['order'] = 'ASC';
    $term = false;
    if($a['cat']){
        $term = get_term_by('slug', $a['cat'], 'comic_types');
        $args['tax_query'] = array(
            array(
                'taxonomy' => 'comic_types',
                'field'    => 'slug',
                'terms'    => $a['cat'],
            ),
        );
    }
    $comics = Comic::getAll($args);
    $template = new Template();
    $templateResponse = $template->get(__DIR__.'/templates/comic-selector.php',array('comics'=>$comics, 'term'=>$term));
    $output[] = $templateResponse;
    $output = implode("\n",$output);
    return $output;
}

function jkcomics_display_comic( $atts ) {
    $a = shortcode_atts( array(
        'id' => false,
        'bar' => 'something else',
    ), $atts );
    $output = array();
    //
    $comic = new Comic();
    if(!$a['id']){
        $comic->getMostRecent();
    }else{
        $comic->getByPostId($a['id']);
    }
    //
    $categories = get_terms('comic_types');
    //
    $template = new Template();
    $templateResponse = $template->get(__DIR__.'/templates/comic.php',array('comic'=>$comic, 'categories'=>$categories));
    $output[] = $templateResponse;

    $output = implode("\n",$output);
    return $output;
}


function jkcomics_create_comic_post_type(){
    $postType = new PostType();
    $postType->name = 'jkcomic';
    $postType->urlSlug = 'comic';
    $postType->labelPlural = 'Comics';
    $postType->labelSingular = 'Comic';
    $postType->excerptTitle = 'Description';
    $postType->excerptHelp = 'Comic Alt Text / Description';
    $postType->iconCSSContent = '\f161';
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
    $output = array();

    //get all comic_types
    $terms = get_terms('comic_types', array('hide_empty'=>false));

    //post import
    if(!empty($_POST['path'])){
        $path = $_POST['path'];
        $categoryId = $_POST['category'];
        //scan directory
        if (file_exists($path)) {
            $output[] = '<p><code>Directory Found...scanning files</code></p>';
            $files = array();
            $dh = opendir($path);
            while (false !== ($filename = readdir($dh))) {
                $files[] = $filename;
            }
            foreach($files as $file){
                if(strstr($file,'.gif')){
                    //import this as a post if the date does not exist
                    $output[] = '<p><code>'.jkcomics_import_comic_from_file($file, $categoryId, $path).'</code></p>';
                }
            }
            //mkdir("folder/" . $dirname, 0777);
            //echo "The directory $dirname was successfully created.";
        } else {
            $output[] = '<p><strong>Path does not exist</strong></p>';
        }
    }

    //output
    //-form
    $output[] = '<div class="wrap">';
    $output[] = '<form method="post">';
    $output[] = '<input type="text" name="path" value="" />';
    $output[] = '<select name="category">';
    foreach($terms as $term){
        $output[] = '<option value="'.$term->term_id.'">'.$term->name.'</option>';
    }
    $output[] = '</select>';
    $output[] = '<input type="submit" value="Import" />';
    $output[] = '</form>';
    $output[] = '</div>';
    //-import response
    //
    $output = implode("\n", $output);
    echo $output;
}

function jkcomics_import_comic_from_file($file, $categoryId, $path){
    $output = array();
    $fullFilePath = $path.'/'.$file;
    $output[] = $fullFilePath;
    //$output[] = $file;
    $fileWithoutExtension = preg_replace('/\\.[^.\\s]{3,4}$/', '', $file);
    $post_filename_parts = explode('_',$fileWithoutExtension);
    if(count($post_filename_parts) != 2){
        $output[] = 'file error';
        return implode("\n", $output);
    }
    $comic_date = array('y'=>'','m'=>'','d'=>'');
    $comic_date['y'] = $post_filename_parts[0];
    $comic_date['m'] = substr($post_filename_parts[1], 0, -2);
    $comic_date['m'] = sprintf('%02d', $comic_date['m']);
    $comic_date['d'] = substr($post_filename_parts[1], -2);
    $comic_date['d'] = sprintf('%02d', $comic_date['d']);
    $comic_title = $comic_date['m'].'-'.$comic_date['d'].'-'.$comic_date['y'];
    //see if comic exists
    $existingPage = get_page_by_title( $comic_title, 'OBJECT', 'jkcomic' );
    if($existingPage){
        $output[] = $comic_title.' post exists';
    }else{
        $output[] = $comic_title.' post does not exist';
        $post = new Post();
        $post->type = 'jkcomic';
        $post->title = $comic_title;
        $post->setDate($comic_date['y'].'-'.$comic_date['m'].'-'.$comic_date['d']);
        $existingPage = $post->insert();
    }
    if($existingPage) {
        //Attach taxonomy
        wp_set_post_terms( $existingPage->ID, array($categoryId), 'comic_types' );
        //Attach image
        $existingAttachmentId = get_post_meta( $existingPage->ID, '_thumbnail_id', true );
        $output[] = '<br/>'.$existingAttachmentId.'<br/>';
        if(empty($existingAttachmentId)) {
            $attachment = new Attachment();
            $output[] = $attachment->uploadFeaturedImage($fullFilePath,$existingPage->ID);
        }
    }
    //return
    return implode("\n", $output);
}