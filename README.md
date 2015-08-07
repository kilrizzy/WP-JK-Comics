#Integration

Create a file in your theme called "single-jkcomic.php"

Add:

```
<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
    <?php echo do_shortcode('[comic id="'.get_the_ID().'"]'); ?>
<?php endwhile; endif; ?>
```