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

    // Enqueue Bootstrap Datepicker CSS
    wp_enqueue_style('bootstrap-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css', array(), '1.9.0', 'all');

    
// Enqueue Bootstrap Datepicker JS
    wp_enqueue_script('bootstrap-datepicker', 'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js', array('jquery'), '1.9.0', true);

    // Enqueue Bootstrap Datepicker Language Files
    $current_lang = apply_filters('wpml_current_language', NULL);
    if ($current_lang && $current_lang !== 'en') {
        wp_enqueue_script(
            'bootstrap-datepicker-lang-' . $current_lang,
            'https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.' . $current_lang . '.min.js',
            array('bootstrap-datepicker'),
            '1.9.0',
            true
        );
    }

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


// ============================================
// LOYALTY CARD FUNCTIONALITY
// ============================================

// Handle custom loyalty form submission
function handle_loyalty_form_submission() {
    // Rate limiting - max 5 form submissions per hour per IP
    $ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'loyalty_form_submit_' . md5($ip);
    $submissions = get_transient($rate_limit_key);

    if ($submissions === false) {
        set_transient($rate_limit_key, 1, 3600); // 1 hour
    } elseif ($submissions >= 5) {
        wp_die('Too many submissions. Please try again later.');
    } else {
        set_transient($rate_limit_key, $submissions + 1, 3600);
    }

    // Verify nonce with lenient checking for cached pages
    $nonce_valid = isset($_POST['loyalty_nonce']) && wp_verify_nonce($_POST['loyalty_nonce'], 'loyalty_form_submit');

    // Only enforce nonce for logged-in users (due to caching)
    if (!$nonce_valid && is_user_logged_in()) {
        wp_die('Security check failed. Please refresh the page and try again.');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_members';

    // Get form language
    $current_lang = isset($_POST['form_language']) ? sanitize_text_field($_POST['form_language']) : 'lt';

    // Validate card number
    $card_number = sanitize_text_field($_POST['korteles-nr']);
    $card_validation = validate_loyalty_card($card_number);

    if (!$card_validation['valid']) {
        error_log('Loyalty Form: Invalid card number: ' . $card_number);
        wp_redirect(add_query_arg('loyalty_error', 'invalid_card', wp_get_referer()));
        exit;
    }

    // Prepare data
    $data = array(
        'first_name' => sanitize_text_field($_POST['vardas']),
        'last_name' => sanitize_text_field($_POST['pavarde']),
        'birth_date' => sanitize_text_field($_POST['gimimo-data']),
        'city' => sanitize_text_field($_POST['miestas']),
        'email' => sanitize_email($_POST['el-pastas']),
        'phone' => sanitize_text_field($_POST['tel-numeris']),
        'card_number' => $card_number,
        'marketing_consent' => isset($_POST['sutinku']) ? 1 : 0,
        'language' => $current_lang,
        'created_at' => current_time('mysql')
    );

    // Insert into loyalty members table
    $result = $wpdb->insert($table_name, $data);

    if ($result === false) {
        error_log('Loyalty Form: Database insert failed. Error: ' . $wpdb->last_error);
        wp_redirect(add_query_arg('loyalty_error', '1', wp_get_referer()));
        exit;
    }

    error_log('Loyalty Form: Successfully saved to database. ID: ' . $wpdb->insert_id);

    // Save to CFDB7 format (if plugin is active)
    save_to_cfdb7($data);

    // Send welcome email to customer
    send_loyalty_welcome_email($data);

    // Send notification to admin
    send_admin_notification($data);

    // Mark card as used
    $member_id = $wpdb->insert_id;
    $card_numbers_table = $wpdb->prefix . 'loyalty_card_numbers';
    $wpdb->update(
        $card_numbers_table,
        array(
            'is_used' => 1,
            'used_by_member_id' => $member_id,
            'used_at' => current_time('mysql')
        ),
        array('card_number' => $card_number),
        array('%d', '%d', '%s'),
        array('%s')
    );

    error_log('Loyalty Form: Card ' . $card_number . ' marked as used by member #' . $member_id);

    // Redirect with success message
    wp_redirect(add_query_arg('loyalty_success', '1', wp_get_referer()));
    exit;
}
add_action('admin_post_submit_loyalty_form', 'handle_loyalty_form_submission');
add_action('admin_post_nopriv_submit_loyalty_form', 'handle_loyalty_form_submission');

// AJAX: Validate loyalty card number
function validate_loyalty_card_ajax() {
    // Basic rate limiting - max 20 requests per minute per IP
    $ip = $_SERVER['REMOTE_ADDR'];
    $rate_limit_key = 'loyalty_card_validation_' . md5($ip);
    $requests = get_transient($rate_limit_key);

    if ($requests === false) {
        set_transient($rate_limit_key, 1, 60); // 60 seconds
    } elseif ($requests > 20) {
        wp_send_json_error(array(
            'message' => 'Too many requests. Please wait a moment.'
        ));
        return;
    } else {
        set_transient($rate_limit_key, $requests + 1, 60);
    }

    // Verify nonce with more lenient checking for cached pages
    $nonce_valid = isset($_POST['security']) && wp_verify_nonce($_POST['security'], 'loyalty_card_validation');

    // For non-logged-in users (nopriv), be more lenient due to caching
    // This is safe because we're only doing read operations (checking card validity)
    if (!$nonce_valid && is_user_logged_in()) {
        wp_send_json_error(array(
            'message' => 'Security check failed. Please refresh the page.'
        ));
        return;
    }

    if (!isset($_POST['card_number'])) {
        wp_send_json_error(array(
            'message' => 'Card number is required.'
        ));
        return;
    }

    $card_number = sanitize_text_field($_POST['card_number']);
    $current_lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : 'lt';

    // Validate card number exists and is available
    $result = validate_loyalty_card($card_number);

    if ($result['valid']) {
        wp_send_json_success(array(
            'message' => get_card_validation_message('valid', $current_lang)
        ));
    } else {
        wp_send_json_error(array(
            'message' => get_card_validation_message($result['error_type'], $current_lang)
        ));
    }
}
add_action('wp_ajax_validate_loyalty_card', 'validate_loyalty_card_ajax');
add_action('wp_ajax_nopriv_validate_loyalty_card', 'validate_loyalty_card_ajax');

// Helper: Validate loyalty card number
function validate_loyalty_card($card_number) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_card_numbers';

    // Check if card exists
    $card = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE card_number = %s",
        $card_number
    ));

    if (!$card) {
        return array('valid' => false, 'error_type' => 'invalid');
    }

    if ($card->is_used) {
        return array('valid' => false, 'error_type' => 'already_used');
    }

    return array('valid' => true);
}

