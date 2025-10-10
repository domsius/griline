<?php
/**
 * Menu Admin Interface
 * Allows admin to control which categories/subcategories to display in the menu
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

class Griline_Menu_Admin {
    
    public function __construct() {
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'register_settings'));
        add_action('wp_ajax_save_menu_settings', array($this, 'save_menu_settings'));
        add_action('wp_ajax_save_menu_order', array($this, 'save_menu_order'));
        add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts'));
    }
    
    /**
     * Add admin menu page
     */
    public function add_admin_menu() {
        add_menu_page(
            __('Menu Settings', 'griline'),
            __('Menu Settings', 'griline'),
            'manage_options',
            'griline-menu-settings',
            array($this, 'admin_page'),
            'dashicons-food',
            30
        );
    }
    
    /**
     * Enqueue admin scripts
     */
    public function enqueue_admin_scripts($hook) {
        if ($hook !== 'toplevel_page_griline-menu-settings') {
            return;
        }
        
        wp_enqueue_script('jquery-ui-sortable');
        wp_localize_script('jquery', 'griline_admin_ajax', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('griline_menu_order')
        ));
    }

    /**
     * Register settings
     */
    public function register_settings() {
        register_setting('griline_menu_settings', 'griline_visible_categories');
        register_setting('griline_menu_settings', 'griline_visible_subcategories');
        register_setting('griline_menu_settings', 'griline_default_category');
        register_setting('griline_menu_settings', 'griline_category_order');
        register_setting('griline_menu_settings', 'griline_subcategory_order');
    }
    
    /**
     * Get category count for display
     */
    private function get_category_post_count($term_id) {
        $count = wp_count_posts('restorano_meniu');
        $args = array(
            'post_type' => 'restorano_meniu',
            'tax_query' => array(
                array(
                    'taxonomy' => 'kategorijos',
                    'field'    => 'term_id',
                    'terms'    => $term_id,
                )
            ),
            'posts_per_page' => -1,
            'fields' => 'ids'
        );
        $posts = new WP_Query($args);
        return $posts->found_posts;
    }

    /**
     * Save menu order via AJAX
     */
    public function save_menu_order() {
        check_ajax_referer('griline_menu_order', 'nonce');
        
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
        
        $category_order = isset($_POST['category_order']) ? array_map('intval', $_POST['category_order']) : array();
        $subcategory_order = isset($_POST['subcategory_order']) ? array_map('intval', $_POST['subcategory_order']) : array();
        
        update_option('griline_category_order', $category_order);
        update_option('griline_subcategory_order', $subcategory_order);
        
        wp_send_json_success(array(
            'message' => __('Order saved successfully!', 'griline')
        ));
    }

    /**
     * Get sorted categories based on saved order
     */
    private function get_sorted_categories($categories) {
        $category_order = get_option('griline_category_order', array());
        
        if (empty($category_order)) {
            return $categories;
        }
        
        $sorted = array();
        $remaining = $categories;
        
        // First, add categories in the saved order
        foreach ($category_order as $term_id) {
            foreach ($categories as $key => $category) {
                if ($category->term_id == $term_id) {
                    $sorted[] = $category;
                    unset($remaining[$key]);
                    break;
                }
            }
        }
        
        // Add any remaining categories that weren't in the saved order
        foreach ($remaining as $category) {
            $sorted[] = $category;
        }
        
        return $sorted;
    }

    /**
     * Get sorted subcategories based on saved order
     */
    private function get_sorted_subcategories($subcategories, $parent_id) {
        $subcategory_order = get_option('griline_subcategory_order', array());
        $parent_key = 'parent_' . $parent_id;
        
        if (empty($subcategory_order[$parent_key])) {
            return $subcategories;
        }
        
        $sorted = array();
        $remaining = $subcategories;
        
        // First, add subcategories in the saved order
        foreach ($subcategory_order[$parent_key] as $term_id) {
            foreach ($subcategories as $key => $subcategory) {
                if ($subcategory->term_id == $term_id) {
                    $sorted[] = $subcategory;
                    unset($remaining[$key]);
                    break;
                }
            }
        }
        
        // Add any remaining subcategories
        foreach ($remaining as $subcategory) {
            $sorted[] = $subcategory;
        }
        
        return $sorted;
    }

    /**
     * Admin page content
     */
    public function admin_page() {
        // Get all parent categories
        $all_parent_categories = get_terms(array(
            'taxonomy'   => 'kategorijos',
            'hide_empty' => false,
            'parent'     => 0,
            'object_type'=> array('restorano_meniu'),
        ));
        
        // Sort categories based on saved order
        $parent_categories = $this->get_sorted_categories($all_parent_categories);
        
        // Get saved settings
        $visible_categories = get_option('griline_visible_categories', array());
        $visible_subcategories = get_option('griline_visible_subcategories', array());
        $default_category = get_option('griline_default_category', '');
        
        // Ensure arrays are always arrays (WordPress sometimes returns strings)
        if (!is_array($visible_categories)) {
            $visible_categories = array();
        }
        if (!is_array($visible_subcategories)) {
            $visible_subcategories = array();
        }
        
        ?>
        <div class="wrap">
            <h1><?php _e('Menu Display Settings', 'griline'); ?></h1>
            
            <div class="menu-admin-notice">
                <strong><?php _e('How it works:', 'griline'); ?></strong><br>
                <?php _e('Use this interface to control which menu categories and subcategories appear on your website. Only checked categories will be visible to visitors. If no categories are selected, all categories will be shown by default.', 'griline'); ?>
            </div>
            
            <form method="post" action="options.php">
                <?php settings_fields('griline_menu_settings'); ?>
                
                <div class="menu-admin-container">
                    <div class="menu-admin-section">
                        <h2><?php _e('Visible Categories', 'griline'); ?></h2>
                        <p><?php _e('Select which main categories should be displayed as tabs in the menu.', 'griline'); ?></p>
                        
                        <div class="admin-controls">
                            <button type="button" id="select-all-categories" class="button button-secondary">
                                <?php _e('Select All Categories', 'griline'); ?>
                            </button>
                            <button type="button" id="deselect-all-categories" class="button button-secondary">
                                <?php _e('Deselect All Categories', 'griline'); ?>
                            </button>
                            <button type="button" id="save-order" class="button button-primary">
                                <?php _e('Save Order', 'griline'); ?>
                            </button>
                            <span id="save-status" class="save-status"></span>
                        </div>
                        
                        <div class="sort-notice">
                            <strong><?php _e('💡 Tip:', 'griline'); ?></strong>
                            <?php _e('Drag and drop categories to reorder them. The order here will be the same as on your website.', 'griline'); ?>
                        </div>
                        
                        <div class="category-list sortable-categories" id="sortable-categories">
                            <?php foreach ($parent_categories as $category) : ?>
                                <div class="category-item" data-term-id="<?php echo $category->term_id; ?>">
                                    <div class="category-header">
                                        <span class="drag-handle">⋮⋮</span>
                                        <label>
                                            <input type="checkbox" 
                                                   name="griline_visible_categories[]" 
                                                   value="<?php echo $category->term_id; ?>"
                                                   <?php checked(in_array($category->term_id, $visible_categories)); ?>>
                                            <strong><?php echo esc_html($category->name); ?></strong>
                                            <span class="category-slug">(<?php echo $category->slug; ?>)</span>
                                            <span class="post-count"><?php echo $this->get_category_post_count($category->term_id); ?> items</span>
                                        </label>
                                    </div>
                                    
                                    <?php
                                    // Get child categories
                                    $all_child_categories = get_terms(array(
                                        'taxonomy'   => 'kategorijos',
                                        'hide_empty' => false,
                                        'parent'     => $category->term_id,
                                        'object_type'=> array('restorano_meniu'),
                                    ));
                               ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    
                    <div class="menu-admin-section">
                        <h2><?php _e('Default Active Category', 'griline'); ?></h2>
                        <p><?php _e('Choose which category should be active by default when the page loads.', 'griline'); ?></p>
                        
                        <select name="griline_default_category">
                            <option value=""><?php _e('Auto (first visible category)', 'griline'); ?></option>
                            <?php foreach ($parent_categories as $category) : 
                                if (empty($visible_categories) || in_array($category->term_id, $visible_categories)) : ?>
                                    <option value="<?php echo $category->slug; ?>" 
                                            <?php selected($default_category, $category->slug); ?>>
                                        <?php echo esc_html($category->name); ?>
                                    </option>
                                <?php endif;
                            endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <?php submit_button(__('Save Settings', 'griline')); ?>
            </form>
            
            <div class="menu-admin-section">
                <h2><?php _e('Current Menu Preview', 'griline'); ?></h2>
                <p><?php _e('This shows what categories will be visible on the frontend based on your current settings:', 'griline'); ?></p>
                
                <?php
                $preview_categories = array();
                if (!empty($visible_categories)) {
                    foreach ($parent_categories as $category) {
                        if (in_array($category->term_id, $visible_categories)) {
                            $preview_categories[] = $category;
                        }
                    }
                } else {
                    $preview_categories = $parent_categories;
                }
                
                if (!empty($preview_categories)) : ?>
                    <div class="menu-preview">
                        <?php foreach ($preview_categories as $category) : ?>
                            <div class="preview-tab">
                                <?php echo esc_html($category->name); ?>
                                <?php if ($category->slug === $default_category) : ?>
                                    <span class="default-indicator"><?php _e('(Default)', 'griline'); ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else : ?>
                    <p><em><?php _e('No categories selected - all categories will be shown.', 'griline'); ?></em></p>
                <?php endif; ?>
            </div>
        </div>
        
        <style>
        .menu-admin-container {
            max-width: 800px;
        }
        
        .menu-admin-section {
            background: #fff;
            border: 1px solid #ccd0d4;
            padding: 20px;
            margin: 20px 0;
            border-radius: 4px;
        }
        
        .category-list {
            margin-top: 15px;
        }
        
        .category-item {
            border: 1px solid #ddd;
            padding: 15px;
            margin-bottom: 10px;
            border-radius: 4px;
            background: #f9f9f9;
            cursor: move;
            transition: all 0.2s ease;
        }
        
        .category-item:hover {
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .category-item.ui-sortable-helper {
            box-shadow: 0 5px 15px rgba(0,0,0,0.2);
            transform: rotate(2deg);
        }
        
        .category-header {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        
        .category-item label {
            font-size: 14px;
            cursor: pointer;
            display: block;
            flex: 1;
        }
        
        .drag-handle {
            font-size: 18px;
            color: #666;
            margin-right: 10px;
            cursor: move;
            user-select: none;
            line-height: 1;
        }
        
        .drag-handle:hover {
            color: #f25b0a;
        }
        
        .category-slug {
            color: #666;
            font-size: 12px;
            font-style: italic;
        }
        
        .post-count {
            color: #007cba;
            font-size: 11px;
            font-weight: bold;
            margin-left: 8px;
            background: #f0f6fc;
            padding: 2px 6px;
            border-radius: 3px;
        }
        
        .subcategory-list {
            margin-left: 20px;
            margin-top: 10px;
            padding: 10px;
            background: #fff;
            border-radius: 4px;
            border: 1px dashed #ddd;
        }
        
        .subcategory-list h4 {
            margin: 0 0 10px 0;
            font-size: 13px;
            color: #666;
        }
        
        .subcategory-item-wrapper {
            display: flex;
            align-items: center;
            margin: 5px 0;
            padding: 5px;
            background: #f0f0f0;
            border-radius: 3px;
            cursor: move;
            transition: all 0.2s ease;
        }
        
        .subcategory-item-wrapper:hover {
            background: #e0e0e0;
        }
        
        .subcategory-item-wrapper.ui-sortable-helper {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            transform: scale(1.02);
        }
        
        .subcategory-item {
            display: block;
            font-size: 13px;
            cursor: pointer;
            flex: 1;
        }
        
        .subcategory-item input {
            margin-right: 8px;
        }
        
        .drag-handle-small {
            font-size: 12px;
            color: #999;
            margin-right: 8px;
            cursor: move;
            user-select: none;
        }
        
        .drag-handle-small:hover {
            color: #f25b0a;
        }
        
        .admin-controls {
            margin-bottom: 15px;
        }
        
        .admin-controls .button {
            margin-right: 10px;
        }
        
        .menu-admin-notice {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
        }
        
        .sort-notice {
            background: #e7f3ff;
            border: 1px solid #b8d4f0;
            color: #2271b1;
            padding: 10px 15px;
            border-radius: 4px;
            margin-bottom: 15px;
            font-size: 13px;
        }
        
        .save-status {
            margin-left: 10px;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            display: none;
        }
        
        .save-status.success {
            color: #00a32a;
            background: #f0f9ff;
            border: 1px solid #c6e9c7;
            display: inline-block;
        }
        
        .save-status.error {
            color: #d63638;
            background: #fcf0f1;
            border: 1px solid #f1a2a2;
            display: inline-block;
        }
        
        .menu-preview {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 15px;
        }
        
        .preview-tab {
            background: #f25b0a;
            color: #fff;
            padding: 8px 15px;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
        }
        
        .default-indicator {
            background: rgba(255, 255, 255, 0.2);
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 11px;
            margin-left: 8px;
        }
        
        .sortable-placeholder {
            background: #f0f0f0;
            border: 2px dashed #ccc;
            margin-bottom: 10px;
            border-radius: 4px;
            visibility: visible !important;
        }
        
        .sortable-placeholder-small {
            background: #f8f8f8;
            border: 1px dashed #ccc;
            margin: 5px 0;
            border-radius: 3px;
            visibility: visible !important;
            height: 30px;
        }
        </style>
        
        <script>
        jQuery(document).ready(function($) {
            // Initialize sortable for categories
            $('#sortable-categories').sortable({
                handle: '.drag-handle',
                placeholder: 'sortable-placeholder',
                tolerance: 'pointer',
                start: function(e, ui) {
                    ui.placeholder.height(ui.item.height());
                }
            });
            
            // Initialize sortable for subcategories
            $('.sortable-subcategories').sortable({
                handle: '.drag-handle-small',
                placeholder: 'sortable-placeholder-small',
                tolerance: 'pointer',
                start: function(e, ui) {
                    ui.placeholder.height(ui.item.height());
                }
            });
            
            // Save order functionality
            $('#save-order').on('click', function() {
                var $button = $(this);
                var $status = $('#save-status');
                
                $button.prop('disabled', true).text('<?php _e('Saving...', 'griline'); ?>');
                $status.removeClass('success error').hide();
                
                // Get category order
                var categoryOrder = [];
                $('#sortable-categories .category-item').each(function() {
                    categoryOrder.push(parseInt($(this).data('term-id')));
                });
                
                // Get subcategory order
                var subcategoryOrder = {};
                $('.sortable-subcategories').each(function() {
                    var parentId = $(this).data('parent-id');
                    var order = [];
                    $(this).find('.subcategory-item-wrapper').each(function() {
                        order.push(parseInt($(this).data('term-id')));
                    });
                    subcategoryOrder['parent_' + parentId] = order;
                });
                
                // Send AJAX request
                $.post(griline_admin_ajax.ajax_url, {
                    action: 'save_menu_order',
                    nonce: griline_admin_ajax.nonce,
                    category_order: categoryOrder,
                    subcategory_order: subcategoryOrder
                }, function(response) {
                    $button.prop('disabled', false).text('<?php _e('Save Order', 'griline'); ?>');
                    
                    if (response.success) {
                        $status.addClass('success').text(response.data.message).show();
                        setTimeout(function() {
                            $status.fadeOut();
                        }, 3000);
                    } else {
                        $status.addClass('error').text('<?php _e('Error saving order', 'griline'); ?>').show();
                    }
                }).fail(function() {
                    $button.prop('disabled', false).text('<?php _e('Save Order', 'griline'); ?>');
                    $status.addClass('error').text('<?php _e('Error saving order', 'griline'); ?>').show();
                });
            });
            
            // Select all categories
            $('#select-all-categories').on('click', function() {
                $('.category-list input[name="griline_visible_categories[]"]').prop('checked', true);
                $('.subcategory-list input[name="griline_visible_subcategories[]"]').prop('checked', true);
            });
            
            // Deselect all categories
            $('#deselect-all-categories').on('click', function() {
                $('.category-list input[name="griline_visible_categories[]"]').prop('checked', false);
                $('.subcategory-list input[name="griline_visible_subcategories[]"]').prop('checked', false);
            });
            
            // Auto-check/uncheck subcategories when parent is checked/unchecked
            $('.category-list input[name="griline_visible_categories[]"]').on('change', function() {
                var $parentItem = $(this).closest('.category-item');
                var $subcategoryInputs = $parentItem.find('.subcategory-list input[name="griline_visible_subcategories[]"]');
                
                if ($(this).is(':checked')) {
                    $subcategoryInputs.prop('checked', true);
                } else {
                    $subcategoryInputs.prop('checked', false);
                }
            });
        });
        </script>
        <?php
    }
}

// Initialize the admin class
new Griline_Menu_Admin(); 