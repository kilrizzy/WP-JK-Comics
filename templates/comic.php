<div class="comic-container">
    <div class="comic-header">
        <?php if(!empty($comicPagerBackURL)){ ?>
        <div class="pager-back"><a href="<?php echo $comicPagerBackURL; ?>">Back</a></div>
        <?php } ?>
        <div class="comic-title"><?php echo $comic->title; ?></div>
        <?php if(!empty($comicPagerNextURL)){ ?>
            <div class="pager-next"><a href="<?php echo $comicPagerNextURL; ?>">Next</a></div>
        <?php } ?>
    </div>
    <div class="comic">
        <?php if(!empty($comic->imageURL)){ ?>
        <img src="<?php echo $comic->imageURL; ?>" class="img-responsive" />
        <?php } ?>
    </div>
</div>