<!DOCTYPE html>
<html <?php language_attributes(); ?>>              
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />                   
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php wp_title('|', true, 'right'); ?></title>
        <link rel="profile" href="http://gmpg.org/xfn/11">                
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />               
        <?php wp_head(); ?>
    </head>    
    <?php
    $layout = get_option('kopa_theme_options_layout', 'wide');
    $layout = ( $layout === 'box' ? 'kopa-boxed' : '' );
    $layout .= ' heavy-dark-footer';
    ?>
    <body <?php body_class($layout); ?>>

        <div class="kopa-wrapper">

            <header id="page-header">
           
                <div id="header-top">
     
                    <div class="wrapper">
                        <div class="row-fluid">
                            <div class="span12 clearfix">
                              
                                <div class="top-box clearfix">
                                    <ul class="top-link clearfix">
  <div class="search clearfix"><p class="hot-line"><a href="tel:<?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_left_header_description'))); ?>"> Tel: <?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_left_header_description'))); ?></a></p> 
  <p class="hot-line email"> &nbsp;&nbsp; / &nbsp;&nbsp; Email: <?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_left_header_description_2'))); ?></p>
  
  <p class="hot-line phone"><a href="tel:<?php echo htmlspecialchars_decode(stripslashes(get_option('kopa_theme_options_left_header_description'))); ?>"> <i class="fa fa-phone-square" style=" font-size: 30px;padding-top: 10px;"></i> </a></p> 
  
  
  </div>

                                </div><!--top=box-->
                            </div><!--span12-->
                        </div><!--row-fluid-->
                    </div><!--wrapper-->
                </div><!--header-top-->

<?php if (!is_home() && !is_front_page()) { ?>
                <div id="header-bottom" class="<?php
                if ('enable' === get_option('kopa_theme_options_sticky_menu_status', 'enable')) {
                    echo 'cbp-af-header';
                }
                ?>">
                    <div class="wrapper">
                        <div class="row-fluid">
                            <div class="span12 clearfix">
                                <div id="logo-image" class="<?php echo ( display_header_text() && get_header_image() ) ? 'kp-text-logo' : ''; ?>">
                                    <?php if (get_header_image()) { ?>
                                        <a href="<?php echo esc_url(home_url()); ?>">
                                            <img src="<?php header_image(); ?>" alt="<?php bloginfo('name'); ?> <?php _e('Logo', kopa_get_domain()); ?>">
                                        </a>
                                    <?php } ?>
                                    <h1 class="site-title"><a href="<?php echo esc_url(home_url()); ?>"><?php bloginfo('name'); ?></a></h1>
                                </div><!--logo-image-->
                                <nav id="main-nav">
                                    <?php
                                    if (has_nav_menu('main-nav')) {

                                        wp_nav_menu(array(
                                            'theme_location' => 'main-nav',
                                            'container' => '',
                                            'items_wrap' => '<ul id="main-menu" class="clearfix">%3$s</ul>',
                                            'walker' => new kopa_main_menu()
                                        ));

                                        wp_nav_menu(array(
                                            'theme_location' => 'main-nav',
                                            'container' => 'div',
                                            'container_id' => 'mobile-menu',
                                            'items_wrap' => '<span>' . __('Menu', kopa_get_domain()) . '</span><ul id="toggle-view-menu">%3$s</ul>',
                                            'walker' => new kopa_mobile_menu()
                                        ));
                                    }
                                    ?>
                                </nav><!--main-nav-->
                            </div><!--span12-->
                        </div><!--row-fluid-->
                    </div><!--wrapper-->
                </div><!--header-bottom-->
                <?php  } ?>
            </header><!--page-header-->