// Helper: Get card validation messages by language
function get_card_validation_message($type, $lang = 'lt') {
    $messages = array(
        'valid' => array(
            'lt' => 'Lojalumo kortelės numeris galioja ✓',
            'en' => 'Loyalty card number is valid ✓',
            'ru' => 'Номер карты лояльности действителен ✓'
        ),
        'invalid' => array(
            'lt' => 'Neteisingas lojalumo kortelės numeris',
            'en' => 'Invalid loyalty card number',
            'ru' => 'Неверный номер карты лояльности'
        ),
        'already_used' => array(
            'lt' => 'Ši kortelė jau užregistruota',
            'en' => 'This card is already registered',
            'ru' => 'Эта карта уже зарегистрирована'
        ),
        'required' => array(
            'lt' => 'Prašome įvesti lojalumo kortelės numerį',
            'en' => 'Please enter loyalty card number',
            'ru' => 'Пожалуйста, введите номер карты лояльности'
        )
    );

    return isset($messages[$type][$lang]) ? $messages[$type][$lang] : $messages[$type]['lt'];
}

// Save form data to CFDB7 format
function save_to_cfdb7($data) {
    global $wpdb;

    // Check if CFDB7 table exists
    $cfdb_table = $wpdb->prefix . 'db7_forms';
    if ($wpdb->get_var("SHOW TABLES LIKE '$cfdb_table'") != $cfdb_table) {
        error_log('CFDB7: Table does not exist');
        return; // CFDB7 not installed
    }

    // Get the Contact Form 7 form ID (if you want to associate it)
    // Or use 0 for standalone forms
    $form_post_id = 0; // You can change this to your CF7 form ID if needed

    // Prepare form data array matching CFDB7 structure
    $form_fields = array(
        'vardas' => $data['first_name'],
        'pavarde' => $data['last_name'],
        'gimimo-data' => $data['birth_date'],
        'miestas' => $data['city'],
        'el-pastas' => $data['email'],
        'tel-numeris' => $data['phone'],
        'korteles-nr' => $data['card_number'],
        'sutinku' => $data['marketing_consent'] ? 'Yes' : 'No',
        'form-title' => 'Lojalumas',
        'form-language' => $data['language']
    );

    // CFDB7 stores data in a specific format
    $form_data = array(
        'form_post_id' => $form_post_id,
        'form_value' => serialize($form_fields),
        'form_date' => current_time('mysql')
    );

    $result = $wpdb->insert($cfdb_table, $form_data);

    if ($result === false) {
        error_log('CFDB7: Insert failed. Error: ' . $wpdb->last_error);
    } else {
        error_log('CFDB7: Successfully saved. ID: ' . $wpdb->insert_id);
    }
}

// Send admin notification email
function send_admin_notification($member_data) {
    $admin_email = get_option('admin_email');
    $lang = $member_data['language'];

    $subject = 'Nauja lojalumo kortelės registracija / New Loyalty Card Registration';

    $message = '
    <html>
    <body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
        <div style="max-width: 600px; margin: 0 auto; padding: 20px; background-color: #f9f9f9; border-radius: 5px;">
            <h2 style="color: #ee4424; margin-bottom: 20px;">Nauja lojalumo kortelės registracija</h2>

            <table cellpadding="5" style="width: 100%; background-color: #fff; border-radius: 5px; padding: 15px;">
                <tr>
                    <td style="font-weight: bold; color: #666; padding: 8px;">Vardas:</td>
                    <td style="padding: 8px;">' . esc_html($member_data['first_name']) . '</td>
                </tr>
                <tr style="background-color: #f9f9f9;">
                    <td style="font-weight: bold; color: #666; padding: 8px;">Pavardė:</td>
                    <td style="padding: 8px;">' . esc_html($member_data['last_name']) . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #666; padding: 8px;">El. paštas:</td>
                    <td style="padding: 8px;">' . esc_html($member_data['email']) . '</td>
                </tr>
                <tr style="background-color: #f9f9f9;">
                    <td style="font-weight: bold; color: #666; padding: 8px;">Tel. numeris:</td>
                    <td style="padding: 8px;">' . esc_html($member_data['phone']) . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #666; padding: 8px;">Gimimo data:</td>
                    <td style="padding: 8px;">' . esc_html($member_data['birth_date']) . '</td>
                </tr>
                <tr style="background-color: #f9f9f9;">
                    <td style="font-weight: bold; color: #666; padding: 8px;">Miestas:</td>
                    <td style="padding: 8px;">' . esc_html($member_data['city']) . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #666; padding: 8px;">Kortelės nr.:</td>
                    <td style="padding: 8px;">' . esc_html($member_data['card_number']) . '</td>
                </tr>
                <tr style="background-color: #f9f9f9;">
                    <td style="font-weight: bold; color: #666; padding: 8px;">Sutikimas gauti informaciją:</td>
                    <td style="padding: 8px;">' . ($member_data['marketing_consent'] ? 'Taip / Yes' : 'Ne / No') . '</td>
                </tr>
                <tr>
                    <td style="font-weight: bold; color: #666; padding: 8px;">Kalba:</td>
                    <td style="padding: 8px;">' . strtoupper($lang) . '</td>
                </tr>
            </table>

            <p style="margin-top: 20px; font-size: 12px; color: #999;">
                Registracija įvyko: ' . current_time('Y-m-d H:i:s') . '
            </p>
        </div>
    </body>
    </html>
    ';

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Grilinė <info@griline.lt>'
    );

    wp_mail($admin_email, $subject, $message, $headers);
}

// Send welcome email with custom template
function send_loyalty_welcome_email($member_data) {
    $lang = $member_data['language'];

    // Get email template
    ob_start();
    include(get_template_directory() . '/email-templates/loyalty-welcome.php');
    $message = ob_get_clean();

    // Subject based on language
    $subjects = array(
        'lt' => 'Sveiki prisijungę prie Grilinė lojalumo programos!',
        'en' => 'Welcome to Grilinė Loyalty Program!',
        'ru' => 'Добро пожаловать в программу лояльности Grilinė!'
    );

    $subject = isset($subjects[$lang]) ? $subjects[$lang] : $subjects['lt'];

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Grilinė <info@griline.lt>',
        'Reply-To: info@griline.lt'
    );

    $sent = wp_mail($member_data['email'], $subject, $message, $headers);

    if ($sent) {
        error_log('Loyalty Welcome Email: Sent to ' . $member_data['email']);
    } else {
        error_log('Loyalty Welcome Email: Failed to send to ' . $member_data['email']);
    }

    return $sent;
}

// Create custom table for loyalty members
function create_loyalty_members_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_members';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        first_name varchar(100) NOT NULL,
        last_name varchar(100) NOT NULL,
        birth_date date NOT NULL,
        city varchar(100) NOT NULL,
        email varchar(100) NOT NULL,
        phone varchar(50) NOT NULL,
        card_number varchar(50) NOT NULL,
        marketing_consent tinyint(1) DEFAULT 0,
        language varchar(10) DEFAULT 'lt',
        birthday_email_sent_year int(4) DEFAULT 0,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        KEY birth_date (birth_date),
        KEY email (email)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);
}


