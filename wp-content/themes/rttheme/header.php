<?php
/**
 * The Header for our theme
 *
 * Displays all of the <head> section and everything up till <div id="main">
 */
?><!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) & !(IE 8)]><!-->
<html <?php language_attributes(); ?>>
    <!--<![endif]-->
    <head>
        <meta charset="<?php bloginfo('charset'); ?>">
        <meta name="viewport" content="width=device-width">
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
        <!--[if lt IE 9]>
        <script src="<?php echo get_template_directory_uri(); ?>/js/html5.js"></script>
        <![endif]-->
        <link rel="apple-touch-icon" sizes="57x57" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-57x57.png">
        <link rel="apple-touch-icon" sizes="60x60" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-60x60.png">
        <link rel="apple-touch-icon" sizes="72x72" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-72x72.png">
        <link rel="apple-touch-icon" sizes="76x76" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-76x76.png">
        <link rel="apple-touch-icon" sizes="114x114" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-114x114.png">
        <link rel="apple-touch-icon" sizes="120x120" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-120x120.png">
        <link rel="apple-touch-icon" sizes="144x144" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-144x144.png">
        <link rel="apple-touch-icon" sizes="152x152" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-152x152.png">
        <link rel="apple-touch-icon" sizes="180x180" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/apple-touch-icon-180x180.png">
        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/favicon-32x32.png" sizes="32x32">
        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/android-chrome-192x192.png" sizes="192x192">
        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/favicon-96x96.png" sizes="96x96">
        <link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/favicon-16x16.png" sizes="16x16">
        <link rel="manifest" href="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/manifest.json">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="msapplication-TileImage" content="<?php echo get_stylesheet_directory_uri() ?>/images/favicons/mstile-144x144.png">
        <meta name="theme-color" content="#ffffff">
        <?php wp_head(); ?>
    </head>

    <body <?php body_class(); ?>>
        <!-- Facebook Script Code -->
        <div id="fb-root"></div>
        <script>(function (d, s, id) {
                var js, fjs = d.getElementsByTagName(s)[0];
                if (d.getElementById(id))
                    return;
                js = d.createElement(s);
                js.id = id;
                js.src = "//connect.facebook.net/en_GB/sdk.js#xfbml=1&version=v2.4&appId=1570798473187078";
                fjs.parentNode.insertBefore(js, fjs);
            }(document, 'script', 'facebook-jssdk'));
        </script>
        <div class="test-main"><!-- Main Wrapper  Starts -->
            <div class="container-fluid main-wrapper"><!-- Main Header Container -->
                <header class="header"> <!-- Header Starts -->
                    <div class="header-wrap">  <!-- Header Wrap Starts -->
                        <div class="row">
                            <div class="col-md-12">
                                <div class=" wrapper-body top-navbar">
                                    <div class="logo-top-section">
                                        <div class="container">
                                            <div class="row">
                                                <div class="col-md-6 col-sm-6 col-xs-12 top-logo">
                                                    <div class="logo"><!-- Logo Wrap Starts -->
                                                        <!-- http://code.tutsplus.com/articles/how-to-integrate-the-wordpress-media-uploader-in-theme-and-plugin-options--wp-26052 -->
                                                        <?php $wptuts_options = get_option('theme_wptuts_options'); ?>
                                                        <?php if ($wptuts_options['logo'] != '') { ?>
                                                            <a href="<?php bloginfo('url'); ?>">  <img src="<?php echo $wptuts_options['logo']; ?>" /> </a>
                                                        <?php } else {
                                                            ?>
                                                            <a href = "<?php bloginfo('url'); ?>"><img class = "img-responsive" src = "<?php bloginfo("stylesheet_directory"); ?>/images/logo.png"/></a>
                                                        <?php }
                                                        ?>
                                                    </div><!-- Logo Wrap Ends -->
                                                </div>
                                                <div class="col-md-6 col-sm-6 col-xs-12 sec-menu-section">
                                                    <div class="sec-menu"> <!-- Secondary Top menu-->
                                                        <?php
                                                        $menu_args = array(
                                                            'menu' => 'Top Menu',
                                                            'container' => 'container',
                                                            'container_class' => 'container_class',
                                                            'container_id' => 'container_id',
                                                            'menu_class' => 'nav navbar-nav',
                                                            'menu_id' => 'menu_id',
                                                            'echo' => true);
                                                        ?>
                                                        <?php wp_nav_menu($menu_args); ?>
                                                    </div>
                                                    <div class="clearfix"></div>
                                                    <div class="google-cse">
                                                        <!-- https://cse.google.com/cse/tools/create_onthefly -->
                                                        <!-- Use of this code assumes agreement with the Google Custom Search Terms of Service. -->
                                                        <!-- The terms of service are available at http://www.google.com//cse/docs/tos.html -->
                                                        <form name="cse" id="searchbox_demo" action="https://www.google.com/cse">
                                                            <input type="hidden" name="cref" value="" />
                                                            <input type="hidden" name="ie" value="utf-8" />
                                                            <input type="hidden" name="hl" value="" />
                                                            <input name="q" type="text" size="40" />
                                                            <input type="submit" name="sa" value="Search" />
                                                        </form>
                                                        <script type="text/javascript" src="https%3A%2F%2Fcse.google.com%2Fcse/tools/onthefly?form=searchbox_demo&lang="></script>
                                                    </div>
                                                </div>
                                            </div><!-- End Row -->
                                        </div>
                                    </div>
                                    <div class="container">
                                        <div class="row">
                                            <div class="col-md-12 col-sm-12 col-xs-12 navigation-main"><!-- Primary Menu Starts -->
                                                <nav class="navbar navbar-default main-top-nav" role="navigation">  
                                                    <div class="nav-container">
                                                        <!-- Brand and toggle get grouped for better mobile display -->
                                                        <div class="navbar-header">
                                                            <button type="button" class="navbar-toggle mobile-menu toggle-menu" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                                                <span class="sr-only">Toggle navigation</span>
                                                                <span class="icon-bar"></span>
                                                                <span class="icon-bar"></span>
                                                                <span class="icon-bar"></span>
                                                            </button>
                                                        </div>
                                                        <div class="collapse navbar-collapse nav-pages" id="bs-example-navbar-collapse-1">
                                                            <?php
                                                            $menu_args = array(
                                                                'menu' => 'Main Menu',
                                                                'container' => 'container',
                                                                'container_class' => 'container_class',
                                                                'container_id' => 'container_id',
                                                                'menu_class' => 'nav navbar-nav nav-items',
                                                                'menu_id' => 'menu_id',
                                                                'echo' => true);
                                                            ?>
                                                            <?php wp_nav_menu($menu_args); ?>
                                                        </div>
                                                    </div>
                                                </nav>
                                            </div> <!-- Primary Menu Ends -->
                                        </div><!-- End Row -->
                                    </div><!-- Container End -->
                                </div>
                            </div>
                        </div>
                    </div>  <!-- Header Wrap Ends -->
                </header> <!-- Header Ends -->


