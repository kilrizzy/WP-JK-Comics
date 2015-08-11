<div class="comic-selector-container <?php if($term){ echo 'comic-selector-container-'.$term->slug;  } ?>">
    <div class="inner">
        <?php if($term){ ?>
            <div class="title"><?php echo $term->name; ?></div>
        <?php } ?>
        <select class="comic-selector form-control">
            <option>---</option>
            <?php foreach($comics as $comic){ ?>
                <option value="<?php echo $comic->url; ?>"><?php echo $comic->title; ?></option>
            <?php } ?>
        </select>
    </div>
</div>