# Loyalty Card Number Validation System - Implementation Guide

This guide provides complete instructions for implementing a loyalty card number validation system with admin management, AJAX validation, and automatic card status tracking.

## Table of Contents
1. [Overview](#overview)
2. [Database Setup](#database-setup)
3. [Backend Implementation](#backend-implementation)
4. [Frontend Implementation](#frontend-implementation)
5. [Admin Interface](#admin-interface)
6. [Testing](#testing)

---

## Overview

This system adds:
- **Card number validation** - Database of valid card numbers that can be validated before form submission
- **AJAX real-time validation** - Users see instant feedback when entering card numbers
- **Admin management** - Interface to generate, view, and manage card numbers
- **Automatic tracking** - Cards are automatically marked as used/available based on member status
- **Multi-language support** - Validation messages in Lithuanian, English, and Russian

---

## Database Setup

### 1. Create Card Numbers Table

Add this function to your `functions.php`:

```php
/**
 * Create loyalty card numbers table
 */
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

    // Populate initial card numbers
    populate_initial_card_numbers();
}
```

### 2. Populate Initial Card Numbers

Add this function to populate your first batch of card numbers:

```php
/**
 * Populate initial card numbers (200901-201000)
 */
function populate_initial_card_numbers() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_card_numbers';

    // Check if already populated
    $count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    if ($count > 0) {
        return;
    }

    $values = array();
    for ($i = 200901; $i <= 201000; $i++) {
        $values[] = $wpdb->prepare("(%s, 0, NULL, %s, NULL)", $i, current_time('mysql'));
    }

    if (!empty($values)) {
        $sql = "INSERT INTO $table_name (card_number, is_used, used_by_member_id, created_at, used_at) VALUES ";
        $sql .= implode(', ', $values);
        $wpdb->query($sql);
        error_log('Loyalty Card Numbers: Populated 100 initial cards (200901-201000)');
    }
}
```

### 3. Run Table Creation on Init

Add this to your `functions.php` to check and create tables:

```php
/**
 * Check and create tables on admin init
 */
add_action('admin_init', function() {
    global $wpdb;

    // Check loyalty card numbers table
    $card_numbers_table = $wpdb->prefix . 'loyalty_card_numbers';
    if ($wpdb->get_var("SHOW TABLES LIKE '$card_numbers_table'") != $card_numbers_table) {
        create_loyalty_card_numbers_table();
    }
});
```

---

## Backend Implementation

### 1. Card Validation Function

Add to `functions.php`:

```php
/**
 * Validate loyalty card number
 * Returns: array('valid' => bool, 'error_type' => string)
 */
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
```

### 2. Validation Messages Helper

Add multi-language validation messages:

```php
/**
 * Get card validation messages by language
 */
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
```

### 3. AJAX Validation Endpoint

Add AJAX handler for real-time validation:

```php
/**
 * AJAX: Validate loyalty card number
 */
function validate_loyalty_card_ajax() {
    check_ajax_referer('loyalty_card_validation', 'security');

    $card_number = sanitize_text_field($_POST['card_number']);
    $current_lang = isset($_POST['lang']) ? sanitize_text_field($_POST['lang']) : 'lt';

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
```

### 4. Update Form Handler to Validate Cards

In your existing form submission handler, add card validation BEFORE saving the member:

```php
// In your handle_loyalty_form_submission() function, add this BEFORE inserting member:

// Validate card number
$card_number = sanitize_text_field($_POST['korteles-nr']); // Adjust field name as needed
$card_validation = validate_loyalty_card($card_number);

if (!$card_validation['valid']) {
    error_log('Loyalty Form: Invalid card number: ' . $card_number);
    wp_redirect(add_query_arg('loyalty_error', 'invalid_card', wp_get_referer()));
    exit;
}

// ... proceed with saving member ...
```

### 5. Mark Card as Used After Registration

After successfully saving a member, mark the card as used:

```php
// In your handle_loyalty_form_submission() function, add this AFTER inserting member:

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
```

### 6. Free Card When Member is Deleted

Update your member deletion handler:

```php
// In your display_loyalty_members_page() function, update the delete action:

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
```

---

## Frontend Implementation

### 1. Update Form Error Display

In your loyalty page template (e.g., `page-lojalumas.php`), update the error message section to handle card validation errors:

```php
// Display error message
if (isset($_GET['loyalty_error'])) {
    echo '<div class="loyalty-error-message" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">';

    $error_type = $_GET['loyalty_error'];

    if ($error_type === 'invalid_card') {
        echo get_card_validation_message('invalid', $current_lang);
    } else {
        // Your default error message
        switch($current_lang) {
            case 'en':
                echo 'An error occurred. Please try again.';
                break;
            case 'ru':
                echo 'Произошла ошибка. Пожалуйста, попробуйте снова.';
                break;
            default:
                echo 'Įvyko klaida. Prašome bandyti dar kartą.';
        }
    }
    echo '</div>';
}
```

### 2. Add Validation Message Container

Add a container for the validation message below your card number input field:

```html
<div class="form-group">
    <label for="korteles-nr"><?php echo esc_html($l['card_number']); ?></label>
    <input type="text" id="korteles-nr" name="korteles-nr">
    <div id="card-validation-message" class="card-validation-message"></div>
</div>
```

### 3. Add AJAX Validation JavaScript

Add this JavaScript to your form page (in the existing `<script>` tag or create a new one):

```javascript
document.addEventListener('DOMContentLoaded', function() {
    const cardNumberField = document.getElementById('korteles-nr');
    const cardValidationMessage = document.getElementById('card-validation-message');

    // Card validation state
    let cardIsValid = false;
    let cardValidationTimeout = null;

    // AJAX Card Number Validation
    if (cardNumberField && typeof jQuery !== 'undefined') {
        cardNumberField.addEventListener('input', function() {
            const cardNumber = this.value.trim();

            // Clear previous timeout
            if (cardValidationTimeout) {
                clearTimeout(cardValidationTimeout);
            }

            // Reset validation state
            cardIsValid = false;
            cardValidationMessage.textContent = '';
            cardValidationMessage.className = 'card-validation-message';

            // Clear red border when user is typing
            if (cardNumber !== '') {
                this.style.borderColor = '';
                this.style.borderWidth = '';
            }

            // Only validate if there's input
            if (cardNumber === '') {
                return;
            }

            // Show validating state
            cardValidationMessage.textContent = '⏳ <?php
                switch($current_lang) {
                    case 'en': echo 'Validating...'; break;
                    case 'ru': echo 'Проверка...'; break;
                    default: echo 'Tikrinama...';
                }
            ?>';
            cardValidationMessage.className = 'card-validation-message validating';

            // Debounce validation
            cardValidationTimeout = setTimeout(function() {
                jQuery.ajax({
                    url: '<?php echo admin_url('admin-ajax.php'); ?>',
                    type: 'POST',
                    data: {
                        action: 'validate_loyalty_card',
                        security: '<?php echo wp_create_nonce('loyalty_card_validation'); ?>',
                        card_number: cardNumber,
                        lang: '<?php echo $current_lang; ?>'
                    },
                    success: function(response) {
                        if (response.success) {
                            cardIsValid = true;
                            cardValidationMessage.textContent = '✓ ' + response.data.message;
                            cardValidationMessage.className = 'card-validation-message valid';
                            cardNumberField.style.borderColor = '#28a745';
                            cardNumberField.style.borderWidth = '2px';
                        } else {
                            cardIsValid = false;
                            cardValidationMessage.textContent = '✗ ' + response.data.message;
                            cardValidationMessage.className = 'card-validation-message invalid';
                            cardNumberField.style.borderColor = '#dc3545';
                            cardNumberField.style.borderWidth = '2px';
                        }
                    },
                    error: function() {
                        cardIsValid = false;
                        cardValidationMessage.textContent = '✗ <?php
                            switch($current_lang) {
                                case 'en': echo 'Validation error'; break;
                                case 'ru': echo 'Ошибка проверки'; break;
                                default: echo 'Tikrinimo klaida';
                            }
                        ?>';
                        cardValidationMessage.className = 'card-validation-message invalid';
                    }
                });
            }, 500); // 500ms debounce
        });
    }

    // Update your existing form submit handler to check card validation
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        let isValid = true;

        // ... your existing validation ...

        // Check card validation
        if (!cardIsValid && cardNumberField.value.trim() !== '') {
            cardNumberField.style.borderColor = '#dc3545';
            cardNumberField.style.borderWidth = '2px';
            isValid = false;

            if (cardValidationMessage.textContent === '') {
                cardValidationMessage.textContent = '✗ <?php echo get_card_validation_message("required", $current_lang); ?>';
                cardValidationMessage.className = 'card-validation-message invalid';
            }
        }

        // If validation passes, submit the form
        if (isValid) {
            form.submit();
        } else {
            // Scroll to first invalid field
            const firstInvalid = requiredFields.find(field => field.style.borderColor === 'rgb(220, 53, 69)');
            if (firstInvalid) {
                firstInvalid.scrollIntoView({ behavior: 'smooth', block: 'center' });
                firstInvalid.focus();
            }
        }
    });
});
```

### 4. Add CSS for Validation Messages

Add to your `style.css`:

```css
/* Card validation message styles */
.card-validation-message {
    margin-top: 8px;
    font-size: 0.9rem;
    font-weight: 500;
    padding: 8px 12px;
    border-radius: 4px;
    transition: all 0.3s ease;
    display: block;
}

.card-validation-message.valid {
    color: #28a745;
    background-color: #d4edda;
    border: 1px solid #c3e6cb;
}

.card-validation-message.invalid {
    color: #dc3545;
    background-color: #f8d7da;
    border: 1px solid #f5c6cb;
}

.card-validation-message.validating {
    color: #666;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.card-validation-message:empty {
    display: none;
    padding: 0;
    margin: 0;
}
```

---

## Admin Interface

### 1. Create Admin Page File

Create `wp-content/themes/your-theme/inc/admin-card-numbers.php`:

```php
<?php
/**
 * Admin Page for Managing Loyalty Card Numbers
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add admin menu item
 */
function add_loyalty_card_numbers_menu() {
    add_submenu_page(
        'loyalty-members', // Parent menu slug (adjust to match your admin page)
        'Lojalumo kortelių numeriai',
        'Kortelių numeriai',
        'manage_options',
        'loyalty-card-numbers',
        'render_loyalty_card_numbers_page'
    );
}
add_action('admin_menu', 'add_loyalty_card_numbers_menu', 11);

/**
 * Handle freeing up a card number
 */
function handle_free_card_number() {
    if (!isset($_GET['action']) || $_GET['action'] !== 'free_card' || !isset($_GET['card_id'])) {
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    $card_id = intval($_GET['card_id']);
    check_admin_referer('free_card_' . $card_id);

    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_card_numbers';
    $members_table = $wpdb->prefix . 'loyalty_members';

    // Get the card details
    $card = $wpdb->get_row($wpdb->prepare(
        "SELECT * FROM $table_name WHERE id = %d",
        $card_id
    ));

    if (!$card) {
        add_settings_error('loyalty_cards', 'card_not_found', 'Kortelė nerasta', 'error');
        return;
    }

    // Check if member still exists with this card number
    if ($card->used_by_member_id) {
        $member_exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $members_table WHERE id = %d",
            $card->used_by_member_id
        ));

        if ($member_exists) {
            add_settings_error(
                'loyalty_cards',
                'member_exists',
                sprintf(
                    'Negalima atlaisvinti kortelės - narys #%d vis dar egzistuoja. Pirmiausia ištrinkite narį iš <a href="?page=loyalty-members">Lojalumo narių</a> puslapio.',
                    $card->used_by_member_id
                ),
                'error'
            );
            return;
        }
    }

    // Safe to free the card (member was already deleted)
    $result = $wpdb->update(
        $table_name,
        array(
            'is_used' => 0,
            'used_by_member_id' => NULL,
            'used_at' => NULL
        ),
        array('id' => $card_id),
        array('%d', '%d', '%s'),
        array('%d')
    );

    if ($result !== false) {
        add_settings_error('loyalty_cards', 'card_freed', 'Kortelės numeris sėkmingai atlaisvintas', 'success');
    } else {
        add_settings_error('loyalty_cards', 'card_free_error', 'Nepavyko atlaisvinti kortelės numerio', 'error');
    }
}
add_action('admin_init', 'handle_free_card_number');

/**
 * Handle bulk card number generation
 */
function handle_bulk_card_generation() {
    if (!isset($_POST['generate_cards_nonce']) || !wp_verify_nonce($_POST['generate_cards_nonce'], 'generate_cards')) {
        return;
    }

    if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
    }

    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_card_numbers';

    $start_number = intval($_POST['start_number']);
    $end_number = intval($_POST['end_number']);

    if ($start_number >= $end_number || $start_number < 1 || $end_number < 1) {
        add_settings_error('loyalty_cards', 'invalid_range', 'Neteisingas numerių diapazonas', 'error');
        return;
    }

    $total_to_generate = $end_number - $start_number + 1;

    if ($total_to_generate > 10000) {
        add_settings_error('loyalty_cards', 'too_many', 'Per daug kortelių (maksimaliai 10,000 vienu metu)', 'error');
        return;
    }

    $values = array();
    $generated = 0;
    $skipped = 0;

    for ($i = $start_number; $i <= $end_number; $i++) {
        // Check if card number already exists
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE card_number = %s",
            $i
        ));

        if ($exists) {
            $skipped++;
            continue;
        }

        $values[] = $wpdb->prepare("(%s, 0, NULL, %s, NULL)", $i, current_time('mysql'));
        $generated++;
    }

    if (!empty($values)) {
        // Insert in batches of 100
        $batches = array_chunk($values, 100);

        foreach ($batches as $batch) {
            $sql = "INSERT INTO $table_name (card_number, is_used, used_by_member_id, created_at, used_at) VALUES ";
            $sql .= implode(', ', $batch);
            $wpdb->query($sql);
        }

        add_settings_error('loyalty_cards', 'success', sprintf(
            'Sėkmingai sugeneruota %d naujų kortelių numerių. Praleista %d (jau egzistuoja).',
            $generated,
            $skipped
        ), 'success');
    } else {
        add_settings_error('loyalty_cards', 'no_new', 'Visi numeriai šiame diapazone jau egzistuoja', 'warning');
    }
}
add_action('admin_init', 'handle_bulk_card_generation');

/**
 * Render the admin page
 */
function render_loyalty_card_numbers_page() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'loyalty_card_numbers';

    // Get statistics
    $total_cards = $wpdb->get_var("SELECT COUNT(*) FROM $table_name");
    $used_cards = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE is_used = 1");
    $available_cards = $total_cards - $used_cards;

    // Pagination
    $per_page = 50;
    $current_page = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
    $offset = ($current_page - 1) * $per_page;

    // Search and filter
    $search = isset($_GET['s']) ? sanitize_text_field($_GET['s']) : '';
    $status_filter = isset($_GET['status']) ? sanitize_text_field($_GET['status']) : 'all';

    // Build query
    $where = array();
    if (!empty($search)) {
        $where[] = $wpdb->prepare("card_number LIKE %s", '%' . $wpdb->esc_like($search) . '%');
    }
    if ($status_filter === 'used') {
        $where[] = "is_used = 1";
    } elseif ($status_filter === 'available') {
        $where[] = "is_used = 0";
    }

    $where_sql = !empty($where) ? ' WHERE ' . implode(' AND ', $where) : '';

    // Get total filtered count
    $total_filtered = $wpdb->get_var("SELECT COUNT(*) FROM $table_name" . $where_sql);
    $total_pages = ceil($total_filtered / $per_page);

    // Get cards
    $cards = $wpdb->get_results(
        "SELECT * FROM $table_name" . $where_sql . " ORDER BY id ASC LIMIT $per_page OFFSET $offset"
    );

    // Get the highest card number to suggest next range
    $highest_card = $wpdb->get_var("SELECT MAX(CAST(card_number AS UNSIGNED)) FROM $table_name");
    $suggested_start = $highest_card ? $highest_card + 1 : 200901;
    $suggested_end = $suggested_start + 99;

    ?>
    <div class="wrap">
        <h1>Lojalumo kortelių numeriai</h1>

        <?php settings_errors('loyalty_cards'); ?>

        <!-- Statistics -->
        <div class="card" style="max-width: 100%; margin-bottom: 20px;">
            <div class="card-body" style="padding: 20px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <h2 style="margin-top: 0;">Statistika</h2>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                    <div style="padding: 15px; background: #f0f6fc; border-left: 4px solid #0073aa; border-radius: 4px;">
                        <div style="font-size: 14px; color: #646970; margin-bottom: 5px;">Viso kortelių</div>
                        <div style="font-size: 28px; font-weight: 600; color: #0073aa;"><?php echo number_format($total_cards); ?></div>
                    </div>
                    <div style="padding: 15px; background: #f0f8f0; border-left: 4px solid #46b450; border-radius: 4px;">
                        <div style="font-size: 14px; color: #646970; margin-bottom: 5px;">Laisvos kortelės</div>
                        <div style="font-size: 28px; font-weight: 600; color: #46b450;"><?php echo number_format($available_cards); ?></div>
                    </div>
                    <div style="padding: 15px; background: #fff8f0; border-left: 4px solid #f56e28; border-radius: 4px;">
                        <div style="font-size: 14px; color: #646970; margin-bottom: 5px;">Panaudotos kortelės</div>
                        <div style="font-size: 28px; font-weight: 600; color: #f56e28;"><?php echo number_format($used_cards); ?></div>
                    </div>
                    <div style="padding: 15px; background: #f8f9fa; border-left: 4px solid #6c757d; border-radius: 4px;">
                        <div style="font-size: 14px; color: #646970; margin-bottom: 5px;">Panaudojimo %</div>
                        <div style="font-size: 28px; font-weight: 600; color: #6c757d;">
                            <?php echo $total_cards > 0 ? number_format(($used_cards / $total_cards) * 100, 1) : '0'; ?>%
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Generate New Cards Form -->
        <div class="card" style="max-width: 100%; margin-bottom: 20px;">
            <div class="card-body" style="padding: 20px; background: #fff; border: 1px solid #ccd0d4; box-shadow: 0 1px 1px rgba(0,0,0,.04);">
                <h2 style="margin-top: 0;">Generuoti naujus numerius</h2>
                <form method="post" action="">
                    <?php wp_nonce_field('generate_cards', 'generate_cards_nonce'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="start_number">Pradžios numeris</label></th>
                            <td>
                                <input type="number" name="start_number" id="start_number"
                                       value="<?php echo esc_attr($suggested_start); ?>"
                                       class="regular-text" required min="1">
                                <p class="description">Siūlomas: <?php echo $suggested_start; ?></p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="end_number">Pabaigos numeris</label></th>
                            <td>
                                <input type="number" name="end_number" id="end_number"
                                       value="<?php echo esc_attr($suggested_end); ?>"
                                       class="regular-text" required min="1">
                                <p class="description">Siūlomas: <?php echo $suggested_end; ?> (sugeneruos 100 kortelių)</p>
                            </td>
                        </tr>
                    </table>
                    <?php submit_button('Generuoti korteles', 'primary', 'submit', false); ?>
                </form>
            </div>
        </div>

        <!-- Search and Filter -->
        <div style="background: #fff; padding: 15px; border: 1px solid #ccd0d4; margin-bottom: 10px;">
            <form method="get" action="">
                <input type="hidden" name="page" value="loyalty-card-numbers">
                <div style="display: flex; gap: 10px; align-items: flex-end;">
                    <div>
                        <label for="s">Ieškoti kortelės numerio:</label><br>
                        <input type="text" name="s" id="s" value="<?php echo esc_attr($search); ?>"
                               placeholder="Įveskite numerį..." style="min-width: 250px;">
                    </div>
                    <div>
                        <label for="status">Būsena:</label><br>
                        <select name="status" id="status">
                            <option value="all" <?php selected($status_filter, 'all'); ?>>Visos</option>
                            <option value="available" <?php selected($status_filter, 'available'); ?>>Laisvos</option>
                            <option value="used" <?php selected($status_filter, 'used'); ?>>Panaudotos</option>
                        </select>
                    </div>
                    <div>
                        <?php submit_button('Filtruoti', 'secondary', 'submit', false); ?>
                        <?php if (!empty($search) || $status_filter !== 'all'): ?>
                            <a href="?page=loyalty-card-numbers" class="button">Valyti filtrus</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>

        <!-- Cards Table -->
        <?php if ($cards): ?>
            <table class="wp-list-table widefat fixed striped">
                <thead>
                    <tr>
                        <th style="width: 80px;">ID</th>
                        <th>Kortelės numeris</th>
                        <th>Būsena</th>
                        <th>Nario ID</th>
                        <th>Sukurta</th>
                        <th>Panaudota</th>
                        <th style="width: 120px;">Veiksmai</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cards as $card): ?>
                        <tr>
                            <td><?php echo esc_html($card->id); ?></td>
                            <td><strong><?php echo esc_html($card->card_number); ?></strong></td>
                            <td>
                                <?php if ($card->is_used): ?>
                                    <span style="display: inline-block; padding: 3px 10px; background: #dc3545; color: #fff; border-radius: 3px; font-size: 12px;">
                                        Panaudota
                                    </span>
                                <?php else: ?>
                                    <span style="display: inline-block; padding: 3px 10px; background: #28a745; color: #fff; border-radius: 3px; font-size: 12px;">
                                        Laisva
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($card->used_by_member_id): ?>
                                    <a href="?page=loyalty-members&member_id=<?php echo esc_attr($card->used_by_member_id); ?>">
                                        #<?php echo esc_html($card->used_by_member_id); ?>
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo esc_html(date('Y-m-d H:i', strtotime($card->created_at))); ?></td>
                            <td>
                                <?php if ($card->used_at): ?>
                                    <?php echo esc_html(date('Y-m-d H:i', strtotime($card->used_at))); ?>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if ($card->is_used): ?>
                                    <a href="<?php echo wp_nonce_url(admin_url('admin.php?page=loyalty-card-numbers&action=free_card&card_id=' . $card->id), 'free_card_' . $card->id); ?>"
                                       class="button button-small"
                                       onclick="return confirm('Ar tikrai norite atlaisvinti šį kortelės numerį?');"
                                       style="font-size: 12px;">
                                        Atlaisvinti
                                    </a>
                                <?php else: ?>
                                    <span style="color: #999;">-</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <!-- Pagination -->
            <?php if ($total_pages > 1): ?>
                <div class="tablenav bottom">
                    <div class="tablenav-pages">
                        <span class="displaying-num"><?php echo number_format($total_filtered); ?> elementai</span>
                        <?php
                        $base_url = add_query_arg(array(
                            'page' => 'loyalty-card-numbers',
                            's' => $search,
                            'status' => $status_filter
                        ), admin_url('admin.php'));

                        echo paginate_links(array(
                            'base' => add_query_arg('paged', '%#%', $base_url),
                            'format' => '',
                            'prev_text' => '&laquo;',
                            'next_text' => '&raquo;',
                            'total' => $total_pages,
                            'current' => $current_page,
                            'type' => 'plain'
                        ));
                        ?>
                    </div>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div style="background: #fff; padding: 40px; text-align: center; border: 1px solid #ccd0d4;">
                <p style="font-size: 16px; color: #666;">
                    <?php if (!empty($search) || $status_filter !== 'all'): ?>
                        Kortelių nerasta pagal pasirinktus filtrus.
                    <?php else: ?>
                        Kortelių numerių dar nėra. Naudokite formą aukščiau, kad sukurtumėte pirmuosius numerius.
                    <?php endif; ?>
                </p>
            </div>
        <?php endif; ?>
    </div>

    <style>
        .card-body h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .tablenav-pages {
            float: right;
            margin: 10px 0;
        }

        .tablenav-pages .page-numbers {
            padding: 5px 10px;
            margin: 0 2px;
            text-decoration: none;
            border: 1px solid #ddd;
            background: #fff;
        }

        .tablenav-pages .page-numbers.current {
            background: #0073aa;
            color: #fff;
            border-color: #0073aa;
        }

        .tablenav-pages .page-numbers:hover:not(.current) {
            background: #f0f0f0;
        }
    </style>
    <?php
}
```

### 2. Include Admin Page in functions.php

At the end of your `functions.php`, add:

```php
// Include admin card numbers management page
require_once get_template_directory() . '/inc/admin-card-numbers.php';
```

---

## Testing

### 1. Verify Database Table
1. Go to your WordPress database (via phpMyAdmin)
2. Check that table `wp_loyalty_card_numbers` exists
3. Verify it contains 100 cards (200901-201000)
4. All should have `is_used = 0` initially

### 2. Test Admin Interface
1. Navigate to **WordPress Admin → Loyalty Members → Kortelių numeriai**
2. Verify statistics display correctly
3. Test bulk generation (e.g., 201001-201100)
4. Test search functionality
5. Test status filtering (All/Available/Used)
6. Verify pagination works

### 3. Test Frontend Validation
1. Visit your loyalty registration page
2. Enter a valid card number (e.g., 200901)
   - Should show green checkmark: "Lojalumo kortelės numeris galioja ✓"
3. Enter an invalid number (e.g., 999999)
   - Should show red error: "Neteisingas lojalumo kortelės numeris"
4. Try to submit with invalid card
   - Should be blocked with error message
5. Clear the field
   - Validation message should disappear

### 4. Test Form Submission
1. Complete form with a valid card number
2. Submit and verify registration succeeds
3. Go to admin → Kortelių numeriai
4. Verify card is marked as "Panaudota" (Used)
5. Try registering with same card again
   - Should fail with "Ši kortelė jau užregistruota"

### 5. Test Member Deletion
1. Delete a member from Loyalty Members page
2. Go to Kortelių numeriai page
3. Verify card is back to "Laisva" (Available) status
4. Card should be reusable for new registrations

### 6. Test Manual Card Freeing
1. Find a used card in admin
2. Click "Atlaisvinti" button
3. Should show error: "Negalima atlaisvinti kortelės - narys #X vis dar egzistuoja"
4. Only allows freeing if member was deleted

---

## Summary

This system provides:
- ✅ Database-backed card number validation
- ✅ Real-time AJAX validation with visual feedback
- ✅ Multi-language support (LT/EN/RU)
- ✅ Admin interface for managing cards
- ✅ Automatic card status tracking
- ✅ Bulk card generation
- ✅ Search and filtering
- ✅ Safety checks to prevent data inconsistencies

All code is production-ready and includes proper:
- Security (nonces, capability checks)
- Error handling and logging
- Database sanitization
- Multi-language support
- User feedback messages
