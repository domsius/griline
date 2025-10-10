<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <!-- required meta -->
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- #favicon -->
    <link rel="shortcut icon" href="/wp-content/themes/griline/assets/images/favicon.png" type="image/x-icon">
    <!-- #keywords -->
    <meta name="keywords" content="">
    <!-- #description -->
    <meta name="description" content="">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

    <!-- ==== header start ==== -->
    <header class="header header--secondary">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a href="<?php echo esc_url(home_url('/')); ?>" class="navbar-brand d-flex align-items-center gap-2">
                    <img class="custom-logo" src="/wp-content/themes/griline/assets/images/griline-logo-black.svg" alt="Griline">
                </a>
                <div class="navbar-out order-2 order-md-3">
                    <div class="nav-group-btn">
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