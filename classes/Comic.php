<?php

class Comic{
    public $id;
    public $title;
    public $date;
    public $imageURL;
    public $nextURL;
    public $backURL;
    public $post=false;

    public function getMostRecent(){
        $args = array(
            'post_type' => 'jkcomic',
            'posts_per_page' => '1',
        );
        $query = new WP_Query( $args );
        if ( $query->have_posts() ) {
            $this->post = $query->posts[0];
            $this->id = $this->post->ID;
            $this->title = $this->post->post_title;
            $this->date = $this->post->post_date;
            $this->thumbId = get_post_thumbnail_id( $this->id );
            $imageURLParts = wp_get_attachment_image_src( get_post_thumbnail_id( $this->id ), 'single-post-thumbnail' );
            if($imageURLParts){
                $this->imageURL = $imageURLParts[0];
            }
            //Next / Back links - get_previous_posts...won't work here. We need custom fun. Womp Womp
            $this->nextURL = $this->getNextURL();

        }
    }

    public function getNextURL(){
        $comics = self::getAll();
        print_r($comics);
        //loop and get next
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
                $posts[] = $query->post;
            }
        }
        return $posts;
    }
}