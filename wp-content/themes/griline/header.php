<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <!-- required meta -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- #favicon -->
    <?php 
    $favicon = get_theme_mod('site_icon');
    if ($favicon) : 
        echo wp_site_icon();
    else :
        $custom_favicon = get_theme_mod('griline_favicon');
        if ($custom_favicon) : ?>
            <link rel="shortcut icon" href="<?php echo esc_url($custom_favicon); ?>" type="image/x-icon">
        <?php else : ?>
            <link rel="shortcut icon" href="<?php echo esc_url(get_template_directory_uri() . '/assets/images/griline.jpg'); ?>" type="image/x-icon">
        <?php endif; 
    endif; ?>
    <!-- #title -->
    <meta name="keywords" content="">
    <!-- #description -->
    <meta name="description" content="">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- ==== header start ==== -->
    <header class="header">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand d-flex align-items-center gap-2">
                    <?php
                    if (has_custom_logo()) :
                        the_custom_logo();
                    else :
                        echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/favicon.png') . '" alt="' . esc_attr(get_bloginfo('name')) . '">';
                        echo '<img src="' . esc_url(get_template_directory_uri() . '/assets/images/logo-light.png') . '" alt="' . esc_attr(get_bloginfo('name')) . '" class="d-none d-xxl-block">';
                    endif;
                    ?>
                </a>
                <div class="navbar-out order-2 order-md-3">
                    <div class="nav-group-btn">
                        <?php if (class_exists('WooCommerce')) : ?>
                            <a href="<?php echo esc_url(wc_get_cart_url()); ?>" class="cart">
                                <i class="fa-solid fa-basket-shopping"></i>
                                <span class="cart-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            </a>
                        <?php endif; ?>
                        <a href="tel:+37061114446" class="button d-none d-sm-block"><?php esc_html_e('Susisiekti', 'griline'); ?></a>
                    </div>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#primaryNav"
                        aria-controls="primaryNav" aria-expanded="false" aria-label="Toggle Primary Nav">
                        <span class="icon-bar top-bar"></span>
                        <span class="icon-bar middle-bar"></span>
                        <span class="icon-bar bottom-bar"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse order-3 order-lg-2 justify-content-center" id="primaryNav">
                    <?php
                    wp_nav_menu(array(
                        'theme_location' => 'primary',
                        'menu_class'     => 'navbar-nav',
                        'container'      => false,
                        'fallback_cb'    => false,
                        'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                        'walker'         => new Bootstrap_5_Nav_Walker()
                    ));
                    ?>
                </div>
            </div>
        </nav>
    </header>
    <!-- ==== #header end ==== -->