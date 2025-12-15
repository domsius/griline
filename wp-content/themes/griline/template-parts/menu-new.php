<?php
/**
 * Grilinė Menu Template - Mobile-First Design
 * Optimized for 90%+ mobile traffic
 * Custom classes: grl-* prefix
 * WPML Compatible
 */

// Hardcoded translations
function grl_t($key, $default = '') {
    $lang = apply_filters('wpml_current_language', 'lt');

    $translations = array(
        // UI strings
        'empty' => array(
            'lt' => 'Šiuo metu tuščia',
            'en' => 'Currently empty',
            'ru' => 'В данный момент пусто',
        ),
        'view' => array(
            'lt' => 'Peržiūrėti',
            'en' => 'View',
            'ru' => 'Смотреть',
        ),
        // Category names (by slug)
        'cat_dienos-pietus' => array(
            'lt' => 'Dienos pietūs',
            'en' => 'Daily Lunch',
            'ru' => 'Дневное меню',
        ),
        'cat_patiekalai' => array(
            'lt' => 'Patiekalai',
            'en' => 'Dishes',
            'ru' => 'Блюда',
        ),
        'cat_vaikiskas-meniu' => array(
            'lt' => 'Vaikiškas meniu',
            'en' => 'Kids Menu',
            'ru' => 'Детское меню',
        ),
        'cat_desertai' => array(
            'lt' => 'Desertai',
            'en' => 'Desserts',
            'ru' => 'Десерты',
        ),
        'cat_gerimai' => array(
            'lt' => 'Gėrimai',
            'en' => 'Drinks',
            'ru' => 'Напитки',
        ),
        // Category descriptions
        'desc_dienos-pietus' => array(
            'lt' => 'Kasdieniai pietų pasiūlymai',
            'en' => 'Daily lunch specials',
            'ru' => 'Ежедневные обеденные предложения',
        ),
        'desc_patiekalai' => array(
            'lt' => 'Nuo užkandžių iki kepsnių',
            'en' => 'From appetizers to steaks',
            'ru' => 'От закусок до стейков',
        ),
        'desc_vaikiskas-meniu' => array(
            'lt' => 'Mažiesiems gurmanams',
            'en' => 'For little gourmets',
            'ru' => 'Для маленьких гурманов',
        ),
        'desc_desertai' => array(
            'lt' => 'Saldus pabaigai',
            'en' => 'Sweet endings',
            'ru' => 'Сладкое завершение',
        ),
        'desc_gerimai' => array(
            'lt' => 'Alus, vynas ir kokteiliai',
            'en' => 'Beer, wine and cocktails',
            'ru' => 'Пиво, вино и коктейли',
        ),
    );

    if (isset($translations[$key][$lang])) {
        return $translations[$key][$lang];
    }
    return $default ?: ($translations[$key]['lt'] ?? '');
}

// Define category config by SLUG (stable across languages)
$category_config = array(
    'dienos-pietus' => array(
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>',
        'order' => 1,
    ),
    'patiekalai' => array(
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M3 2v7c0 1.1.9 2 2 2h4a2 2 0 0 0 2-2V2"/><path d="M7 2v20"/><path d="M21 15V2a5 5 0 0 0-5 5v6c0 1.1.9 2 2 2h3zm0 0v7"/></svg>',
        'order' => 2,
    ),
    'vaikiskas-meniu' => array(
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="8" r="5"/><path d="M20 21a8 8 0 1 0-16 0"/></svg>',
        'order' => 3,
    ),
    'desertai' => array(
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M12 2a4 4 0 0 0-4 4c0 2 2 3 2 6H6l1 7h10l1-7h-4c0-3 2-4 2-6a4 4 0 0 0-4-4z"/></svg>',
        'order' => 4,
    ),
    'gerimai' => array(
        'icon' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5"><path d="M8 22h8"/><path d="M12 11v11"/><path d="m19 3-7 8-7-8h14z"/></svg>',
        'order' => 5,
    ),
);

// Helper: Get default language code
$default_lang = apply_filters('wpml_default_language', null);
global $wpdb;

