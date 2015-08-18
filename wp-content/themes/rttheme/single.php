<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Sunrisers Hyderabad 1.0
 */
get_header();
?>

<?php
if (have_posts()) :
    ?>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php the_content(); ?>
            </div>
        </div>

    </div>
    <?php
endif;
?>
<?php
get_footer();