// Create loyalty email templates table
function create_loyalty_email_templates_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_email_templates';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        template_type varchar(50) NOT NULL,
        language varchar(10) NOT NULL,
        subject varchar(255) NOT NULL,
        content longtext NOT NULL,
        is_active tinyint(1) DEFAULT 1,
        updated_at datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        PRIMARY KEY (id),
        UNIQUE KEY template_lang (template_type, language)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Populate with default values if empty
    populate_default_email_templates();
}

// Populate default email template values
function populate_default_email_templates() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_email_templates';

    // Check if already populated
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    if ($count > 0) {
        return;
    }

    // Birthday template defaults
    $birthday_templates = array(
        'lt' => array(
            'subject' => 'Su gimimo diena, {first_name}! 🎉',
            'content' => json_encode(array(
                'title' => 'Su gimimo diena!',
                'greeting' => 'Mielas(a) {first_name},',
                'message' => 'Šią ypatingą dieną norime palinkėti Jums daug džiaugsmo, sveikatos ir gerų emocijų!',
                'discount_intro' => 'Kaip mūsų ištikimam klientui, dovanojame Jums',
                'discount' => '10% nuolaidą',
                'discount_text' => 'visam meniu!',
                'validity' => 'Nuolaida galioja visą gimtadienio mėnesį. Parodykite šį laišką užsakymo metu.',
                'wishes' => 'Geriausių linkėjimų Jūsų gimtadienio proga!',
                'image_1' => '',
                'image_2' => '',
                'image_3' => '',
                'regards' => 'Su pagarba,',
                'team' => 'Grilinė komanda'
            ))
        ),
        'en' => array(
            'subject' => 'Happy Birthday, {first_name}! 🎉',
            'content' => json_encode(array(
                'title' => 'Happy Birthday!',
                'greeting' => 'Dear {first_name},',
                'message' => 'On this special day, we want to wish you lots of joy, health and good emotions!',
                'discount_intro' => 'As our loyal customer, we are giving you a',
                'discount' => '10% discount',
                'discount_text' => 'on our entire menu!',
                'validity' => 'The discount is valid for the entire birthday month. Show this email when ordering.',
                'wishes' => 'Best wishes on your birthday!',
                'image_1' => '',
                'image_2' => '',
                'image_3' => '',
                'regards' => 'Best regards,',
                'team' => 'Grilinė team'
            ))
        ),
        'ru' => array(
            'subject' => 'С днём рождения, {first_name}! 🎉',
            'content' => json_encode(array(
                'title' => 'С днём рождения!',
                'greeting' => 'Дорогой(ая) {first_name},',
                'message' => 'В этот особенный день мы желаем вам много радости, здоровья и хороших эмоций!',
                'discount_intro' => 'Как нашему постоянному клиенту, мы дарим вам',
                'discount' => 'скидку 10%',
                'discount_text' => 'на всё меню!',
                'validity' => 'Скидка действительна в течение всего месяца вашего дня рождения. Покажите это письмо при заказе.',
                'wishes' => 'С наилучшими пожеланиями в ваш день рождения!',
                'image_1' => '',
                'image_2' => '',
                'image_3' => '',
                'regards' => 'С уважением,',
                'team' => 'Команда Grilinė'
            ))
        )
    );

    // Welcome template defaults
    $welcome_templates = array(
        'lt' => array(
            'subject' => 'Sveiki prisijungę prie Grilinė lojalumo programos!',
            'content' => json_encode(array(
                'greeting' => 'Sveiki, {first_name}!',
                'welcome' => 'Džiaugiamės, kad prisijungėte prie mūsų lojalumo programos!',
                'card_title' => 'Jūsų lojalumo kortelės numeris:',
                'benefits_title' => 'Kaip lojalumo programos narys, gausite:',
                'benefit_1' => '10% nuolaidą gimtadienio mėnesį',
                'benefit_2' => 'Specialius pasiūlymus el. paštu',
                'benefit_3' => 'Pirmumo teisę dalyvauti VIP renginiuose',
                'thanks' => 'Ačiū, kad esate su mumis!',
                'image_1' => '',
                'image_2' => '',
                'image_3' => '',
                'regards' => 'Su pagarba,',
                'team' => 'Grilinė komanda'
            ))
        ),
        'en' => array(
            'subject' => 'Welcome to Grilinė Loyalty Program!',
            'content' => json_encode(array(
                'greeting' => 'Hello, {first_name}!',
                'welcome' => 'We are delighted that you have joined our loyalty program!',
                'card_title' => 'Your loyalty card number:',
                'benefits_title' => 'As a loyalty program member, you will receive:',
                'benefit_1' => '10% discount during your birthday month',
                'benefit_2' => 'Special offers via email',
                'benefit_3' => 'Priority access to VIP events',
                'thanks' => 'Thank you for being with us!',
                'image_1' => '',
                'image_2' => '',
                'image_3' => '',
                'regards' => 'Best regards,',
                'team' => 'Grilinė team'
            ))
        ),
        'ru' => array(
            'subject' => 'Добро пожаловать в программу лояльности Grilinė!',
            'content' => json_encode(array(
                'greeting' => 'Здравствуйте, {first_name}!',
                'welcome' => 'Мы рады, что вы присоединились к нашей программе лояльности!',
                'card_title' => 'Номер вашей карты лояльности:',
                'benefits_title' => 'Как участник программы лояльности, вы получите:',
                'benefit_1' => 'Скидку 10% в месяц вашего дня рождения',
                'benefit_2' => 'Специальные предложения по электронной почте',
                'benefit_3' => 'Приоритетный доступ к VIP-мероприятиям',
                'thanks' => 'Спасибо, что вы с нами!',
                'image_1' => '',
                'image_2' => '',
                'image_3' => '',
                'regards' => 'С уважением,',
                'team' => 'Команда Grilinė'
            ))
        )
    );

    // Insert birthday templates
    foreach ($birthday_templates as $lang => $data) {
        $wpdb->insert(
            $table_name,
            array(
                'template_type' => 'birthday',
                'language' => $lang,
                'subject' => $data['subject'],
                'content' => $data['content'],
                'is_active' => 1
            ),
            array('%s', '%s', '%s', '%s', '%d')
        );
    }

    // Insert welcome templates
    foreach ($welcome_templates as $lang => $data) {
        $wpdb->insert(
            $table_name,
            array(
                'template_type' => 'welcome',
                'language' => $lang,
                'subject' => $data['subject'],
                'content' => $data['content'],
                'is_active' => 1
            ),
            array('%s', '%s', '%s', '%s', '%d')
        );
    }
}


// Helper function to get email template content from database
function get_email_template_content($type, $lang) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_email_templates';

    $template = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE template_type = %s AND language = %s AND is_active = 1",
        $type,
        $lang
    ));

    // Fallback to defaults if not found
    if (!$template) {
        return get_default_email_template($type, $lang);
    }

    return $template;
}

