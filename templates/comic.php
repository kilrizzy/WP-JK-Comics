<div class="comic-container">
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