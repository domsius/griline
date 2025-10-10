<?php
// WPML-compatible menu template - fixes translation issues

// Get admin settings for visible categories
$visible_categories = get_option('griline_visible_categories', array());
$admin_default_category = get_option('griline_default_category', '');

// Ensure arrays are always arrays
if (!is_array($visible_categories)) {
    $visible_categories = array();
}

// Get current language
$current_language = apply_filters('wpml_current_language', NULL);

// Get all parent categories for current language
$all_parent_categories = get_terms(array(
    'taxonomy'   => 'kategorijos',
    'hide_empty' => true,
    'parent'     => 0,
    'object_type'=> array('restorano_meniu'),
    'lang'       => $current_language,
    'orderby'    => 'meta_value_num',
    'order'      => 'ASC',
));

// WPML Translation Fix: Convert admin category IDs to current language
$parent_categories = array();
if (!empty($visible_categories)) {
    $translated_visible_categories = array();
    
    foreach ($visible_categories as $category_id) {
        // Get the translated term ID for current language
        $translated_id = apply_filters('wpml_object_id', $category_id, 'kategorijos', false, $current_language);
        if ($translated_id) {
            $translated_visible_categories[] = $translated_id;
        }
    }
    
    // Filter categories based on translated IDs
    foreach ($all_parent_categories as $category) {
        if (in_array($category->term_id, $translated_visible_categories)) {
            $parent_categories[] = $category;
        }
    }
} else {
    // If no admin settings, show all categories
    $parent_categories = $all_parent_categories;
}

// Find the default active category
$default_active_category = null;
if (!empty($admin_default_category)) {
    foreach ($parent_categories as $category) {
        if ($category->slug === $admin_default_category || 
            strpos($category->slug, $admin_default_category) !== false) {
            $default_active_category = $category;
            break;
        }
    }
}

if (!$default_active_category && !empty($parent_categories)) {
    $default_active_category = $parent_categories[0];
}
?>

<section class="explore section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header">
                    <h2><?php _e('Meniu', 'griline'); ?></h2>
                </div>
            </div>
        </div>
        
        <?php if (!empty($parent_categories)) : ?>
        <div class="row">
            <div class="col-12">
                <div class="explore-tab-btn-wrapper">
                    <?php foreach ($parent_categories as $category) : 
                        $active_class = ($default_active_category && $category->term_id === $default_active_category->term_id) ? 'active' : '';
                    ?>
                        <a href="#<?php echo $category->slug; ?>" class="button button--tertiary explore-tab-btn <?php echo $active_class; ?>" data-target="<?php echo $category->slug; ?>"><?php echo $category->name; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        
        <div class="row neutral-row">
            <div class="col-lg-12">
                <div class="explore-tab-wrapper single-item">
                    <?php foreach ($parent_categories as $parent_category) : ?>
                    <div class="explore-tab-content" id="<?php echo $parent_category->slug; ?>" style="display: <?php echo ($default_active_category && $parent_category->term_id === $default_active_category->term_id) ? 'block' : 'none'; ?>">
                        <?php
                        // Query posts for this category with proper WPML filtering
                        $args = array(
                            'post_type' => 'restorano_meniu',
                            'posts_per_page' => -1,
                            'tax_query' => array(
                                array(
                                    'taxonomy' => 'kategorijos',
                                    'field'    => 'term_id',
                                    'terms'    => $parent_category->term_id,
                                ),
                            ),
                            'orderby' => 'menu_order',
                            'order'   => 'ASC',
                            'suppress_filters' => false, // Allow WPML to filter
                        );
                        
                        $menu_items = new WP_Query($args);
                        
                        if ($menu_items->have_posts()) :
                            while ($menu_items->have_posts()) : $menu_items->the_post();
                                $kaina = get_post_meta(get_the_ID(), 'kaina', true);
                        ?>
                        <div class="explore-tab-content-single">
                            <?php if (has_post_thumbnail()) : ?>
                            <div class="dish-image">
                                <?php the_post_thumbnail('medium'); ?>
                            </div>
                            <?php endif; ?>
                            <div class="about-dish">
                                <h5><?php the_title(); ?> 
                                    <?php if ($kaina) : ?>
                                        <span><?php echo esc_html($kaina); ?></span>
                                    <?php endif; ?>
                                </h5>
                                <p><?php the_content(); ?></p>
                            </div>
                        </div>
                        <?php 
                            endwhile;
                            wp_reset_postdata();
                        else : 
                        ?>
                            <p><?php _e('No menu items found in this category.', 'griline'); ?></p>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php else : ?>
        <div class="row">
            <div class="col-12">
                <p style="color: #f25b0a; font-size: 18px; text-align: center; padding: 40px;">
                    <?php _e('No menu categories available for this language.', 'griline'); ?>
                </p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<style>
.subcategory-title {
    margin-top: 30px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.explore-tab-content {
    display: none;
}

.explore-tab-btn.active {
    background-color: #f25b0a !important;
    color: #fff !important;
    border-color: #f25b0a !important;
    opacity: 1 !important;
}

.explore-tab-btn {
    transition: all 0.3s ease;
}

.explore-tab-btn:hover {
    background-color: #f25b0a;
    color: #fff;
    border-color: #f25b0a;
}

.explore-tab-content-single {
    display: flex;
    align-items: flex-start;
    margin-bottom: 30px;
    padding: 20px;
    border-radius: 8px;
    background: rgba(255, 255, 255, 0.05);
}

.dish-image {
    margin-right: 20px;
    flex-shrink: 0;
}

.dish-image img {
    border-radius: 8px;
    max-width: 150px;
    height: auto;
}

.about-dish h5 {
    margin-bottom: 10px;
    color: #fff;
}

.about-dish h5 span {
    color: #f25b0a;
    font-weight: bold;
    margin-left: 10px;
}

.about-dish p {
    color: rgba(255, 255, 255, 0.8);
    line-height: 1.6;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.explore-tab-btn');
    const tabContents = document.querySelectorAll('.explore-tab-content');

    tabButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('data-target');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.style.display = 'none';
            });
            
            // Remove active class from all buttons
            tabButtons.forEach(btn => {
                btn.classList.remove('active');
            });
            
            // Show target content
            const targetContent = document.getElementById(targetId);
            if (targetContent) {
                targetContent.style.display = 'block';
            }
            
            // Add active class to clicked button
            this.classList.add('active');
        });
    });
});
</script> 