// Get main categories using direct SQL (bypasses WPML filtering)
$main_categories_lt = $wpdb->get_results("
    SELECT t.term_id, t.name, t.slug
    FROM {$wpdb->terms} t
    JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
    WHERE tt.taxonomy = 'kategorijos' AND tt.parent = 0
    ORDER BY t.name ASC
");

// Build filtered list with original term IDs
$categories_with_config = array();
foreach ($main_categories_lt as $cat) {
    if (isset($category_config[$cat->slug])) {
        $categories_with_config[] = array(
            'original_id' => $cat->term_id,
            'original_slug' => $cat->slug,
            'config' => $category_config[$cat->slug],
        );
    }
}

// Translate categories to current language
$filtered_categories = array();
foreach ($categories_with_config as $cat_data) {
    $translated_id = apply_filters('wpml_object_id', $cat_data['original_id'], 'kategorijos', true);
    $term = get_term($translated_id, 'kategorijos');

    if ($term && !is_wp_error($term)) {
        $term->config = $cat_data['config'];
        $term->original_term_id = $cat_data['original_id'];
        $term->original_slug = $cat_data['original_slug'];
        $filtered_categories[] = $term;
    }
}

usort($filtered_categories, function($a, $b) {
    return $a->config['order'] - $b->config['order'];
});

// Check if a specific category is selected via URL
$selected_category = isset($_GET['kategorija']) ? sanitize_text_field($_GET['kategorija']) : null;
$selected_cat_obj = null;

if ($selected_category) {
    foreach ($filtered_categories as $cat) {
        // Match by current translated slug OR original LT slug
        if ($cat->slug === $selected_category || $cat->original_slug === $selected_category) {
            $selected_cat_obj = $cat;
            break;
        }
    }
}
?>

<section class="grl-menu">
    <?php if (!$selected_cat_obj) : ?>
    <!-- ========== MAIN MENU GRID ========== -->
    <div class="grl-main">
        <div class="grl-grid">
            <?php foreach ($filtered_categories as $index => $category) : ?>
            <a href="?kategorija=<?php echo esc_attr($category->slug); ?>"
               class="grl-tile"
               style="--i: <?php echo $index; ?>">
                <div class="grl-tile__icon">
                    <?php echo $category->config['icon']; ?>
                </div>
                <h3 class="grl-tile__title"><?php echo esc_html(grl_t('cat_' . $category->original_slug, $category->name)); ?></h3>
                <p class="grl-tile__desc"><?php echo esc_html(grl_t('desc_' . $category->original_slug)); ?></p>
                <span class="grl-tile__cta">
                    <?php echo esc_html(grl_t('view')); ?>
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </span>
            </a>
            <?php endforeach; ?>
        </div>
    </div>

    <?php else : ?>
    <!-- ========== CATEGORY VIEW ========== -->

    <?php
    // Use original (LT) term ID for parent-child queries
    $parent_id_for_query = isset($selected_cat_obj->original_term_id)
        ? $selected_cat_obj->original_term_id
        : $selected_cat_obj->term_id;

    // Get subcategories using direct SQL (bypasses WPML filtering)
    $subcategories_lt = $wpdb->get_results($wpdb->prepare("
        SELECT t.term_id, t.name, t.slug, tt.parent
        FROM {$wpdb->terms} t
        JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
        WHERE tt.taxonomy = 'kategorijos' AND tt.parent = %d
        ORDER BY t.name ASC
    ", $parent_id_for_query));

    // Store original IDs (SQL results are simple objects)
    $subcategory_data = array();
    if (!empty($subcategories_lt)) {
        foreach ($subcategories_lt as $subcat) {
            $subcategory_data[] = array(
                'original_id' => $subcat->term_id,
                'original_slug' => $subcat->slug,
                'original_name' => $subcat->name,
            );
        }
    }

    // Translate subcategories
    $subcategories = array();
    foreach ($subcategory_data as $sub_data) {
        $translated_id = apply_filters('wpml_object_id', $sub_data['original_id'], 'kategorijos', true);
        $term = get_term($translated_id, 'kategorijos');
        if ($term && !is_wp_error($term)) {
            $term->original_term_id = $sub_data['original_id'];
            $term->original_slug = $sub_data['original_slug'];
            $subcategories[] = $term;
        }
    }

    if (!empty($subcategories)) :
    ?>
    <!-- HORIZONTAL SWIPE TABS -->
    <div class="grl-tabs-wrap">
        <div class="grl-tabs">
            <?php foreach ($subcategories as $index => $subcat) : ?>
            <button class="grl-tab <?php echo ($index === 0) ? 'is-on' : ''; ?>"
                    data-panel="panel-<?php echo $subcat->term_id; ?>">
                <?php echo esc_html($subcat->name); ?>
            </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- CONTENT PANELS -->
    <div class="grl-body">
        <?php foreach ($subcategories as $index => $subcat) : ?>
        <div class="grl-panel <?php echo ($index === 0) ? 'is-on' : ''; ?>"
             id="panel-<?php echo $subcat->term_id; ?>">

            <?php
            // Check for third level categories using original term ID
            $subcat_orig_id = isset($subcat->original_term_id) ? $subcat->original_term_id : $subcat->term_id;

            // Get third level using direct SQL
            $third_level_lt = $wpdb->get_results($wpdb->prepare("
                SELECT t.term_id, t.name, t.slug
                FROM {$wpdb->terms} t
                JOIN {$wpdb->term_taxonomy} tt ON t.term_id = tt.term_id
                WHERE tt.taxonomy = 'kategorijos' AND tt.parent = %d
                ORDER BY t.name ASC
            ", $subcat_orig_id));

            // Translate third level
            $third_level = array();
            foreach ($third_level_lt as $t) {
                $translated_id = apply_filters('wpml_object_id', $t->term_id, 'kategorijos', true);
                $term = get_term($translated_id, 'kategorijos');
                if ($term && !is_wp_error($term)) {
                    $term->original_term_id = $t->term_id;
                    $third_level[] = $term;
                }
            }

            if (!empty($third_level)) :
                foreach ($third_level as $third_cat) :
            ?>
                <h3 class="grl-group"><?php echo esc_html($third_cat->name); ?></h3>
                <?php echo grl_mobile_dishes($third_cat->original_term_id); ?>
            <?php
                endforeach;
            else :
                echo grl_mobile_dishes($subcat->original_term_id);
            endif;
            ?>
        </div>
        <?php endforeach; ?>
    </div>

    <?php else : ?>
    <!-- NO SUBCATEGORIES -->
    <div class="grl-body">
        <div class="grl-panel is-on">
            <?php echo grl_mobile_dishes($selected_cat_obj->original_term_id); ?>
        </div>
    </div>
    <?php endif; ?>

    <?php endif; ?>
</section>

<?php
/**
 * Render dishes - mobile optimized
 * Gets LT posts, then shows translations if they exist
 */
function grl_mobile_dishes($term_id) {
    $current_lang = apply_filters('wpml_current_language', 'lt');

    // Query original LT posts (suppress_filters to get all)
    $args = array(
        'post_type' => 'restorano_meniu',
        'posts_per_page' => -1,
        'tax_query' => array(
            array(
                'taxonomy' => 'kategorijos',
                'field'    => 'term_id',
                'terms'    => $term_id,
            ),
        ),
        'orderby' => 'menu_order',
        'order'   => 'ASC',
        'suppress_filters' => true,
    );

    $query = new WP_Query($args);
    ob_start();

    if ($query->have_posts()) :
        echo '<div class="grl-dishes">';
        $i = 0;
        while ($query->have_posts()) : $query->the_post();
            $original_id = get_the_ID();

            // Try to get translated post for current language
            $translated_id = apply_filters('wpml_object_id', $original_id, 'restorano_meniu', false, $current_lang);
            $display_id = ($translated_id && $translated_id !== $original_id) ? $translated_id : $original_id;

            // Get post data (translated or original)
            $post_obj = get_post($display_id);
            $title = $post_obj->post_title;
            $content = $post_obj->post_content;
            $price = get_post_meta($original_id, 'kaina', true); // Price from original
            $has_thumb = has_post_thumbnail($original_id); // Thumbnail from original
            ?>
            <article class="grl-dish <?php echo $has_thumb ? 'has-img' : ''; ?>" style="--d: <?php echo $i * 30; ?>ms">
                <?php if ($has_thumb) : ?>
                <div class="grl-dish__thumb">
                    <?php echo get_the_post_thumbnail($original_id, 'medium'); ?>
                </div>
                <?php endif; ?>
                <div class="grl-dish__info">
                    <div class="grl-dish__head">
                        <h4 class="grl-dish__title"><?php echo esc_html($title); ?></h4>
                        <?php if ($price) : ?>
                        <span class="grl-dish__price"><?php echo esc_html($price); ?></span>
                        <?php endif; ?>
                    </div>
                    <?php if ($content) : ?>
                    <p class="grl-dish__desc"><?php echo wp_strip_all_tags($content); ?></p>
                    <?php endif; ?>
                </div>
            </article>
            <?php
            $i++;
        endwhile;
        echo '</div>';
        wp_reset_postdata();
    else :
        echo '<p class="grl-empty">' . esc_html(grl_t('empty')) . '</p>';
    endif;

    return ob_get_clean();
}
?>

<style>
/* ================================================
   GRILINĖ MENU - Mobile-First Design
   90%+ mobile traffic optimization
   ================================================ */

/* === TOKENS === */
.grl-menu {
    --grl-orange: #f25b0a;
    --grl-orange-light: #ff6d1f;
    --grl-dark: #2d2d2d;
    --grl-text: #4d4d4d;
    --grl-muted: #888;
    --grl-light: #f7f7f7;
    --grl-border: #e8e8e8;
    --grl-white: #fff;
    --grl-radius: 12px;
    --grl-radius-sm: 8px;
    --grl-shadow: 0 2px 8px rgba(0,0,0,0.08);
    --grl-font: 'Raleway', -apple-system, sans-serif;

    font-family: var(--grl-font);
    background: var(--grl-white);
    min-height: 50vh;
    padding-bottom: 32px;
    width: 100%;
}

/* === MAIN CATEGORY GRID === */
.grl-main {
    padding: 20px 16px;
}

.grl-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 12px;
}

/* Category Tiles */
.grl-tile {
    display: flex;
    flex-direction: column;
    padding: 16px;
    background: var(--grl-white);
    border: 1px solid var(--grl-border);
    border-radius: var(--grl-radius);
    text-decoration: none;
    color: inherit;
    position: relative;
    overflow: hidden;
    transition: all 0.3s ease;
    animation: grl-slideUp 0.5s ease both;
    animation-delay: calc(var(--i) * 80ms);
}

.grl-tile::after {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: var(--grl-orange);
    transform: scaleX(0);
    transform-origin: left;
    transition: transform 0.3s ease;
}

.grl-tile:active {
    transform: scale(0.97);
    background: var(--grl-light);
}

.grl-tile:active::after {
    transform: scaleX(1);
}

.grl-tile__icon {
    width: 44px;
    height: 44px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: var(--grl-light);
    border-radius: 50%;
    margin-bottom: 12px;
    color: var(--grl-orange);
    transition: all 0.3s ease;
}

.grl-tile__icon svg {
    width: 22px;
    height: 22px;
}

.grl-tile:active .grl-tile__icon {
    background: var(--grl-orange);
    color: var(--grl-white);
}

.grl-tile__title {
    font-size: 16px;
    font-weight: 700;
    color: var(--grl-dark);
    margin: 0 0 4px;
    line-height: 1.2;
}

.grl-tile__desc {
    font-size: 12px;
    color: var(--grl-muted);
    margin: 0;
    line-height: 1.4;
    flex: 1;
}

.grl-tile__cta {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 12px;
    font-weight: 600;
    color: var(--grl-orange);
    margin-top: 12px;
}

.grl-tile__cta svg {
    width: 14px;
    height: 14px;
    transition: transform 0.2s;
}

.grl-tile:active .grl-tile__cta svg {
    transform: translateX(3px);
}

/* === TABS - Horizontal Scroll === */
.grl-tabs-wrap {
    position: sticky;
    top: 0;
    z-index: 100;
    background: var(--grl-white);
    border-bottom: 1px solid var(--grl-border);
    /* Break out of container */
    width: 100vw;
    margin-left: calc(-50vw + 50%);
    padding-left: calc(50vw - 50%);
    padding-right: calc(50vw - 50%);
}

.grl-tabs {
    display: flex;
    gap: 8px;
    padding: 12px 0;
    overflow-x: auto;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
    -ms-overflow-style: none;
}

.grl-tabs::-webkit-scrollbar {
    display: none;
}

.grl-tab {
    flex-shrink: 0;
    padding: 10px 18px;
    font-family: var(--grl-font);
    font-size: 14px;
    font-weight: 500;
    color: var(--grl-text);
    background: var(--grl-light);
    border: none;
    border-radius: 100px;
    cursor: pointer;
    white-space: nowrap;
    scroll-snap-align: start;
    transition: all 0.2s ease;
    -webkit-tap-highlight-color: transparent;
}

.grl-tab.is-on {
    background: var(--grl-dark);
    color: var(--grl-white);
}

.grl-tab:active {
    transform: scale(0.96);
}

/* === CONTENT BODY === */
.grl-panel {
    display: none;
}

.grl-panel.is-on {
    display: block;
    animation: grl-fadeIn 0.3s ease;
}

/* === GROUP HEADING === */
.grl-group {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--grl-orange);
    margin: 24px 0 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid var(--grl-orange);
}

.grl-group:first-child {
    margin-top: 16px;
}

/* === DISHES LIST === */
.grl-dishes {
    display: flex;
    flex-direction: column;
}

/* === SINGLE DISH === */
.grl-dish {
    display: flex;
    flex-direction: column;
    padding: 16px 0;
    border-bottom: 1px solid var(--grl-border);
    animation: grl-slideUp 0.4s ease both;
    animation-delay: var(--d);
}

.grl-dish:last-child {
    border-bottom: none;
}

/* Dish with image */
.grl-dish.has-img {
    gap: 12px;
}

.grl-dish__thumb {
    width: 100%;
    height: 180px;
    border-radius: var(--grl-radius);
    overflow: hidden;
    background: var(--grl-light);
}

.grl-dish__thumb img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

/* Dish info */
.grl-dish__info {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.grl-dish__head {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    gap: 12px;
}

.grl-dish__title {
    flex: 1;
    font-size: 16px;
    font-weight: 600;
    color: var(--grl-dark);
    margin: 0;
    line-height: 1.3;
}

.grl-dish__price {
    flex-shrink: 0;
    font-size: 16px;
    font-weight: 700;
    color: var(--grl-orange);
    white-space: nowrap;
}

.grl-dish__desc {
    font-size: 14px;
    color: var(--grl-muted);
    line-height: 1.5;
    margin: 0;
}

/* Empty state */
.grl-empty {
    text-align: center;
    padding: 48px 20px;
    color: var(--grl-muted);
    font-style: italic;
}

/* === ANIMATIONS === */
@keyframes grl-slideUp {
    from {
        opacity: 0;
        transform: translateY(16px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@keyframes grl-fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

/* === TABLET+ ENHANCEMENTS === */
@media (min-width: 600px) {
    .grl-main {
        padding: 24px;
    }

    .grl-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
    }

    .grl-tile {
        padding: 20px;
    }

    .grl-tile__title {
        font-size: 18px;
    }

    .grl-tile__desc {
        font-size: 13px;
    }

    .grl-dish.has-img {
        flex-direction: row;
        gap: 20px;
    }

    .grl-dish__thumb {
        width: 120px;
        height: 120px;
        flex-shrink: 0;
    }

    .grl-dish__info {
        flex: 1;
        justify-content: center;
    }
}

/* === DESKTOP ENHANCEMENTS === */
@media (min-width: 900px) {
    .grl-menu {
        margin: 0 auto;
    }

    .grl-grid {
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
    }

    .grl-tile {
        padding: 24px;
    }

    .grl-tile:hover {
        border-color: var(--grl-orange);
        box-shadow: 0 8px 24px rgba(242, 91, 10, 0.12);
        transform: translateY(-3px);
    }

    .grl-tile:hover::after {
        transform: scaleX(1);
    }

    .grl-tile:hover .grl-tile__icon {
        background: var(--grl-orange);
        color: var(--grl-white);
    }

    .grl-tile:hover .grl-tile__cta svg {
        transform: translateX(4px);
    }

    .grl-tab:hover:not(.is-on) {
        background: var(--grl-border);
    }

    .grl-dishes {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0 32px;
    }

    .grl-dish {
        padding: 20px 0;
    }

    .grl-dish__thumb {
        width: 100px;
        height: 100px;
    }
}

/* === REDUCED MOTION === */
@media (prefers-reduced-motion: reduce) {
    .grl-tile,
    .grl-dish,
    .grl-panel {
        animation: none;
    }
}
</style>

<script>
(function() {
    document.addEventListener('DOMContentLoaded', function() {
        const tabs = document.querySelectorAll('.grl-tab');
        const panels = document.querySelectorAll('.grl-panel');

        tabs.forEach(tab => {
            tab.addEventListener('click', function() {
                const panelId = this.getAttribute('data-panel');

                // Update tabs
                tabs.forEach(t => t.classList.remove('is-on'));
                this.classList.add('is-on');

                // Update panels
                panels.forEach(p => p.classList.remove('is-on'));
                const target = document.getElementById(panelId);
                if (target) {
                    target.classList.add('is-on');
                }

                // Scroll tab into view
                this.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            });
        });
    });
})();
</script>