// Helper function to update email template content
function update_email_template_content($type, $lang, $subject, $content_array) {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_email_templates';

    $content_json = json_encode($content_array);

    // Use INSERT ... ON DUPLICATE KEY UPDATE for MySQL
    $result = $wpdb->replace(
        $table_name,
        array(
            'template_type' => $type,
            'language' => $lang,
            'subject' => $subject,
            'content' => $content_json,
            'is_active' => 1
        ),
        array('%s', '%s', '%s', '%s', '%d')
    );

    return $result !== false;
}

// Helper function to get default email template (hardcoded fallback)
function get_default_email_template($type, $lang) {
    $defaults = array(
        'birthday' => array(
            'lt' => array(
                'subject' => 'Su gimimo diena, {first_name}! 🎉',
                'content' => json_encode(array(
                    'title' => 'Su gimimo diena!',
                    'greeting' => 'Mielas(a) {first_name},',
                    'message' => 'Šią ypatingą dieną norime palinkėti Jums daug džiaugsmo, sveikatos ir gerų emocijų!',
                    'discount_intro' => 'Kaip mūsų ištikimam klientui, dovanojame Jums',
                    'discount' => '10% nuolaidą',
                    'discount_text' => 'visam meniu!',
                    'validity' => 'Nuolaida galioja visą gimtadienio mėnesį. Parodykite šį laišką užsakymo metu.',
                    'wishes' => 'Geriausių linkėjimų Jūsų gimtadienio proga!',
                    'regards' => 'Su pagarba,',
                    'team' => 'Grilinė komanda'
                ))
            ),
            'en' => array(
                'subject' => 'Happy Birthday, {first_name}! 🎉',
                'content' => json_encode(array(
                    'title' => 'Happy Birthday!',
                    'greeting' => 'Dear {first_name},',
                    'message' => 'On this special day, we want to wish you lots of joy, health and good emotions!',
                    'discount_intro' => 'As our loyal customer, we are giving you a',
                    'discount' => '10% discount',
                    'discount_text' => 'on our entire menu!',
                    'validity' => 'The discount is valid for the entire birthday month. Show this email when ordering.',
                    'wishes' => 'Best wishes on your birthday!',
                    'regards' => 'Best regards,',
                    'team' => 'Grilinė team'
                ))
            ),
            'ru' => array(
                'subject' => 'С днём рождения, {first_name}! 🎉',
                'content' => json_encode(array(
                    'title' => 'С днём рождения!',
                    'greeting' => 'Дорогой(ая) {first_name},',
                    'message' => 'В этот особенный день мы желаем вам много радости, здоровья и хороших эмоций!',
                    'discount_intro' => 'Как нашему постоянному клиенту, мы дарим вам',
                    'discount' => 'скидку 10%',
                    'discount_text' => 'на всё меню!',
                    'validity' => 'Скидка действительна в течение всего месяца вашего дня рождения. Покажите это письмо при заказе.',
                    'wishes' => 'С наилучшими пожеланиями в ваш день рождения!',
                    'regards' => 'С уважением,',
                    'team' => 'Команда Grilinė'
                ))
            )
        ),
        'welcome' => array(
            'lt' => array(
                'subject' => 'Sveiki prisijungę prie Grilinė lojalumo programos!',
                'content' => json_encode(array(
                    'greeting' => 'Sveiki, {first_name}!',
                    'welcome' => 'Džiaugiamės, kad prisijungėte prie mūsų lojalumo programos!',
                    'card_title' => 'Jūsų lojalumo kortelės numeris:',
                    'benefits_title' => 'Kaip lojalumo programos narys, gausite:',
                    'benefit_1' => '10% nuolaidą gimtadienio mėnesį',
                    'benefit_2' => 'Specialius pasiūlymus el. paštu',
                    'benefit_3' => 'Pirmumo teisę dalyvauti VIP renginiuose',
                    'thanks' => 'Ačiū, kad esate su mumis!',
                    'regards' => 'Su pagarba,',
                    'team' => 'Grilinė komanda'
                ))
            ),
            'en' => array(
                'subject' => 'Welcome to Grilinė Loyalty Program!',
                'content' => json_encode(array(
                    'greeting' => 'Hello, {first_name}!',
                    'welcome' => 'We are delighted that you have joined our loyalty program!',
                    'card_title' => 'Your loyalty card number:',
                    'benefits_title' => 'As a loyalty program member, you will receive:',
                    'benefit_1' => '10% discount during your birthday month',
                    'benefit_2' => 'Special offers via email',
                    'benefit_3' => 'Priority access to VIP events',
                    'thanks' => 'Thank you for being with us!',
                    'regards' => 'Best regards,',
                    'team' => 'Grilinė team'
                ))
            ),
            'ru' => array(
                'subject' => 'Добро пожаловать в программу лояльности Grilinė!',
                'content' => json_encode(array(
                    'greeting' => 'Здравствуйте, {first_name}!',
                    'welcome' => 'Мы рады, что вы присоединились к нашей программе лояльности!',
                    'card_title' => 'Номер вашей карты лояльности:',
                    'benefits_title' => 'Как участник программы лояльности, вы получите:',
                    'benefit_1' => 'Скидку 10% в месяц вашего дня рождения',
                    'benefit_2' => 'Специальные предложения по электронной почте',
                    'benefit_3' => 'Приоритетный доступ к VIP-мероприятиям',
                    'thanks' => 'Спасибо, что вы с нами!',
                    'regards' => 'С уважением,',
                    'team' => 'Команда Grilinė'
                ))
            )
        )
    );

    if (isset($defaults[$type][$lang])) {
        return (object) array_merge(
            array('id' => 0, 'template_type' => $type, 'language' => $lang, 'is_active' => 1),
            $defaults[$type][$lang]
        );
    }

    return null;
}

// Helper function to reset email template to defaults
function reset_email_template($type, $lang) {
    $default = get_default_email_template($type, $lang);
    if ($default) {
        $content_array = json_decode($default->content, true);
        return update_email_template_content($type, $lang, $default->subject, $content_array);
    }
    return false;
}

// Create loyalty card numbers table
function create_loyalty_card_numbers_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_card_numbers';
    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE IF NOT EXISTS $table_name (
        id bigint(20) NOT NULL AUTO_INCREMENT,
        card_number varchar(50) NOT NULL,
        is_used tinyint(1) DEFAULT 0,
        used_by_member_id bigint(20) DEFAULT NULL,
        created_at datetime DEFAULT CURRENT_TIMESTAMP,
        used_at datetime DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY card_number (card_number),
        KEY is_used (is_used),
        KEY used_by_member_id (used_by_member_id)
    ) $charset_collate;";

    require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
    dbDelta($sql);

    // Populate initial card numbers if table is empty
    populate_initial_card_numbers();
}

