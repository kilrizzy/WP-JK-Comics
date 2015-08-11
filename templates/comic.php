<div class="comic-container">
    <div class="comic-categories-container">
        <?php if(!empty($categories)){ ?>
            <ul class="comic-categories">
                <?php foreach($categories as $category){ ?>
                    <?php
                    $args = array();
                    $args['orderby'] = 'date';
                    $args['order'] = 'ASC';
                    $args['tax_query'] = array(
                        array(
                            'taxonomy' => 'comic_types',
                            'field'    => 'slug',
                            'terms'    => $category->slug,
                        ),
                    );
                    $posts = Comic::getAll($args);
                    $postFirst = $posts[0];
                    ?>
                    <li><a href="<?php echo $postFirst->url; ?>"><?php echo $category->name; ?></a></li>
                <?php } ?>
            </ul>
        <?php } ?>
    </div>
    <div class="comic-header">
        <?php if($comic->getPrevURL()){ ?>
            <div class="pager-prev"><a href="<?php echo $comic->getPrevURL(); ?>">Prev</a></div>
        <?php } ?>
        <div class="comic-title"><?php echo $comic->title; ?></div>
        <?php if($comic->getNextURL()){ ?>
            <div class="pager-next"><a href="<?php echo $comic->getNextURL(); ?>">Next</a></div>
        <?php } ?>
    </div>
    <div class="comic">
        <?php if(!empty($comic->imageURL)){ ?>
        <img src="<?php echo $comic->imageURL; ?>" class="img-responsive" />
        <?php } ?>
    </div>
</div>