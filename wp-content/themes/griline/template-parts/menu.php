<?php
// Get admin settings for visible categories
$visible_categories = get_option('griline_visible_categories', array());
$visible_subcategories = get_option('griline_visible_subcategories', array());
$admin_default_category = get_option('griline_default_category', '');

// Ensure arrays are always arrays (WordPress sometimes returns strings)
if (!is_array($visible_categories)) {
    $visible_categories = array();
}
if (!is_array($visible_subcategories)) {
    $visible_subcategories = array();
}

// Get all parent categories
$all_parent_categories = get_terms(array(
    'taxonomy'   => 'kategorijos',
    'hide_empty' => true,
    'parent'     => 0, // Only top-level categories
    'object_type'=> array('restorano_meniu'),
    'lang'       => apply_filters('wpml_current_language', NULL),
    'orderby'    => 'meta_value_num',
    'order'      => 'ASC',
));

// WPML Translation Fix: Convert admin category IDs to current language
$parent_categories = array();
if (!empty($visible_categories)) {
    $translated_visible_categories = array();
    
    foreach ($visible_categories as $category_id) {
        // Get the translated term ID for current language
        $translated_id = apply_filters('wpml_object_id', $category_id, 'kategorijos', false, apply_filters('wpml_current_language', NULL));
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

// Apply custom ordering
$category_order = get_option('griline_category_order', array());
if (!empty($category_order)) {
    $sorted_categories = array();
    $remaining_categories = $parent_categories;
    
    // First, add categories in the saved order
    foreach ($category_order as $term_id) {
        foreach ($parent_categories as $key => $category) {
            if ($category->term_id == $term_id) {
                $sorted_categories[] = $category;
                unset($remaining_categories[$key]);
                break;
            }
        }
    }
    
    // Add any remaining categories that weren't in the saved order
    foreach ($remaining_categories as $category) {
        $sorted_categories[] = $category;
    }
    
    $parent_categories = $sorted_categories;
}

// Find the default active category
$default_active_category = null;

// First, check if admin set a specific default category
if (!empty($admin_default_category)) {
    foreach ($parent_categories as $category) {
        if ($category->slug === $admin_default_category) {
            $default_active_category = $category;
            break;
        }
    }
}

// If admin default not found or not set, try dienos-pietus
if (!$default_active_category) {
    foreach ($parent_categories as $category) {
        if ($category->slug === 'dienos-pietus') {
            $default_active_category = $category;
            break;
        }
    }
}

// If dienos-pietus not found, use first visible category as fallback
if (!$default_active_category && !empty($parent_categories)) {
    $default_active_category = $parent_categories[0];
}
?>
<!-- ==== explore section start ==== -->
<section class="explore section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header">
                    <h2><?php _e('Meniu', 'griline'); ?></h2>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="explore-tab-btn-wrapper">
                    <?php 
                    foreach ($parent_categories as $category) : 
                        $active_class = ($category->slug === ($default_active_category ? $default_active_category->slug : '')) ? 'active' : '';
                    ?>
                        <a href="#<?php echo $category->slug; ?>" class="button button--tertiary explore-tab-btn <?php echo $active_class; ?>" data-target="<?php echo $category->slug; ?>"><?php echo $category->name; ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <div class="row neutral-row">
            <div class="col-lg-12">
                <div class="explore-tab-wrapper single-item">
                    <?php 
                    foreach ($parent_categories as $parent_category) : 
                        // Get child categories
                        $all_child_categories = get_terms(array(
                            'taxonomy'   => 'kategorijos',
                            'hide_empty' => true,
                            'parent'     => $parent_category->term_id,
                            'object_type'=> array('restorano_meniu'),
                            'lang'       => apply_filters('wpml_current_language', NULL),
                            'orderby'    => 'meta_value_num',
                            'order'      => 'ASC',
                        ));

                        // Filter child categories based on admin settings
                        $child_categories = array();
                        if (!empty($visible_subcategories)) {
                            foreach ($all_child_categories as $child_category) {
                                if (in_array($child_category->term_id, $visible_subcategories)) {
                                    $child_categories[] = $child_category;
                                }
                            }
                        } else {
                            // If no admin settings for subcategories, show all
                            $child_categories = $all_child_categories;
                        }

                        // Apply custom subcategory ordering
                        $subcategory_order = get_option('griline_subcategory_order', array());
                        $parent_key = 'parent_' . $parent_category->term_id;
                        
                        if (!empty($subcategory_order[$parent_key])) {
                            $sorted_subcategories = array();
                            $remaining_subcategories = $child_categories;
                            
                            // First, add subcategories in the saved order
                            foreach ($subcategory_order[$parent_key] as $term_id) {
                                foreach ($child_categories as $key => $subcategory) {
                                    if ($subcategory->term_id == $term_id) {
                                        $sorted_subcategories[] = $subcategory;
                                        unset($remaining_subcategories[$key]);
                                        break;
                                    }
                                }
                            }
                            
                            // Add any remaining subcategories
                            foreach ($remaining_subcategories as $subcategory) {
                                $sorted_subcategories[] = $subcategory;
                            }
                            
                            $child_categories = $sorted_subcategories;
                        }

                    ?>
                    <div class="explore-tab-content" id="<?php echo $parent_category->slug; ?>" style="display: <?php echo ($parent_category->slug === ($default_active_category ? $default_active_category->slug : '')) ? 'block' : 'none'; ?>">
                        <?php 
                        // If there are child categories, display them
                        if (!empty($child_categories)) :
                            foreach ($child_categories as $child_category) :
                        ?>
                            <h3 class="subcategory-title"><?php echo $child_category->name; ?></h3>
                            <?php
                            // Query posts for this child category
                            $args = array(
                                'post_type' => 'restorano_meniu',
                                'posts_per_page' => -1,
                                'tax_query' => array(
                                    array(
                                        'taxonomy' => 'kategorijos',
                                        'field'    => 'term_id',
                                        'terms'    => $child_category->term_id,
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
                                    <h5><?php the_title(); ?> <?php if ($kaina) : ?><span><?php echo esc_html($kaina); ?></span><?php endif; ?></h5>
                                    <p><?php the_content(); ?></p>
                                </div>
                            </div>
                            <?php 
                                endwhile;
                                wp_reset_postdata();
                            else : 
                            ?>
                                <p>No menu items found in this category.</p>
                            <?php endif; ?>
                        <?php 
                            endforeach;
                        else :
                            // No child categories, show items from parent category directly
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
                                    <h5><?php the_title(); ?> <?php if ($kaina) : ?><span><?php echo esc_html($kaina); ?></span><?php endif; ?></h5>
                                    <p><?php the_content(); ?></p>
                                </div>
                            </div>
                        <?php 
                                endwhile;
                                wp_reset_postdata();
                            else : 
                        ?>
                                <p>No menu items found in this category.</p>
                        <?php 
                            endif;
                        endif; 
                        ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
   .explore-tab-btn-wrapper .active { 
    background-color: #f25b0a !important;
    color: #fff !important;
    border-color: #f25b0a !important;
    opacity: 1 !important;
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