// Populate initial loyalty card numbers (200901-201000)
function populate_initial_card_numbers() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_card_numbers';

    // Check if table already has data
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    if ($count > 0) {
        return; // Already populated
    }

    // Insert initial range: 000600 to 000700 (101 cards)
    $values = array();
    for ($i = 600; $i <= 700; $i++) {
        $card_number = str_pad($i, 6, '0', STR_PAD_LEFT); // Format as 000600, 000601, etc.
        $values[] = $wpdb->prepare("(%s, 0, NULL, %s, NULL)", $card_number, current_time('mysql'));
    }

    if (!empty($values)) {
        $sql = "INSERT INTO $table_name (card_number, is_used, used_by_member_id, created_at, used_at) VALUES ";
        $sql .= implode(', ', $values);
        $wpdb->query($sql);

        error_log('Loyalty Card Numbers: Populated 101 initial cards (000600-000700)');
    }
}

add_action('after_switch_theme', 'create_loyalty_email_templates_table');
add_action('after_switch_theme', 'create_loyalty_members_table');
add_action('after_switch_theme', 'create_loyalty_card_numbers_table');

// Run table creation on admin init if tables don't exist
add_action('admin_init', function() {
    global $wpdb;

    // Check loyalty members table
    $table_name = $wpdb->prefix . 'loyalty_members';
    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        create_loyalty_members_table();
    }

    // Check loyalty email templates table
    $templates_table = $wpdb->prefix . 'loyalty_email_templates';
    if ($wpdb->get_var("SHOW TABLES LIKE '$templates_table'") != $templates_table) {
        create_loyalty_email_templates_table();
    }

    // Check loyalty card numbers table
    $card_numbers_table = $wpdb->prefix . 'loyalty_card_numbers';
    if ($wpdb->get_var("SHOW TABLES LIKE '$card_numbers_table'") != $card_numbers_table) {
        create_loyalty_card_numbers_table();
    }
});

// Schedule daily birthday email check
function schedule_birthday_emails() {
    if (!wp_next_scheduled('send_birthday_emails_hook')) {
        wp_schedule_event(time(), 'daily', 'send_birthday_emails_hook');
    }
}
add_action('wp', 'schedule_birthday_emails');

// Send birthday emails
function send_birthday_emails() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_members';

    // Get today's date (month and day only)
    $today_month = date('m');
    $today_day = date('d');
    $current_year = date('Y');

    error_log("Birthday Check: Looking for birthdays on {$today_month}/{$today_day}");

    // Find members with birthday today who have marketing consent and haven't received email this year
    $members = $wpdb->get_results($wpdb->prepare(
        "SELECT * FROM $table_name
        WHERE MONTH(birth_date) = %d
        AND DAY(birth_date) = %d
        AND marketing_consent = 1
        AND (birthday_email_sent_year IS NULL OR birthday_email_sent_year < %d)",
        $today_month,
        $today_day,
        $current_year
    ));

    error_log("Birthday Check: Found " . count($members) . " members with birthdays today");

    foreach ($members as $member) {
        $email_sent = send_birthday_email($member);

        if ($email_sent) {
            // Update the year when birthday email was sent
            $wpdb->update(
                $table_name,
                array('birthday_email_sent_year' => $current_year),
                array('id' => $member->id)
            );
            error_log("Birthday Email: Sent to {$member->email}");
        } else {
            error_log("Birthday Email: Failed to send to {$member->email}");
        }
    }
}
add_action('send_birthday_emails_hook', 'send_birthday_emails');

// Manual trigger for testing birthday emails (URL: yoursite.com/?test_birthday_emails=1)
add_action('init', function() {
    if (isset($_GET['test_birthday_emails']) && current_user_can('manage_options')) {
        send_birthday_emails();
        wp_die('Birthday emails check completed. Check debug.log for results.');
    }
});

// Add admin menu for loyalty members
function add_loyalty_members_admin_menu() {
    // Main menu
    add_menu_page(
        'Loyalty Members',
        'Loyalty Members',
        'manage_options',
        'loyalty-members',
        'display_loyalty_members_page',
        'dashicons-id-alt',
        30
    );

    // Submenu - Email Templates
    add_submenu_page(
        'loyalty-members',
        'Email Templates',
        'Email Templates',
        'manage_options',
        'loyalty-email-templates',
        'display_loyalty_email_templates_page'
    );
}
add_action('admin_menu', 'add_loyalty_members_admin_menu');

// Display loyalty email templates admin page
function display_loyalty_email_templates_page() {
    // Security check
    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized access');
    }

    // Handle form submission
    if (isset($_POST['save_template']) && check_admin_referer('loyalty_email_template_save')) {
        $template_type = sanitize_text_field($_POST['template_type']);
        $language = sanitize_text_field($_POST['language']);
        $subject = sanitize_text_field($_POST['subject']);

        // Build content array based on template type
        $content_array = array();
        if ($template_type === 'birthday') {
            $content_array = array(
                'title' => sanitize_text_field($_POST['title']),
                'greeting' => sanitize_text_field($_POST['greeting']),
                'message' => sanitize_textarea_field($_POST['message']),
                'discount_intro' => sanitize_text_field($_POST['discount_intro']),
                'discount' => sanitize_text_field($_POST['discount']),
                'discount_text' => sanitize_text_field($_POST['discount_text']),
                'validity' => sanitize_textarea_field($_POST['validity']),
                'wishes' => sanitize_text_field($_POST['wishes']),
                'image_1' => esc_url_raw($_POST['image_1'] ?? ''),
                'image_2' => esc_url_raw($_POST['image_2'] ?? ''),
                'image_3' => esc_url_raw($_POST['image_3'] ?? ''),
                'regards' => sanitize_text_field($_POST['regards']),
                'team' => sanitize_text_field($_POST['team'])
            );
        } elseif ($template_type === 'welcome') {
            $content_array = array(
                'greeting' => sanitize_text_field($_POST['greeting']),
                'welcome' => sanitize_textarea_field($_POST['welcome']),
                'card_title' => sanitize_text_field($_POST['card_title']),
                'benefits_title' => sanitize_text_field($_POST['benefits_title']),
                'benefit_1' => sanitize_text_field($_POST['benefit_1']),
                'benefit_2' => sanitize_text_field($_POST['benefit_2']),
                'benefit_3' => sanitize_text_field($_POST['benefit_3']),
                'thanks' => sanitize_text_field($_POST['thanks']),
                'image_1' => esc_url_raw($_POST['image_1'] ?? ''),
                'image_2' => esc_url_raw($_POST['image_2'] ?? ''),
                'image_3' => esc_url_raw($_POST['image_3'] ?? ''),
                'regards' => sanitize_text_field($_POST['regards']),
                'team' => sanitize_text_field($_POST['team'])
            );
        }

        if (update_email_template_content($template_type, $language, $subject, $content_array)) {
            echo '<div class="notice notice-success is-dismissible"><p>Template saved successfully!</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Failed to save template.</p></div>';
        }
    }

    // Handle reset action
    if (isset($_POST['reset_template']) && check_admin_referer('loyalty_email_template_reset')) {
        $template_type = sanitize_text_field($_POST['template_type']);
        $language = sanitize_text_field($_POST['language']);

        if (reset_email_template($template_type, $language)) {
            echo '<div class="notice notice-success is-dismissible"><p>Template reset to defaults!</p></div>';
        } else {
            echo '<div class="notice notice-error is-dismissible"><p>Failed to reset template.</p></div>';
        }
    }

    // Get current template and language from GET params or defaults
    $current_template = isset($_GET['template']) ? sanitize_text_field($_GET['template']) : 'birthday';
    $current_lang = isset($_GET['lang']) ? sanitize_text_field($_GET['lang']) : 'lt';

    // Get template data
    $template = get_email_template_content($current_template, $current_lang);
    $content = $template ? json_decode($template->content, true) : array();

    // Enqueue WordPress media library
    wp_enqueue_media();

    // Enqueue admin styles and scripts
    wp_enqueue_style('loyalty-email-templates-admin', get_template_directory_uri() . '/assets/css/admin-email-templates.css', array(), '1.0.0');
    wp_enqueue_script('loyalty-email-templates-admin', get_template_directory_uri() . '/assets/js/admin-email-templates.js', array('jquery'), '1.0.0', true);

    // Localize script with data
    wp_localize_script('loyalty-email-templates-admin', 'loyaltyTemplateData', array(
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('loyalty_template_ajax'),
        'template' => $current_template,
        'language' => $current_lang
    ));

    // Display the admin page
    include(get_template_directory() . '/inc/admin-email-templates-view.php');
}

