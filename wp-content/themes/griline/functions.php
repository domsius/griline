<?php
if (!defined('ABSPATH')) exit;

// Include Bootstrap Nav Walker
require_once get_template_directory() . '/inc/class-bootstrap-5-nav-walker.php';

// Include Menu Admin Interface
require_once get_template_directory() . '/inc/menu-admin.php';

// Theme Setup
function griline_setup() {
    // Add default posts and comments RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Let WordPress manage the document title
    add_theme_support('title-tag');

    // Add theme support for custom logos
    add_theme_support('custom-logo');
    
    // Enable support for Post Thumbnails on posts and pages
    add_theme_support('post-thumbnails');

    // Register nav menus
    register_nav_menus(array(
        'primary' => esc_html__('Primary Menu', 'griline'),
        'footer' => esc_html__('Footer Menu', 'griline'),
    ));

    // Add theme support for selective refresh for widgets
    add_theme_support('customize-selective-refresh-widgets');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');
}
add_action('after_setup_theme', 'griline_setup');

// Add Google Tag Manager code to <head>
function griline_google_tag_manager_head() {
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-TP49PBN8');</script>
    <!-- End Google Tag Manager -->
    <?php
}
add_action('wp_head', 'griline_google_tag_manager_head', 1);

// Add Google Tag Manager noscript code right after <body>
function griline_google_tag_manager_body() {
    ?>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TP49PBN8"
    height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <?php
}
add_action('wp_body_open', 'griline_google_tag_manager_body', 1);

// Add Facebook Pixel code to <head>
function griline_facebook_pixel_head() {
    ?>
    <!-- Meta Pixel Code -->
    <script>
    !function(f,b,e,v,n,t,s)
    {if(f.fbq)return;n=f.fbq=function(){n.callMethod?
    n.callMethod.apply(n,arguments):n.queue.push(arguments)};
    if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
    n.queue=[];t=b.createElement(e);t.async=!0;
    t.src=v;s=b.getElementsByTagName(e)[0];
    s.parentNode.insertBefore(t,s)}(window, document,'script',
    'https://connect.facebook.net/en_US/fbevents.js');
    fbq('init', '2237871520001668');
    fbq('track', 'PageView');
    </script>
    <!-- End Meta Pixel Code -->
    <?php
}
add_action('wp_head', 'griline_facebook_pixel_head', 1);

// Add Facebook Pixel noscript code right after <body>
function griline_facebook_pixel_body() {
    ?>
    <!-- Meta Pixel Code (noscript) -->
    <noscript><img height="1" width="1" style="display:none"
    src="https://www.facebook.com/tr?id=2237871520001668&ev=PageView&noscript=1"
    /></noscript>
    <!-- End Meta Pixel Code (noscript) -->
    <?php
}
add_action('wp_body_open', 'griline_facebook_pixel_body', 2);

