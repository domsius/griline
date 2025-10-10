<?php
/**
 * Template Name: Dienos Pietūs
 */

get_header('page');

get_template_part('template-parts/page-banner');

// Get the dienos-pietus category
$dienos_pietus_category = get_term_by('slug', 'dienos-pietus', 'kategorijos');

if ($dienos_pietus_category) {
    // Get child categories of dienos-pietus
    $child_categories = get_terms(array(
        'taxonomy'   => 'kategorijos',
        'hide_empty' => true,
        'parent'     => $dienos_pietus_category->term_id,
        'object_type'=> array('restorano_meniu'),
        'lang'       => apply_filters('wpml_current_language', NULL),
        'orderby'    => 'meta_value_num',
        'order'      => 'ASC',
    ));
}
?>

<section class="explore section-space">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="section-header">
                    <h2><?php _e('Dienos Pietūs', 'griline'); ?></h2>
                </div>
            </div>
        </div>
        <div class="row neutral-row">
            <div class="col-lg-12">
                <div class="explore-tab-wrapper">
                    <div class="explore-tab-content-test" style="display: block;">
                        <?php
                        if ($dienos_pietus_category) {
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
                                    <p><?php _e('Šioje kategorijoje patiekalų nerasta.', 'griline'); ?></p>
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
                                            'terms'    => $dienos_pietus_category->term_id,
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
                                    <p><?php _e('Šiuo metu dienos pietų pasiūlymų nėra.', 'griline'); ?></p>
                            <?php 
                                endif;
                            endif;
                        } else {
                            echo '<p>' . __('Dienos pietų kategorija nerasta.', 'griline') . '</p>';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.subcategory-title {
    margin-top: 30px;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}
</style>

<?php get_footer(); ?> 