// AJAX handler for email preview
function ajax_preview_email_template() {
    check_ajax_referer('loyalty_template_ajax', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized access');
    }

    $template_type = sanitize_text_field($_POST['template_type']);
    $language = sanitize_text_field($_POST['language']);
    $form_data = $_POST['form_data'];

    // Create mock member object for preview
    $mock_member = (object) array(
        'first_name' => 'John',
        'last_name' => 'Doe',
        'email' => 'john@example.com',
        'card_number' => 'CARD-123456',
        'language' => $language
    );

    // Get template with form data
    $template = get_email_template_content($template_type, $language);
    $content = array();

    // Override with form data
    foreach ($form_data as $field) {
        $content[$field['name']] = $field['value'];
    }

    // Replace placeholders
    foreach ($content as $key => $value) {
        $content[$key] = str_replace('{first_name}', $mock_member->first_name, $value);
    }

    // Generate HTML preview
    ob_start();
    if ($template_type === 'birthday') {
        $member = $mock_member;
        $lang = $language;
        include(get_template_directory() . '/email-templates/loyalty-birthday.php');
    } else {
        $member_data = array(
            'first_name' => $mock_member->first_name,
            'card_number' => $mock_member->card_number
        );
        $lang = $language;
        include(get_template_directory() . '/email-templates/loyalty-welcome.php');
    }
    $html = ob_get_clean();

    wp_send_json_success(array('html' => $html));
}
add_action('wp_ajax_preview_email_template', 'ajax_preview_email_template');

// AJAX handler for sending test email
function ajax_send_test_email() {
    check_ajax_referer('loyalty_template_ajax', 'nonce');

    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized access');
    }

    $template_type = sanitize_text_field($_POST['template_type']);
    $language = sanitize_text_field($_POST['language']);
    $test_email = sanitize_email($_POST['test_email']);

    if (!is_email($test_email)) {
        wp_send_json_error('Invalid email address');
    }

    // Create mock member for testing
    $mock_member = (object) array(
        'first_name' => 'Test',
        'last_name' => 'User',
        'email' => $test_email,
        'card_number' => 'TEST-123456',
        'language' => $language
    );

    // Get template
    $template = get_email_template_content($template_type, $language);
    $subject = str_replace('{first_name}', $mock_member->first_name, $template->subject);
    $subject = '[TEST] ' . $subject;

    // Generate email HTML
    ob_start();
    if ($template_type === 'birthday') {
        $member = $mock_member;
        $lang = $language;
        include(get_template_directory() . '/email-templates/loyalty-birthday.php');
    } else {
        $member_data = array(
            'first_name' => $mock_member->first_name,
            'card_number' => $mock_member->card_number,
            'language' => $language
        );
        $lang = $language;
        include(get_template_directory() . '/email-templates/loyalty-welcome.php');
    }
    $message = ob_get_clean();

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Grilinė <info@griline.lt>',
        'Reply-To: info@griline.lt'
    );

    $sent = wp_mail($test_email, $subject, $message, $headers);

    if ($sent) {
        wp_send_json_success('Test email sent successfully!');
    } else {
        wp_send_json_error('Failed to send test email. Please check your email settings.');
    }
}
add_action('wp_ajax_send_test_email', 'ajax_send_test_email');

