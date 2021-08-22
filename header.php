<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta name="author" content="Scientina Skill" />
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <title><?php wp_title('|', true, 'right'); ?></title>
    <?php wp_head(); ?>
</head>

<body>
    <!-- Body Inner -->
    <div class="body-inner">
        <!-- Header -->
        <header id="header" class="dark">

            <div class="header-inner">
                <div class="container">
                    <!--Logo-->
                    <div id="logo">

                        <a href="<?php echo get_bloginfo('url'); ?>" class="logo-default" data-dark-logo="<?php echo $logo[0] ?>">
                            <img src="<?php echo get_template_directory_uri() . '/images/logo.png'; ?>" alt="Lab IP" class="py-3 d-none d-md-block">
                            <!--    <img src="" alt="Lab IP" class="d-inline d-md-none py-4">-->
                        </a>

                    </div>
                    <!--End: Logo-->
                    <!-- Search --
                    <div id="search"><a id="btn-search-close" class="btn-search-close" aria-label="Close search form"><i class="icon-x"></i></a>
                        <form role="search" id="searchform" class="search-form" action="/" method="get">
                            <input id="s" class="form-control" name="s" type="text" placeholder="Type & Search..." />
                            <span class="text-muted">Start typing & press "Enter" or "ESC" to close</span>
                        </form>
                    </div>
                    !-- end: search --
                    <!--Header Extras--
                    <div class="header-extras">
                        <ul>
                            <li>
                                <a id="btn-search" href="#"> <i class="icon-search"></i></a>
                            </li>
                        </ul>
                    </div>
                    <!--end: Header Extras-->
                    <!--Navigation Resposnive Trigger-->
                    <div id="mainMenu-trigger">
                        <a class="lines-button x"><span class="lines"></span></a>
                    </div>
                    <!--end: Navigation Resposnive Trigger-->
                    <!--Navigation-->
                    <div id="mainMenu">
                        <div class="container">
                            <nav>
                                <?php
                                wp_nav_menu(
                                    array(
                                        'menu'              => 'header',
                                        'theme_location'    => 'header',
                                        'depth'             => 2,
                                        'container'         => 'nav',
                                        'container_id'      => 'top_menu',
                                        'container_id'      => 'top_menu',
                                        'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                                        'walker'            => new WP_Bootstrap_Navwalker()
                                    )
                                )
                                ?>
                            </nav>
                        </div>
                    </div>
                    <!--end: Navigation-->

                </div>
            </div>
        </header>
        <!-- end: Header -->