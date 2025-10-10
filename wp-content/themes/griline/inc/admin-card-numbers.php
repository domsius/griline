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
        'loyalty-members',
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
        // Format card number with leading zeros (6 digits)
        $card_number = str_pad($i, 6, '0', STR_PAD_LEFT);

        // Check if card number already exists
        $exists = $wpdb->get_var($wpdb->prepare(
            "SELECT COUNT(*) FROM $table_name WHERE card_number = %s",
            $card_number
        ));

        if ($exists) {
            $skipped++;
            continue;
        }

        $values[] = $wpdb->prepare("(%s, 0, NULL, %s, NULL)", $card_number, current_time('mysql'));
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
    $suggested_start = $highest_card ? $highest_card + 1 : 600;
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
                <p style="background: #e7f3ff; padding: 12px; border-left: 4px solid #0073aa; margin-bottom: 15px;">
                    <strong>Pastaba:</strong> Kortelių numeriai bus automatiškai suformatuoti kaip 6 skaitmenų numeriai su nuliukais pradžioje
                    (pvz., 600 → 000600, 1234 → 001234).
                </p>
                <form method="post" action="">
                    <?php wp_nonce_field('generate_cards', 'generate_cards_nonce'); ?>
                    <table class="form-table">
                        <tr>
                            <th scope="row"><label for="start_number">Pradžios numeris</label></th>
                            <td>
                                <input type="number" name="start_number" id="start_number"
                                       value="<?php echo esc_attr($suggested_start); ?>"
                                       class="regular-text" required min="1">
                                <p class="description">Siūlomas: <?php echo str_pad($suggested_start, 6, '0', STR_PAD_LEFT); ?> (įveskite: <?php echo $suggested_start; ?>)</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="end_number">Pabaigos numeris</label></th>
                            <td>
                                <input type="number" name="end_number" id="end_number"
                                       value="<?php echo esc_attr($suggested_end); ?>"
                                       class="regular-text" required min="1">
                                <p class="description">Siūlomas: <?php echo str_pad($suggested_end, 6, '0', STR_PAD_LEFT); ?> - Sugeneruos <?php echo ($suggested_end - $suggested_start + 1); ?> korteles</p>
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