// Display loyalty members admin page
function display_loyalty_members_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_members';

    // Handle delete action
    if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['member_id'])) {
        $member_id = intval($_GET['member_id']);
        check_admin_referer('delete_loyalty_member_' . $member_id);

        // Get member's card number before deleting
        $member = $wpdb->get_row($wpdb->prepare(
            "SELECT card_number FROM $table_name WHERE id = %d",
            $member_id
        ));

        // Delete the member
        $wpdb->delete($table_name, array('id' => $member_id));

        // Free up the card number
        if ($member && !empty($member->card_number)) {
            $card_numbers_table = $wpdb->prefix . 'loyalty_card_numbers';
            $wpdb->update(
                $card_numbers_table,
                array(
                    'is_used' => 0,
                    'used_by_member_id' => NULL,
                    'used_at' => NULL
                ),
                array('card_number' => $member->card_number),
                array('%d', '%d', '%s'),
                array('%s')
            );
            error_log('Loyalty Member Deleted: Card ' . $member->card_number . ' has been freed up');
        }

        echo '<div class="notice notice-success is-dismissible"><p>Member deleted successfully. Card number has been freed up.</p></div>';
    }

    // Handle export action
    if (isset($_GET['action']) && $_GET['action'] === 'export') {
        check_admin_referer('export_loyalty_members');
        export_loyalty_members_csv();
        exit;
    }

    // Pagination
    $per_page = 20;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    // Search functionality
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $where = '';
    if ($search) {
        $where = $wpdb->prepare(
            " WHERE first_name LIKE %s OR last_name LIKE %s OR email LIKE %s OR card_number LIKE %s",
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%',
            '%' . $wpdb->esc_like($search) . '%'
        );
    }

    // Get total count
    $total_members = $wpdb->get_var("SELECT COUNT(*) FROM $table_name" . $where);
    $total_pages = ceil($total_members / $per_page);

    // Get members for current page
    $members = $wpdb->get_results(
        "SELECT * FROM $table_name" . $where . " ORDER BY created_at DESC LIMIT $per_page OFFSET $offset"
    );

    ?>
    <div class="wrap">
        <h1 class="wp-heading-inline">Loyalty Members</h1>
        <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=loyalty-members&action=export'), 'export_loyalty_members'); ?>" class="page-title-action">Export to CSV</a>
        <hr class="wp-header-end">

        <!-- Search form -->
        <form method="get" style="margin: 20px 0;">
            <input type="hidden" name="page" value="loyalty-members">
            <p class="search-box">
                <input type="search" name="s" value="<?php echo esc_attr($search); ?>" placeholder="Search members...">
                <input type="submit" class="button" value="Search">
                <?php if ($search) : ?>
                    <a href="<?php echo admin_url('admin.php?page=loyalty-members'); ?>" class="button">Clear</a>
                <?php endif; ?>
            </p>
        </form>

        <!-- Stats -->
        <div class="loyalty-stats" style="background: #fff; padding: 15px; border: 1px solid #ccd0d4; border-radius: 4px; margin-bottom: 20px;">
            <h3 style="margin-top: 0;">Statistics</h3>
            <p><strong>Total Members:</strong> <?php echo $total_members; ?></p>
            <p><strong>Members with Marketing Consent:</strong> <?php echo $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE marketing_consent = 1"); ?></p>
            <p><strong>Birthdays This Month:</strong> <?php echo $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE MONTH(birth_date) = MONTH(CURDATE())"); ?></p>
        </div>

        <!-- Members table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Birth Date</th>
                    <th>City</th>
                    <th>Card Number</th>
                    <th>Language</th>
                    <th>Registered</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($members)) : ?>
                    <?php foreach ($members as $member) : ?>
                        <tr id="member-row-<?php echo $member->id; ?>">
                            <td><?php echo $member->id; ?></td>
                            <td><strong><?php echo esc_html($member->first_name . ' ' . $member->last_name); ?></strong></td>
                            <td>
                                <span class="member-email-display-<?php echo $member->id; ?>">
                                    <a href="mailto:<?php echo esc_attr($member->email); ?>"><?php echo esc_html($member->email); ?></a>
                                </span>
                                <span class="member-email-edit-<?php echo $member->id; ?>" style="display: none;">
                                    <input type="email" id="email-input-<?php echo $member->id; ?>" value="<?php echo esc_attr($member->email); ?>" style="width: 250px;">
                                    <button type="button" class="button button-small button-primary" onclick="saveEmail(<?php echo $member->id; ?>)">Save</button>
                                    <button type="button" class="button button-small" onclick="cancelEditEmail(<?php echo $member->id; ?>, '<?php echo esc_js($member->email); ?>')">Cancel</button>
                                    <span class="spinner" id="email-spinner-<?php echo $member->id; ?>" style="float: none; margin: 0;"></span>
                                </span>
                                <div id="email-message-<?php echo $member->id; ?>" style="margin-top: 5px;"></div>
                            </td>
                            <td><?php echo esc_html($member->phone); ?></td>
                            <td><?php echo date('Y-m-d', strtotime($member->birth_date)); ?></td>
                            <td><?php echo esc_html($member->city); ?></td>
                            <td><code><?php echo esc_html($member->card_number); ?></code></td>
                            <td><?php echo strtoupper($member->language); ?></td>
                            <td><?php echo date('Y-m-d H:i', strtotime($member->created_at)); ?></td>
                            <td>
                                <button type="button" class="button button-small" onclick="editEmail(<?php echo $member->id; ?>)">Edit Email</button>
                                <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=loyalty-members&action=delete&member_id=' . $member->id), 'delete_loyalty_member_' . $member->id); ?>"
                                   class="button button-small"
                                   onclick="return confirm('Are you sure you want to delete this member?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="11" style="text-align: center; padding: 40px;">
                            <?php if ($search) : ?>
                                No members found matching "<?php echo esc_html($search); ?>"
                            <?php else : ?>
                                No loyalty members yet.
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <?php if ($total_pages > 1) : ?>
            <div class="tablenav bottom">
                <div class="tablenav-pages">
                    <span class="displaying-num"><?php echo $total_members; ?> items</span>
                    <?php
                    $pagination = paginate_links(array(
                        'base' => add_query_arg('paged', '%#%'),
                        'format' => '',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                        'total' => $total_pages,
                        'current' => $current_page,
                        'type' => 'plain'
                    ));
                    echo $pagination;
                    ?>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <script>
    function editEmail(memberId) {
        // Hide display, show edit form
        document.querySelector('.member-email-display-' + memberId).style.display = 'none';
        document.querySelector('.member-email-edit-' + memberId).style.display = 'inline-block';
        document.getElementById('email-input-' + memberId).focus();
    }

    function cancelEditEmail(memberId, originalEmail) {
        // Show display, hide edit form
        document.querySelector('.member-email-display-' + memberId).style.display = 'inline';
        document.querySelector('.member-email-edit-' + memberId).style.display = 'none';
        // Reset input value
        document.getElementById('email-input-' + memberId).value = originalEmail;
        // Clear any messages
        document.getElementById('email-message-' + memberId).innerHTML = '';
    }

    function saveEmail(memberId) {
        var emailInput = document.getElementById('email-input-' + memberId);
        var newEmail = emailInput.value.trim();
        var spinner = document.getElementById('email-spinner-' + memberId);
        var messageDiv = document.getElementById('email-message-' + memberId);

        // Basic validation
        if (!newEmail || !isValidEmail(newEmail)) {
            messageDiv.innerHTML = '<span style="color: #d63638;">Please enter a valid email address.</span>';
            return;
        }

        // Show spinner
        spinner.classList.add('is-active');
        messageDiv.innerHTML = '';

        // AJAX request
        jQuery.ajax({
            url: ajaxurl,
            type: 'POST',
            data: {
                action: 'update_loyalty_member_email',
                member_id: memberId,
                new_email: newEmail,
                nonce: '<?php echo wp_create_nonce("update_loyalty_email"); ?>'
            },
            success: function(response) {
                spinner.classList.remove('is-active');

                if (response.success) {
                    messageDiv.innerHTML = '<span style="color: #00a32a;">✓ Email updated successfully!</span>';

                    // Update the display email
                    document.querySelector('.member-email-display-' + memberId + ' a').textContent = newEmail;
                    document.querySelector('.member-email-display-' + memberId + ' a').href = 'mailto:' + newEmail;

                    // Switch back to display mode after 2 seconds
                    setTimeout(function() {
                        document.querySelector('.member-email-display-' + memberId).style.display = 'inline';
                        document.querySelector('.member-email-edit-' + memberId).style.display = 'none';
                        messageDiv.innerHTML = '';
                    }, 2000);
                } else {
                    messageDiv.innerHTML = '<span style="color: #d63638;">✗ ' + response.data + '</span>';
                }
            },
            error: function() {
                spinner.classList.remove('is-active');
                messageDiv.innerHTML = '<span style="color: #d63638;">✗ An error occurred. Please try again.</span>';
            }
        });
    }

    function isValidEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(email);
    }
    </script>
    <?php
}

