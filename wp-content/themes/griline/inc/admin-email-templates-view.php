<?php
/**
 * Admin Email Templates View
 * Variables available: $current_template, $current_lang, $template, $content
 */

if (!defined('ABSPATH')) {
    exit;
}
?>

<div class="wrap loyalty-email-templates-page">
    <h1>Email Templates</h1>

    <!-- Template Type Tabs -->
    <h2 class="nav-tab-wrapper">
        <a href="?page=loyalty-email-templates&template=birthday&lang=<?php echo esc_attr($current_lang); ?>"
           class="nav-tab <?php echo $current_template === 'birthday' ? 'nav-tab-active' : ''; ?>">
            Birthday Email
        </a>
        <a href="?page=loyalty-email-templates&template=welcome&lang=<?php echo esc_attr($current_lang); ?>"
           class="nav-tab <?php echo $current_template === 'welcome' ? 'nav-tab-active' : ''; ?>">
            Welcome Email
        </a>
    </h2>

    <!-- Language Tabs -->
    <div class="language-tabs" style="margin: 20px 0;">
        <a href="?page=loyalty-email-templates&template=<?php echo esc_attr($current_template); ?>&lang=lt"
           class="button <?php echo $current_lang === 'lt' ? 'button-primary' : ''; ?>">
            Lithuanian
        </a>
        <a href="?page=loyalty-email-templates&template=<?php echo esc_attr($current_template); ?>&lang=en"
           class="button <?php echo $current_lang === 'en' ? 'button-primary' : ''; ?>">
            English
        </a>
        <a href="?page=loyalty-email-templates&template=<?php echo esc_attr($current_template); ?>&lang=ru"
           class="button <?php echo $current_lang === 'ru' ? 'button-primary' : ''; ?>">
            Russian
        </a>
    </div>

    <div class="template-editor-container">
        <div class="editor-panel">
            <form method="post" action="">
                <?php wp_nonce_field('loyalty_email_template_save'); ?>
                <input type="hidden" name="template_type" value="<?php echo esc_attr($current_template); ?>">
                <input type="hidden" name="language" value="<?php echo esc_attr($current_lang); ?>">

                <h2>Edit Template</h2>

                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="subject">Email Subject</label></th>
                        <td>
                            <input type="text" id="subject" name="subject" class="large-text"
                                   value="<?php echo esc_attr($template ? $template->subject : ''); ?>" required>
                            <p class="description">Use {first_name} as placeholder for member's first name</p>
                        </td>
                    </tr>

                    <?php if ($current_template === 'birthday'): ?>
                        <tr>
                            <th scope="row"><label for="title">Title</label></th>
                            <td><input type="text" id="title" name="title" class="large-text"
                                       value="<?php echo esc_attr($content['title'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="greeting">Greeting</label></th>
                            <td>
                                <input type="text" id="greeting" name="greeting" class="large-text"
                                       value="<?php echo esc_attr($content['greeting'] ?? ''); ?>" required>
                                <p class="description">Use {first_name} as placeholder</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="message">Main Message</label></th>
                            <td><textarea id="message" name="message" class="large-text" rows="3" required><?php echo esc_textarea($content['message'] ?? ''); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="discount_intro">Discount Introduction</label></th>
                            <td><input type="text" id="discount_intro" name="discount_intro" class="large-text"
                                       value="<?php echo esc_attr($content['discount_intro'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="discount">Discount Text</label></th>
                            <td><input type="text" id="discount" name="discount" class="large-text"
                                       value="<?php echo esc_attr($content['discount'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="discount_text">Discount Description</label></th>
                            <td><input type="text" id="discount_text" name="discount_text" class="large-text"
                                       value="<?php echo esc_attr($content['discount_text'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="validity">Validity Information</label></th>
                            <td><textarea id="validity" name="validity" class="large-text" rows="2" required><?php echo esc_textarea($content['validity'] ?? ''); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="wishes">Birthday Wishes</label></th>
                            <td><input type="text" id="wishes" name="wishes" class="large-text"
                                       value="<?php echo esc_attr($content['wishes'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="2"><h3 style="margin: 20px 0 10px 0;">Image Gallery (Optional)</h3></th>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_1">Image 1</label></th>
                            <td>
                                <div class="image-upload-field">
                                    <input type="hidden" id="image_1" name="image_1" class="image-url-input"
                                           value="<?php echo esc_attr($content['image_1'] ?? ''); ?>">
                                    <div class="image-preview-wrapper">
                                        <?php if (!empty($content['image_1'])): ?>
                                        <img src="<?php echo esc_url($content['image_1']); ?>" class="image-preview" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="button upload-image-button" data-target="image_1">
                                        <?php echo !empty($content['image_1']) ? 'Change Image' : 'Upload Image'; ?>
                                    </button>
                                    <?php if (!empty($content['image_1'])): ?>
                                    <button type="button" class="button remove-image-button" data-target="image_1">Remove Image</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_2">Image 2</label></th>
                            <td>
                                <div class="image-upload-field">
                                    <input type="hidden" id="image_2" name="image_2" class="image-url-input"
                                           value="<?php echo esc_attr($content['image_2'] ?? ''); ?>">
                                    <div class="image-preview-wrapper">
                                        <?php if (!empty($content['image_2'])): ?>
                                        <img src="<?php echo esc_url($content['image_2']); ?>" class="image-preview" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="button upload-image-button" data-target="image_2">
                                        <?php echo !empty($content['image_2']) ? 'Change Image' : 'Upload Image'; ?>
                                    </button>
                                    <?php if (!empty($content['image_2'])): ?>
                                    <button type="button" class="button remove-image-button" data-target="image_2">Remove Image</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_3">Image 3</label></th>
                            <td>
                                <div class="image-upload-field">
                                    <input type="hidden" id="image_3" name="image_3" class="image-url-input"
                                           value="<?php echo esc_attr($content['image_3'] ?? ''); ?>">
                                    <div class="image-preview-wrapper">
                                        <?php if (!empty($content['image_3'])): ?>
                                        <img src="<?php echo esc_url($content['image_3']); ?>" class="image-preview" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="button upload-image-button" data-target="image_3">
                                        <?php echo !empty($content['image_3']) ? 'Change Image' : 'Upload Image'; ?>
                                    </button>
                                    <?php if (!empty($content['image_3'])): ?>
                                    <button type="button" class="button remove-image-button" data-target="image_3">Remove Image</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php else: // welcome template ?>
                        <tr>
                            <th scope="row"><label for="greeting">Greeting</label></th>
                            <td>
                                <input type="text" id="greeting" name="greeting" class="large-text"
                                       value="<?php echo esc_attr($content['greeting'] ?? ''); ?>" required>
                                <p class="description">Use {first_name} as placeholder</p>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="welcome">Welcome Message</label></th>
                            <td><textarea id="welcome" name="welcome" class="large-text" rows="2" required><?php echo esc_textarea($content['welcome'] ?? ''); ?></textarea></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="card_title">Card Number Title</label></th>
                            <td><input type="text" id="card_title" name="card_title" class="large-text"
                                       value="<?php echo esc_attr($content['card_title'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="benefits_title">Benefits Title</label></th>
                            <td><input type="text" id="benefits_title" name="benefits_title" class="large-text"
                                       value="<?php echo esc_attr($content['benefits_title'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="benefit_1">Benefit 1</label></th>
                            <td><input type="text" id="benefit_1" name="benefit_1" class="large-text"
                                       value="<?php echo esc_attr($content['benefit_1'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="benefit_2">Benefit 2</label></th>
                            <td><input type="text" id="benefit_2" name="benefit_2" class="large-text"
                                       value="<?php echo esc_attr($content['benefit_2'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="benefit_3">Benefit 3</label></th>
                            <td><input type="text" id="benefit_3" name="benefit_3" class="large-text"
                                       value="<?php echo esc_attr($content['benefit_3'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="thanks">Thank You Message</label></th>
                            <td><input type="text" id="thanks" name="thanks" class="large-text"
                                       value="<?php echo esc_attr($content['thanks'] ?? ''); ?>" required></td>
                        </tr>
                        <tr>
                            <th scope="row" colspan="2"><h3 style="margin: 20px 0 10px 0;">Image Gallery (Optional)</h3></th>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_1">Image 1</label></th>
                            <td>
                                <div class="image-upload-field">
                                    <input type="hidden" id="image_1" name="image_1" class="image-url-input"
                                           value="<?php echo esc_attr($content['image_1'] ?? ''); ?>">
                                    <div class="image-preview-wrapper">
                                        <?php if (!empty($content['image_1'])): ?>
                                        <img src="<?php echo esc_url($content['image_1']); ?>" class="image-preview" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="button upload-image-button" data-target="image_1">
                                        <?php echo !empty($content['image_1']) ? 'Change Image' : 'Upload Image'; ?>
                                    </button>
                                    <?php if (!empty($content['image_1'])): ?>
                                    <button type="button" class="button remove-image-button" data-target="image_1">Remove Image</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_2">Image 2</label></th>
                            <td>
                                <div class="image-upload-field">
                                    <input type="hidden" id="image_2" name="image_2" class="image-url-input"
                                           value="<?php echo esc_attr($content['image_2'] ?? ''); ?>">
                                    <div class="image-preview-wrapper">
                                        <?php if (!empty($content['image_2'])): ?>
                                        <img src="<?php echo esc_url($content['image_2']); ?>" class="image-preview" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="button upload-image-button" data-target="image_2">
                                        <?php echo !empty($content['image_2']) ? 'Change Image' : 'Upload Image'; ?>
                                    </button>
                                    <?php if (!empty($content['image_2'])): ?>
                                    <button type="button" class="button remove-image-button" data-target="image_2">Remove Image</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><label for="image_3">Image 3</label></th>
                            <td>
                                <div class="image-upload-field">
                                    <input type="hidden" id="image_3" name="image_3" class="image-url-input"
                                           value="<?php echo esc_attr($content['image_3'] ?? ''); ?>">
                                    <div class="image-preview-wrapper">
                                        <?php if (!empty($content['image_3'])): ?>
                                        <img src="<?php echo esc_url($content['image_3']); ?>" class="image-preview" style="max-width: 150px; height: auto; display: block; margin-bottom: 10px;">
                                        <?php endif; ?>
                                    </div>
                                    <button type="button" class="button upload-image-button" data-target="image_3">
                                        <?php echo !empty($content['image_3']) ? 'Change Image' : 'Upload Image'; ?>
                                    </button>
                                    <?php if (!empty($content['image_3'])): ?>
                                    <button type="button" class="button remove-image-button" data-target="image_3">Remove Image</button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endif; ?>

                    <tr>
                        <th scope="row"><label for="regards">Regards</label></th>
                        <td><input type="text" id="regards" name="regards" class="large-text"
                                   value="<?php echo esc_attr($content['regards'] ?? ''); ?>" required></td>
                    </tr>
                    <tr>
                        <th scope="row"><label for="team">Team Name</label></th>
                        <td><input type="text" id="team" name="team" class="large-text"
                                   value="<?php echo esc_attr($content['team'] ?? ''); ?>" required></td>
                    </tr>
                </table>

                <p class="submit">
                    <button type="submit" name="save_template" class="button button-primary button-large">Save Template</button>
                    <button type="button" id="preview-email" class="button button-large" style="margin-left: 10px;">Preview Email</button>
                </p>
            </form>

            <form method="post" action="" style="margin-top: 20px;">
                <?php wp_nonce_field('loyalty_email_template_reset'); ?>
                <input type="hidden" name="template_type" value="<?php echo esc_attr($current_template); ?>">
                <input type="hidden" name="language" value="<?php echo esc_attr($current_lang); ?>">
                <button type="submit" name="reset_template" class="button button-secondary"
                        onclick="return confirm('Are you sure you want to reset this template to defaults? This cannot be undone.');">
                    Reset to Defaults
                </button>
            </form>
        </div>

        <div class="preview-panel">
            <h2>Preview</h2>
            <div id="email-preview" class="email-preview-container">
                <p class="description">Click "Preview Email" to see how the email will look.</p>
            </div>

            <div class="test-email-section" style="margin-top: 30px; padding: 20px; background: #f9f9f9; border: 1px solid #ddd; border-radius: 4px;">
                <h3>Send Test Email</h3>
                <p>
                    <input type="email" id="test-email-address" class="regular-text" placeholder="test@example.com">
                    <button type="button" id="send-test-email" class="button">Send Test Email</button>
                </p>
                <div id="test-email-message"></div>
            </div>
        </div>
    </div>
</div>
