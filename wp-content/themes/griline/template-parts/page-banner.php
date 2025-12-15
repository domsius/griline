<?php
/**
 * Template part for displaying page banner with title and breadcrumb
 *
 * @package Griline
 */

$current_page_title = get_the_title();
$selected_category = isset($_GET['kategorija']) ? sanitize_text_field($_GET['kategorija']) : null;
$category_name = null;

// Hardcoded category translations for breadcrumb
function grl_banner_cat_name($slug) {
    $lang = apply_filters('wpml_current_language', 'lt');
    $translations = array(
        'dienos-pietus' => array('lt' => 'Dienos pietūs', 'en' => 'Daily Lunch', 'ru' => 'Дневное меню'),
        'patiekalai' => array('lt' => 'Patiekalai', 'en' => 'Dishes', 'ru' => 'Блюда'),
        'vaikiskas-meniu' => array('lt' => 'Vaikiškas meniu', 'en' => 'Kids Menu', 'ru' => 'Детское меню'),
        'desertai' => array('lt' => 'Desertai', 'en' => 'Desserts', 'ru' => 'Десерты'),
        'gerimai' => array('lt' => 'Gėrimai', 'en' => 'Drinks', 'ru' => 'Напитки'),
    );
    return isset($translations[$slug][$lang]) ? $translations[$slug][$lang] : null;
}

// If on menu page with category selected, get category name
if ($selected_category) {
    // First try hardcoded translation
    $category_name = grl_banner_cat_name($selected_category);

    // Fallback to database if not in hardcoded list
    if (!$category_name) {
        global $wpdb;
        $original_term = $wpdb->get_row($wpdb->prepare("
            SELECT t.term_id, t.name, t.slug
            FROM {$wpdb->terms} t
            JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
            WHERE tt.taxonomy = 'kategorijos' AND t.slug = %s
        ", $selected_category));

        if ($original_term) {
            // Try hardcoded with original slug
            $category_name = grl_banner_cat_name($original_term->slug);

            if (!$category_name) {
                // Final fallback: use WPML translation
                $translated_id = apply_filters('wpml_object_id', $original_term->term_id, 'kategorijos', true);
                $translated_term = get_term($translated_id, 'kategorijos');
                $category_name = ($translated_term && !is_wp_error($translated_term))
                    ? $translated_term->name
                    : $original_term->name;
            }
        }
    }
}
?>
<!-- ==== banner section start ==== -->
<section class="banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="banner-content">
                    <h3><?php echo esc_html($category_name ? $category_name : $current_page_title); ?></h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo home_url(); ?>"><?php _e('Titulinis', 'flavor'); ?></a></li>
                            <?php if ($category_name) : ?>
                                <li class="breadcrumb-item"><a href="<?php echo get_permalink(); ?>"><?php echo esc_html($current_page_title); ?></a></li>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html($category_name); ?></li>
                            <?php else : ?>
                                <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html($current_page_title); ?></li>
                            <?php endif; ?>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==== #banner section end ==== -->