// AJAX handler for updating member email
function update_loyalty_member_email() {
    global $wpdb;

    // Security checks
    if (!current_user_can('manage_options')) {
        wp_send_json_error('Unauthorized access');
    }

    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'update_loyalty_email')) {
        wp_send_json_error('Security check failed');
    }

    // Get and validate input
    $member_id = isset($_POST['member_id']) ? intval($_POST['member_id']) : 0;
    $new_email = isset($_POST['new_email']) ? sanitize_email($_POST['new_email']) : '';

    if (!$member_id || !$new_email) {
        wp_send_json_error('Invalid data provided');
    }

    // Validate email format
    if (!is_email($new_email)) {
        wp_send_json_error('Invalid email format');
    }

    $table_name = $wpdb->prefix . 'loyalty_members';

    // Check if email already exists for another member
    $existing_member = $wpdb->get_var($wpdb->prepare(
        "SELECT id FROM $table_name WHERE email = %s AND id != %d",
        $new_email,
        $member_id
    ));

    if ($existing_member) {
        wp_send_json_error('This email is already registered to another member');
    }

    // Update the email
    $updated = $wpdb->update(
        $table_name,
        array('email' => $new_email),
        array('id' => $member_id),
        array('%s'),
        array('%d')
    );

    if ($updated !== false) {
        wp_send_json_success('Email updated successfully');
    } else {
        wp_send_json_error('Failed to update email. Please try again.');
    }
}
add_action('wp_ajax_update_loyalty_member_email', 'update_loyalty_member_email');

// Export loyalty members to CSV
function export_loyalty_members_csv() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_members';

    $members = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC", ARRAY_A);

    if (empty($members)) {
        wp_die('No members to export');
    }

    // Set headers for CSV download
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=loyalty-members-' . date('Y-m-d') . '.csv');

    // Create file pointer
    $output = fopen('php://output', 'w');

    // Add BOM for UTF-8
    fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));

    // Add column headers
    fputcsv($output, array(
        'ID',
        'First Name',
        'Last Name',
        'Email',
        'Phone',
        'Birth Date',
        'City',
        'Card Number',
        'Marketing Consent',
        'Language',
        'Birthday Email Sent Year',
        'Registered Date'
    ));

    // Add data rows
    foreach ($members as $member) {
        fputcsv($output, array(
            $member['id'],
            $member['first_name'],
            $member['last_name'],
            $member['email'],
            $member['phone'],
            $member['birth_date'],
            $member['city'],
            $member['card_number'],
            $member['marketing_consent'] ? 'Yes' : 'No',
            $member['language'],
            $member['birthday_email_sent_year'],
            $member['created_at']
        ));
    }

    fclose($output);
}

// Debug page to check loyalty members (URL: yoursite.com/?debug_loyalty=1)
add_action('init', function() {
    if (isset($_GET['debug_loyalty']) && current_user_can('manage_options')) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'loyalty_members';

        echo '<h1>Loyalty Members Debug</h1>';

        // Check if table exists
        $table_exists = $wpdb->get_var("SHOW TABLES LIKE '$table_name'") == $table_name;
        echo '<p><strong>Loyalty Members Table exists:</strong> ' . ($table_exists ? 'Yes' : 'No') . '</p>';

        if ($table_exists) {
            $members = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
            echo '<p><strong>Total members:</strong> ' . count($members) . '</p>';

            if (!empty($members)) {
                echo '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
                echo '<tr><th>ID</th><th>Name</th><th>Email</th><th>Birth Date</th><th>Card #</th><th>Marketing</th><th>Language</th><th>Created</th></tr>';
                foreach ($members as $member) {
                    echo '<tr>';
                    echo '<td>' . $member->id . '</td>';
                    echo '<td>' . $member->first_name . ' ' . $member->last_name . '</td>';
                    echo '<td>' . $member->email . '</td>';
                    echo '<td>' . $member->birth_date . '</td>';
                    echo '<td>' . $member->card_number . '</td>';
                    echo '<td>' . ($member->marketing_consent ? 'Yes' : 'No') . '</td>';
                    echo '<td>' . $member->language . '</td>';
                    echo '<td>' . $member->created_at . '</td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        }

        // Check CFDB7 table
        echo '<hr><h2>CFDB7 Integration</h2>';
        $cfdb_table = $wpdb->prefix . 'db7_forms';
        $cfdb_exists = $wpdb->get_var("SHOW TABLES LIKE '$cfdb_table'") == $cfdb_table;
        echo '<p><strong>CFDB7 Table exists:</strong> ' . ($cfdb_exists ? 'Yes' : 'No') . '</p>';

        if ($cfdb_exists) {
            // Show table structure
            $columns = $wpdb->get_results("SHOW COLUMNS FROM $cfdb_table");
            echo '<p><strong>CFDB7 Table structure:</strong></p>';
            echo '<pre>';
            foreach ($columns as $column) {
                echo $column->Field . ' (' . $column->Type . ')' . "\n";
            }
            echo '</pre>';

            // Show recent entries
            $cfdb_entries = $wpdb->get_results("SELECT * FROM $cfdb_table ORDER BY form_date DESC LIMIT 10");
            echo '<p><strong>Recent CFDB7 entries:</strong> ' . count($cfdb_entries) . '</p>';

            if (!empty($cfdb_entries)) {
                echo '<table border="1" cellpadding="10" style="border-collapse: collapse;">';
                echo '<tr><th>ID</th><th>Form Post ID</th><th>Date</th><th>Data</th></tr>';
                foreach ($cfdb_entries as $entry) {
                    echo '<tr>';
                    echo '<td>' . $entry->form_id . '</td>';
                    echo '<td>' . $entry->form_post_id . '</td>';
                    echo '<td>' . $entry->form_date . '</td>';
                    echo '<td><pre>' . print_r(maybe_unserialize($entry->form_value), true) . '</pre></td>';
                    echo '</tr>';
                }
                echo '</table>';
            }
        }

        exit;
    }
});

// Function to send birthday email
function send_birthday_email($member) {
    $lang = $member->language;

    // Get email template
    ob_start();
    include(get_template_directory() . '/email-templates/loyalty-birthday.php');
    $message = ob_get_clean();

    // Email subjects based on language
    $subjects = array(
        'lt' => 'Su gimimo diena, ' . $member->first_name . '! 🎉',
        'en' => 'Happy Birthday, ' . $member->first_name . '! 🎉',
        'ru' => 'С днём рождения, ' . $member->first_name . '! 🎉'
    );

    $subject = isset($subjects[$lang]) ? $subjects[$lang] : $subjects['lt'];

    $headers = array(
        'Content-Type: text/html; charset=UTF-8',
        'From: Gaisrinė <info@gaisrine.lt>',
        'Reply-To: info@gaisrine.lt'
    );

    return wp_mail($member->email, $subject, $message, $headers);
}

// Include admin card numbers management page
require_once get_template_directory() . '/inc/admin-card-numbers.php';