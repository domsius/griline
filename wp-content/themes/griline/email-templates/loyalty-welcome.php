<?php
/**
 * Loyalty Welcome Email Template
 * Variables available: $member_data, $lang
 */

// Get content from database or use defaults
$template = get_email_template_content('welcome', $lang);
$content_data = $template ? json_decode($template->content, true) : array();

// Replace {first_name} placeholder with actual first name
$text = array();
foreach ($content_data as $key => $value) {
    $text[$key] = str_replace('{first_name}', esc_html($member_data['first_name']), $value);
}
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $text['greeting']; ?></title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, sans-serif; background-color: #f4ede5;">
    <table width="100%" cellpadding="0" cellspacing="0" style="background-color: #f4ede5; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="0" cellspacing="0" style="background-color: #ffffff; border-radius: 10px; box-shadow: 0 2px 15px rgba(0,0,0,0.1);">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #f25b0a; padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
                            <img src="<?php echo home_url('/wp-content/uploads/2025/04/griline-logo-white.svg'); ?>" alt="Griline" style="max-width: 180px; height: auto; display: block; margin: 0 auto;">
                        </td>
                    </tr>

                    <!-- Content -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            <h2 style="color: #f25b0a; font-size: 24px; margin: 0 0 20px 0;">
                                <?php echo $text['greeting']; ?>
                            </h2>

                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 0 0 20px 0;">
                                <?php echo $text['welcome']; ?>
                            </p>

                            <!-- Card Number Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <td style="background-color: #fef7f3; padding: 20px; border-left: 4px solid #f25b0a; border-radius: 5px;">
                                        <p style="margin: 0 0 10px 0; color: #666666; font-size: 14px;">
                                            <?php echo $text['card_title']; ?>
                                        </p>
                                        <p style="margin: 0; color: #f25b0a; font-size: 24px; font-weight: 700;">
                                            <?php echo esc_html($member_data['card_number']); ?>
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Benefits -->
                            <p style="color: #333333; font-size: 16px; font-weight: 600; margin: 30px 0 15px 0;">
                                <?php echo $text['benefits_title']; ?>
                            </p>

                            <table width="100%" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="width: 30px; vertical-align: top;">
                                                    <span style="color: #f25b0a; font-size: 20px;">🎁</span>
                                                </td>
                                                <td style="color: #333333; font-size: 15px; line-height: 1.6;">
                                                    <?php echo $text['benefit_1']; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="width: 30px; vertical-align: top;">
                                                    <span style="color: #f25b0a; font-size: 20px;">🔔</span>
                                                </td>
                                                <td style="color: #333333; font-size: 15px; line-height: 1.6;">
                                                    <?php echo $text['benefit_2']; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 0;">
                                        <table cellpadding="0" cellspacing="0">
                                            <tr>
                                                <td style="width: 30px; vertical-align: top;">
                                                    <span style="color: #f25b0a; font-size: 20px;">⭐</span>
                                                </td>
                                                <td style="color: #333333; font-size: 15px; line-height: 1.6;">
                                                    <?php echo $text['benefit_3']; ?>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>

                            <p style="color: #333333; font-size: 16px; line-height: 1.6; margin: 30px 0 20px 0;">
                                <?php echo $text['thanks']; ?>
                            </p>

                            <?php if (!empty($text['image_1']) || !empty($text['image_2']) || !empty($text['image_3'])): ?>
                            <!-- Image Gallery -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin: 30px 0;">
                                <tr>
                                    <?php if (!empty($text['image_1'])): ?>
                                    <td style="width: 33.33%; padding: 5px;" align="center">
                                        <img src="<?php echo esc_url($text['image_1']); ?>" alt="Image 1" style="width: 100%; max-width: 180px; height: auto; border-radius: 8px; display: block;">
                                    </td>
                                    <?php endif; ?>

                                    <?php if (!empty($text['image_2'])): ?>
                                    <td style="width: 33.33%; padding: 5px;" align="center">
                                        <img src="<?php echo esc_url($text['image_2']); ?>" alt="Image 2" style="width: 100%; max-width: 180px; height: auto; border-radius: 8px; display: block;">
                                    </td>
                                    <?php endif; ?>

                                    <?php if (!empty($text['image_3'])): ?>
                                    <td style="width: 33.33%; padding: 5px;" align="center">
                                        <img src="<?php echo esc_url($text['image_3']); ?>" alt="Image 3" style="width: 100%; max-width: 180px; height: auto; border-radius: 8px; display: block;">
                                    </td>
                                    <?php endif; ?>
                                </tr>
                            </table>
                            <?php endif; ?>

                            <p style="color: #666666; font-size: 15px; margin: 0;">
                                <?php echo $text['regards']; ?><br>
                                <strong style="color: #f25b0a;"><?php echo $text['team']; ?></strong>
                            </p>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #4d4d4d; padding: 30px; text-align: center; border-radius: 0 0 10px 10px;">
                            <!-- Contact Information -->
                            <table width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 20px;">
                                <tr>
                                    <td style="text-align: center;">
                                        <h3 style="color: #ffffff; font-size: 16px; margin: 0 0 15px 0; text-transform: uppercase;">
                                            <?php echo $lang == 'lt' ? 'KONTAKTAI:' : ($lang == 'en' ? 'CONTACTS:' : 'КОНТАКТЫ:'); ?>
                                        </h3>

                                        <p style="margin: 0 0 8px 0; color: #ffffff; font-size: 13px;">
                                            <?php echo $lang == 'lt' ? 'Telefono numeris:' : ($lang == 'en' ? 'Phone Number:' : 'Номер телефона:'); ?>
                                            <a href="tel:+37061114446" style="color: #f25b0a; text-decoration: none;">+370 611 14446</a>
                                        </p>

                                        <p style="margin: 0 0 8px 0; color: #ffffff; font-size: 13px;">
                                            <?php
                                            if ($lang == 'lt') {
                                                echo 'Adresas: <a href="https://www.google.com/maps/search/?api=1&query=Arimų+gatvė+3,+Ginduliai,+Lietuva" target="_blank" style="color: #f25b0a; text-decoration: none;">Arimų gatvė 3, Ginduliai, Lietuva</a>';
                                            } elseif ($lang == 'en') {
                                                echo 'Address: <a href="https://www.google.com/maps/search/?api=1&query=Arimų+gatvė+3,+Ginduliai,+Lietuva" target="_blank" style="color: #f25b0a; text-decoration: none;">Arimų st. 3, Ginduliai, Lithuania</a>';
                                            } else {
                                                echo 'Адрес: <a href="https://www.google.com/maps/search/?api=1&query=Arimų+gatvė+3,+Ginduliai,+Lietuva" target="_blank" style="color: #f25b0a; text-decoration: none;">Аримų ул. 3, Гиндуляй, Литва</a>';
                                            }
                                            ?>
                                        </p>

                                        <p style="margin: 0 0 15px 0;">
                                            <a href="mailto:info@griline.lt" style="color: #f25b0a; text-decoration: none; font-size: 13px;">
                                                info@griline.lt
                                            </a>
                                        </p>

                                        <!-- Social Media Icons -->
                                        <table cellpadding="0" cellspacing="0" style="margin: 15px auto 0;">
                                            <tr>
                                                <td style="padding: 0 8px;">
                                                    <a href="https://www.facebook.com/grilinerestobaras/" style="color: #ffffff; text-decoration: none; font-size: 20px;">
                                                        <img src="https://cdn-icons-png.flaticon.com/512/5968/5968764.png" alt="Facebook" width="24" height="24" style="display: block;">
                                                    </a>
                                                </td>
                                                <td style="padding: 0 8px;">
                                                    <a href="https://www.instagram.com/grilinerestobaras/" style="color: #ffffff; text-decoration: none; font-size: 20px;">
                                                        <img src="https://cdn-icons-png.flaticon.com/512/174/174855.png" alt="Instagram" width="24" height="24" style="display: block;">
                                                    </a>
                                                </td>
                                            </tr>
                                        </table>

                                        <p style="margin: 15px 0 0 0; color: #999999; font-size: 11px;">
                                            UAB "Grilinė"
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Copyright -->
                            <p style="margin: 15px 0 0 0; padding-top: 15px; border-top: 1px solid #666666; color: #999999; font-size: 11px;">
                                © <?php echo date('Y'); ?> Grilinė. <?php echo $lang == 'lt' ? 'Visos teisės saugomos.' : ($lang == 'en' ? 'All rights reserved.' : 'Все права защищены.'); ?>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
