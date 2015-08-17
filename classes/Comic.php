<?php

class Comic{
    public $id;
    public $title;
    public $date;
    public $url;
    public $imageURL;
    public $post;
    public $content;

    public function __construct($post=false){
        if($post){
            $this->post = $post;
            $this->setupFromPost();
        }
    }

    public function setupFromPost(){
        $this->id = $this->post->ID;
        $this->title = $this->post->post_title;
        $this->date = $this->post->post_date;
        $this->url = get_post_permalink($this->id);
        $this->thumbId = get_post_thumbnail_id( $this->id );
        $this->content = apply_filters('the_content',$this->post->post_content);
        $imageURLParts = wp_get_attachment_image_src( get_post_thumbnail_id( $this->id ), 'single-post-thumbnail' );
        if($imageURLParts){
            $this->imageURL = $imageURLParts[0];
        }
    }

    public function getMostRecent(){
        $args = array(
            'post_type' => 'jkcomic',
            'posts_per_page' => '1',
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            $this->post = $query->posts[0];
            $this->setupFromPost();
        }
    }

    public function getByPostId($id){
        $args = array(
            'post_type' => 'jkcomic',
            'posts_per_page' => '1',
            'p' => $id
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            $this->post = $query->posts[0];
            $this->setupFromPost();
        }
    }

    public function getNextURL(){
        $comics = self::getAll(array('orderby'=>'date','order'=>'ASC'));
        $nextComicURL = false;
        $triggerNext = false;
        foreach($comics as $comic){
            if($triggerNext){
                $nextComicURL = $comic->url;
                $triggerNext = false;
            }
            if($comic->id == $this->id){
                $triggerNext = true;
            }
        }
        return $nextComicURL;
    }

    public function getPrevURL(){
        $comics = self::getAll(array('orderby'=>'date','order'=>'DESC'));
        $ComicURL = false;
        $triggerPrev = false;
        foreach($comics as $comic){
            if($triggerPrev){
                $ComicURL = $comic->url;
                $triggerPrev = false;
            }
            if($comic->id == $this->id){
                $triggerPrev = true;
            }
        }
        return $ComicURL;
    }

    public static function getAll($argOverrides=array()){
        $posts = array();
        $args = array(
            'post_type' => 'jkcomic',
            'posts_per_page' => '-1',
        );
        $args = array_merge($args, $argOverrides);
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $posts[] = new Comic($query->post);
            }
        }
        return $posts;
    }
}