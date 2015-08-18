<?php
/**
 * The template for displaying the footer
 *
 * Contains footer content and the closing of the #main and #page div elements.
 *
 * @package WordPress
 * @subpackage rtCamp
 * @since rtCamp Hyderabad 1.0
 */
?>
</div><!-- Container End -->
<div class="footer-full-width"><!-- Footer Starts -->
    <div class="container footer-inner-wrap">
        <div class="row">
            <div class='col-md-4 ol-sm-4 col-xs-12 pages-widget'><!-- Pages Widget -->
                <?php if (is_active_sidebar('rttheme-footerone')) : ?>
                    <div id="secondary" class="widget-area" role="complementary">
                        <?php dynamic_sidebar('rttheme-footerone'); ?>
                    </div>
                <?php endif; ?>
            </div> 
            <div class='col-md-4 ol-sm-4 col-xs-12 links-widget'><!-- Links Widget -->
                <?php if (is_active_sidebar('rttheme-footertwo')) : ?>
                    <div id="secondary" class="widget-area" role="complementary">
                        <?php dynamic_sidebar('rttheme-footertwo'); ?>
                    </div>
                <?php endif; ?>
            </div> 
            <div class='col-md-4 ol-sm-4 col-xs-12 static-text'><!-- Footer Logo -->
                <?php if (is_active_sidebar('rttheme-footerthree')) : ?>
                    <div id="secondary" class="widget-area" role="complementary">
                        <?php dynamic_sidebar('rttheme-footerthree'); ?>
                    </div>
                <?php endif; ?>
            </div> 
        </div> 
    </div>
    <div class="footer-bottom-text">
        <div class="container">
            <p>
                <strong>Disclaimer:</strong> Sit arcu nec cras elit? Vut sagittis magna nisi vel integer arcu? Dis pulvinar scelerisque pulvinar rhoncus integer, integer in? 
                Ac, cum etiam tortor duis placerat mid nunc cras integer, aliquam porttitor.
                Dis pulvinar scelerisque pulvinar rhoncus integer, integer in? Ac, cum etiam tortor duis placerat mid nunc cras integer, aliquam porttitor.
            </p>
        </div>
    </div>
</div><!-- Footer Ends -->
</div><!-- Main Header Container end -->
<div class="clearfix"></div>
</div><!-- Test Main Container end -->
<?php wp_footer(); ?>
</body>
</html>