// Enqueue scripts and styles
function griline_scripts() {
    $version = time();

    wp_enqueue_style('bootstrap',  '/wp-content/themes/griline/assets/vendor/bootstrap/css/bootstrap.min.css', array(), '5.3.0');
    wp_enqueue_style('font-awesome',  '/wp-content/themes/griline/assets/vendor/font-awesome/css/all.min.css', array(), '6.4.0');
    wp_enqueue_style('nice-select',  '/wp-content/themes/griline/assets/vendor/nice-select/css/nice-select.css', array(), '1.0.0');
    wp_enqueue_style('magnific-popup',  '/wp-content/themes/griline/assets/vendor/magnific-popup/css/magnific-popup.css', array(), '1.1.0');
    wp_enqueue_style('slick',  '/wp-content/themes/griline/assets/vendor/slick/css/slick.css', array(), '1.8.1');
    wp_enqueue_style('odometer',  '/wp-content/themes/griline/assets/vendor/odometer/css/odometer.css', array(), '0.4.8');
    wp_enqueue_style('animate',  '/wp-content/themes/griline/assets/vendor/animate/animate.css', array(), '4.1.1');
    wp_enqueue_style('griline-style', '/wp-content/themes/griline/assets/css/style.css', array(), $version);
    wp_enqueue_script('jquery');
    wp_enqueue_script('bootstrap',  '/wp-content/themes/griline/assets/vendor/bootstrap/js/bootstrap.bundle.min.js', array('jquery'), '5.3.0', true);
    wp_enqueue_script('nice-select',  '/wp-content/themes/griline/assets/vendor/nice-select/js/jquery.nice-select.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('magnific-popup',  '/wp-content/themes/griline/assets/vendor/magnific-popup/js/jquery.magnific-popup.min.js', array('jquery'), '1.1.0', true);
    wp_enqueue_script('slick',  '/wp-content/themes/griline/assets/vendor/slick/js/slick.js', array('jquery'), '1.8.1', true);
    wp_enqueue_script('odometer',  '/wp-content/themes/griline/assets/vendor/odometer/js/odometer.min.js', array('jquery'), '0.4.8', true);
    wp_enqueue_script('viewport-js',  '/wp-content/themes/griline/assets/vendor/viewport-js/viewport.jquery.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('wow',  '/wp-content/themes/griline/assets/vendor/wow/wow.min.js', array('jquery'), '1.1.3', true);
    wp_enqueue_script('griline-main',  '/wp-content/themes/griline/assets/js/main.js', array('jquery', 'slick', 'odometer', 'griline-plugin'), '1.0.0', true);
    wp_enqueue_script('griline-plugin',  '/wp-content/themes/griline/assets/js/plugin.js', array('jquery', 'slick', 'odometer'), '1.0.0', true);
    wp_enqueue_script('griline-navigation',  '/wp-content/themes/griline/assets/js/navigation.js', array('jquery', 'slick', 'odometer', 'griline-plugin'), '1.0.0', true);
}
add_action('wp_enqueue_scripts', 'griline_scripts');

// Add social media links to customizer
function griline_customize_register($wp_customize) {
    // Add section for social media links
    $wp_customize->add_section('griline_social_links', array(
        'title'    => esc_html__('Social Media Links', 'griline'),
        'priority' => 30,
    ));

    // Add settings for social media links
    $social_platforms = array(
        'facebook'  => esc_html__('Facebook URL', 'griline'),
        'instagram' => esc_html__('Instagram URL', 'griline'),
    );

    foreach ($social_platforms as $platform => $label) {
        $wp_customize->add_setting('griline_' . $platform . '_url', array(
            'default'           => '#',
            'sanitize_callback' => 'esc_url_raw',
        ));

        $wp_customize->add_control('griline_' . $platform . '_url', array(
            'label'    => $label,
            'section'  => 'griline_social_links',
            'type'     => 'url',
        ));
    }
}
add_action('customize_register', 'griline_customize_register');

// Handle newsletter form submission
function griline_handle_newsletter_submission() {
    if (isset($_POST['newsletter__email'])) {
        $email = sanitize_email($_POST['newsletter__email']);
        if (is_email($email)) {
            // Here you can add your newsletter subscription logic
            // For example, sending to a mailing service API
            wp_redirect(add_query_arg('newsletter_status', 'success', wp_get_referer()));
        } else {
            wp_redirect(add_query_arg('newsletter_status', 'error', wp_get_referer()));
        }
    }
}
add_action('admin_post_newsletter_subscription', 'griline_handle_newsletter_submission');
add_action('admin_post_nopriv_newsletter_subscription', 'griline_handle_newsletter_submission'); 

// Enable SVG uploads
function griline_enable_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['svgz'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'griline_enable_svg_upload');

// Fix SVG thumbnail display in media library
function griline_fix_svg_thumbnail_display($response, $attachment, $meta) {
    if ($response['mime'] === 'image/svg+xml') {
        $response['sizes'] = [
            'full' => [
                'url' => $response['url'],
                'width' => $response['width'],
                'height' => $response['height'],
                'orientation' => $response['width'] > $response['height'] ? 'landscape' : 'portrait'
            ]
        ];
    }
    return $response;
}
add_filter('wp_prepare_attachment_for_js', 'griline_fix_svg_thumbnail_display', 10, 3);