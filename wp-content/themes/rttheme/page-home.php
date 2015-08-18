<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme and one
 * of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query,
 * e.g., it puts together the home page when no home.php file exists.
 *
 * @link http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Fourteen
 * @since Twenty Fourteen 1.0
 * http://wordpress.stackexchange.com/questions/26452/how-to-load-post-content-on-index-page-using-ajax-when-post-title-in-sidebar-is
 *  wordpress.stackexchange.com/questions/9233/how-can-i-fetch-loop-of-post-titles-via-ajax
 * 
 */
get_header();
?>
<div class="container">
    <div class="home-page">
        <div class="slider-top-wrap">
            <div class="first-block"><!-- First Block -->
                <div class="row">
                    <div class="col-md-7 col-sm-7 col-xs-12 remove-padding"><!-- Top Slider-->
                        <?php if (have_rows('home_top_slider')) : ?>
                            <div class="home-slider">
                                <div id="owl-demo" class="owl-carousel owl-theme big-slider">
                                    <?php
                                    while (have_rows('home_top_slider')): the_row();
                                        ?>
                                        <div class="item ">
                                            <?php
                                            echo wp_get_attachment_image(get_sub_field('home_top_slider_images'), 'full', false, 'class=img-responsive slider-dp');
                                            ?>
                                        </div>
                                    <?php endwhile; ?>
                                </div>
                                <div class="customNavigation">
                                    <a class="btn prev glyphicon glyphicon-menu-left home-slider-left"></a>
                                    <a class="btn next glyphicon glyphicon-menu-right home-slider-right"></a>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-5 col-sm-5 col-xs-12 about remove-padding"><!-- Welcome Text -->
                        <section>
                            <?php
                            $post_objects = get_field('home_content_box');
                            $postId = $post_objects->ID;
                            $postContent = $post_objects->post_content;
                            echo($postContent);
                            ?>
                            <a href = "<?php echo get_permalink($postId); ?>">Read more...</a>
                        </section>
                    </div>
                </div>
            </div> <!-- First Block End -->

            <!-- http://codepen.io/OwlFonk/pen/qhgjb/ -->
            <div class="second-block"><!-- Second Block -->
                <div class="row"><!-- You Tube Slider -->
                    <?php add_thickbox(); ?>
                    <hr>
                    <?php if (have_rows('youtube_slider')): ?>
                        <?php $i = 1; ?>
                        <?php while (have_rows('youtube_slider')): the_row(); ?>
                            <div id="my-content-id<?php echo $i; ?>" style="display:none;">
                                <?php // the_sub_field('youtube_video_links'); ?>
                                <iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?php the_sub_field('youtube_video_id'); ?>" frameborder="0" allowfullscreen></iframe>
                            </div>
                            <?php $i++; ?>
                        <?php endwhile; ?>
                    <?php endif; ?>

                    <?php if (have_rows('youtube_slider')): ?>
                        <div id="owl-video" class="owl-video">
                            <?php $i = 1; ?>
                            <?php while (have_rows('youtube_slider')): the_row(); ?>
                                <div class="item"><a href="#TB_inline?width=600&height=550&inlineId=my-content-id<?php echo $i; ?>" class="thickbox"><img src="http://img.youtube.com/vi/<?php the_sub_field('youtube_video_id'); ?>/0.jpg" alt="Owl Image"></a></div>
                                <?php $i++; ?>
                            <?php endwhile; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div> <!-- Second Block End -->
            <div class="third-block"><!-- Third Block -->
                <hr>
                <div class="row"><!-- Regular Posts -->
                    <div class="col-md-8 col-sm-8 col-xs-12  border-right">
                        <div class="headingblock">Glimpses of Exhibition</div>
                        <?php
                        $args = array('post_type' => 'rt_posts');
                        $the_query = new WP_Query($args);
                        ?>
                        <?php if ($the_query->have_posts()): ?>
                            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                                <div class="col-md-3 col-sm-3 col-xs-6 remove-padding">
                                    <div class="single-post">
                                        <div class="post-image"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></div>
                                        <a class="post-heading" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        <?php endif; ?>
                    </div>
                    <div class="col-md-4 col-sm-4 col-xs-12">
                        <div class="headingblock">News</div>
                        <div class="sticky-posts"><!-- Sticky Posts -->
                            <?php
                            $sticky = get_option('sticky_posts');
                            $args = array(
                                'posts_per_page' => 1,
                                'post__in' => $sticky,
                                'ignore_sticky_posts' => 1
                            );
                            $query = new WP_Query($args);
                            if (isset($sticky[0])) {
                                while ($query->have_posts()) : $query->the_post();
                                    ?>
                                    <div class="sticky-post-info">
                                        <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a>
                                        <a class="sticky-heading" href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                                        <p><?php the_time('F j, Y'); ?></p>
                                    </div>
                                    <?php
                                endwhile;
                            }
                            ?>
                        </div>
                        <div class="clearfix"></div>
                        <div class="news-posts"><!-- News Category POsts -->
                            <?php echo do_shortcode('[ajax_load_more post_type = "post" post_format = "standard" category = "news" category__not_in = "1" scroll = "false"]'); ?>
                        </div>
                    </div>
                </div>
            </div> <!-- Third Block End -->

            <div class="forth-block"><!-- Forth Block -->
                <hr>
                <div class="row">
                    <div class='col-md-4 ol-sm-4 col-xs-12 border-right'><!-- Twitter Widget -->
                        <div class="headingblock">Latest Tweets</div>
                        <div class="twitter-widget">
                            <a class="twitter-timeline" href="https://twitter.com/rtCamp" data-widget-id="632893969773391872">Tweets by @rtCamp</a>
                            <script>!function(d, s, id){var js, fjs = d.getElementsByTagName(s)[0], p = /^http:/.test(d.location)?'http':'https'; if (!d.getElementById(id)){js = d.createElement(s); js.id = id; js.src = p + "://platform.twitter.com/widgets.js"; fjs.parentNode.insertBefore(js, fjs); }}(document, "script", "twitter-wjs");</script>
                        </div>

                    </div>
                    <div class='col-md-4 ol-sm-4 col-xs-12'><!-- Facebook Widget -->
                        <div class="headingblock">Follow us on Facebook</div>
                        <div class="fb-page" data-href="https://www.facebook.com/rtCamp.solutions" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                            <div class="fb-xfbml-parse-ignore">
                                <blockquote cite="https://www.facebook.com/rtCamp.solutions">
                                    <a href="https://www.facebook.com/rtCamp.solutions">Facebook</a>
                                </blockquote>
                            </div>
                        </div>
                    </div>
                    <div class='col-md-4 ol-sm-4 col-xs-12 border-left'>
                        <div class="headingblock">Weather</div>
                        <div class="weather-widget"><!-- Weather Widget -->
                            <?php if (is_active_sidebar('rttheme-weather')) : ?>
                                <div id="secondary" class="widget-area" role="complementary">
                                    <?php dynamic_sidebar('rttheme-weather'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                        <hr>
                        <div class="headingblock">Date and Time</div>
                        <div class="date-widget"><!-- Date Widget -->
                            <?php if (is_active_sidebar('rttheme-datetime')) : ?>
                                <div id="secondary" class="widget-area" role="complementary">
                                    <?php dynamic_sidebar('rttheme-datetime'); ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div> <!-- Forth Block End -->

            <div class="clearfix"></div>
            <div class="fifth-block"><!-- Fifth Block -->
                <div class="row"><!-- Partners Slider -->
                    <hr>
                    <div class="headingblock">Our Partners</div>
                    <?php
                    $args = array('post_type' => 'rt_post_sports');
                    $the_query = new WP_Query($args);
                    ?>
                    <?php if ($the_query->have_posts()): ?>
                        <div id="owl-partner" class="owl-partner">
                            <?php while ($the_query->have_posts()) : $the_query->the_post(); ?>
                                <div class="col-md-3 col-sm-3 col-xs-6">
                                    <div class="single-post-partner">
                                        <div class="post-image item"><a href="<?php the_permalink(); ?>"><?php the_post_thumbnail(); ?></a></div>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <?php wp_reset_postdata(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div> <!-- Fifth Block End -->
        </div> 
        <hr>
    </div>
    <?php
    get_footer();
    