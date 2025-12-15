<?php
/*
*
* Template Name: Lojalumas (Loyalty Card)
*
*/

get_header('page');
?>

<section class="loyalty-section py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="loyalty-header text-center mb-5">
                    <h1 class="loyalty-title"><?php the_title(); ?></h1>
                    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                        <div class="loyalty-description">
                            <?php the_content(); ?>
                        </div>
                    <?php endwhile; endif; ?>
                </div>

                <div class="loyalty-form-wrapper">
                    <?php
                    $current_lang = apply_filters('wpml_current_language', NULL);
                    $show_form = true;

                    // Display success message
                    if (isset($_GET['loyalty_success']) && $_GET['loyalty_success'] == '1') {
                        $show_form = false;
                        echo '<div class="loyalty-success-message" style="background-color: #d4edda; color: #155724; padding: 30px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #c3e6cb; text-align: center;">';
                        echo '<h2 style="color: #155724; margin-top: 0;">✓ ';
                        switch($current_lang) {
                            case 'en':
                                echo 'Registration Successful!</h2>';
                                echo '<p style="font-size: 1.1rem;">Thank you for joining our loyalty program!</p>';
                                echo '<p>Welcome email has been sent to your email address.</p>';
                                break;
                            case 'ru':
                                echo 'Регистрация успешна!</h2>';
                                echo '<p style="font-size: 1.1rem;">Спасибо за присоединение к нашей программе лояльности!</p>';
                                echo '<p>Приветственное письмо отправлено на вашу электронную почту.</p>';
                                break;
                            default:
                                echo 'Registracija sėkminga!</h2>';
                                echo '<p style="font-size: 1.1rem;">Ačiū, kad prisijungėte prie mūsų lojalumo programos!</p>';
                                echo '<p>Pasveikinimo laiškas išsiųstas į Jūsų el. paštą.</p>';
                        }
                        echo '</div>';
                    }

                    // Display error message
                    if (isset($_GET['loyalty_error'])) {
                        echo '<div class="loyalty-error-message" style="background-color: #f8d7da; color: #721c24; padding: 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #f5c6cb;">';

                        $error_type = $_GET['loyalty_error'];

                        if ($error_type === 'invalid_card') {
                            echo get_card_validation_message('invalid', $current_lang);
                        } else {
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

                    // Only show form if not successful
                    if ($show_form) :

                    // Form labels and placeholders by language
                    $labels = array(
                        'lt' => array(
                            'first_name' => 'Vardas *',
                            'last_name' => 'Pavardė *',
                            'birth_date' => 'Gimimo data *',
                            'city' => 'Miestas *',
                            'email' => 'El. paštas *',
                            'phone' => 'Tel. numeris *',
                            'card_number' => 'Lojalumo kortelės nr. *',
                            'consent' => 'Sutinku gauti informaciją apie specialius pasiūlymus, šventes ir kt. telefonu bei el. paštu',
                            'submit' => 'Registruotis',
                            'placeholder_first_name' => 'Įveskite savo vardą',
                            'placeholder_last_name' => 'Įveskite savo pavardę',
                            'placeholder_birth_date' => 'YYYY-MM-DD',
                            'placeholder_city' => 'Įveskite savo miestą',
                            'placeholder_email' => 'jusu.pastas@example.com',
                            'placeholder_phone' => '+370 600 00000',
                            'placeholder_card_number' => '000500'
                        ),
                        'en' => array(
                            'first_name' => 'First Name *',
                            'last_name' => 'Last Name *',
                            'birth_date' => 'Date of Birth *',
                            'city' => 'City *',
                            'email' => 'Email *',
                            'phone' => 'Phone Number *',
                            'card_number' => 'Loyalty Card Number *',
                            'consent' => 'I agree to receive information about special offers, events, etc. by phone and email',
                            'submit' => 'Register',
                            'placeholder_first_name' => 'Enter your first name',
                            'placeholder_last_name' => 'Enter your last name',
                            'placeholder_birth_date' => 'YYYY-MM-DD',
                            'placeholder_city' => 'Enter your city',
                            'placeholder_email' => 'your.email@example.com',
                            'placeholder_phone' => '+370 600 00000',
                            'placeholder_card_number' => '000500'
                        ),
                        'ru' => array(
                            'first_name' => 'Имя *',
                            'last_name' => 'Фамилия *',
                            'birth_date' => 'Дата рождения *',
                            'city' => 'Город *',
                            'email' => 'Эл. почта *',
                            'phone' => 'Номер телефона *',
                            'card_number' => 'Номер карты лояльности *',
                            'consent' => 'Я согласен получать информацию о специальных предложениях, мероприятиях и т.д. по телефону и электронной почте',
                            'submit' => 'Зарегистрироваться',
                            'placeholder_first_name' => 'Введите ваше имя',
                            'placeholder_last_name' => 'Введите вашу фамилию',
                            'placeholder_birth_date' => 'ГГГГ-ММ-ДД',
                            'placeholder_city' => 'Введите ваш город',
                            'placeholder_email' => 'your.email@example.com',
                            'placeholder_phone' => '+370 600 00000',
                            'placeholder_card_number' => '000500'
                        )
                    );

                    $l = isset($labels[$current_lang]) ? $labels[$current_lang] : $labels['lt'];
                    ?>

                    <form id="loyalty-form" method="POST" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                        <?php wp_nonce_field('loyalty_form_submit', 'loyalty_nonce'); ?>
                        <input type="hidden" name="action" value="submit_loyalty_form">
                        <input type="hidden" name="form_language" value="<?php echo $current_lang; ?>">

                        <div class="form-group">
                            <label for="vardas"><?php echo esc_html($l['first_name']); ?></label>
                            <input type="text" id="vardas" name="vardas" placeholder="<?php echo esc_attr($l['placeholder_first_name']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="pavarde"><?php echo esc_html($l['last_name']); ?></label>
                            <input type="text" id="pavarde" name="pavarde" placeholder="<?php echo esc_attr($l['placeholder_last_name']); ?>">
                        </div>

                        <div class="form-group date-input-wrapper">
                            <label for="gimimo-data"><?php echo esc_html($l['birth_date']); ?></label>
                            <div class="input-with-icon">
                                <input type="text" id="gimimo-data" name="gimimo-data" placeholder="<?php echo esc_attr($l['placeholder_birth_date']); ?>" readonly>
                                <span class="calendar-icon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
                                        <line x1="16" y1="2" x2="16" y2="6"></line>
                                        <line x1="8" y1="2" x2="8" y2="6"></line>
                                        <line x1="3" y1="10" x2="21" y2="10"></line>
                                    </svg>
                                </span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="miestas"><?php echo esc_html($l['city']); ?></label>
                            <input type="text" id="miestas" name="miestas" placeholder="<?php echo esc_attr($l['placeholder_city']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="el-pastas"><?php echo esc_html($l['email']); ?></label>
                            <input type="email" id="el-pastas" name="el-pastas" placeholder="<?php echo esc_attr($l['placeholder_email']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="tel-numeris"><?php echo esc_html($l['phone']); ?></label>
                            <input type="tel" id="tel-numeris" name="tel-numeris" placeholder="<?php echo esc_attr($l['placeholder_phone']); ?>">
                        </div>

                        <div class="form-group">
                            <label for="korteles-nr"><?php echo esc_html($l['card_number']); ?></label>
                            <input type="text" id="korteles-nr" name="korteles-nr" placeholder="<?php echo esc_attr($l['placeholder_card_number']); ?>">
                            <div id="card-validation-message" class="card-validation-message"></div>
                        </div>

                        <div class="form-group checkbox-group">
                            <label>
                                <input type="checkbox" name="sutinku" value="1">
                                <span><?php echo esc_html($l['consent']); ?></span>
                            </label>
                        </div>

                        <button type="submit" class="loyalty-submit-btn" id="loyalty-submit-btn" disabled><?php echo esc_html($l['submit']); ?></button>
                    </form>

                    <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        // Initialize Bootstrap datepicker
                        if (typeof jQuery !== 'undefined' && jQuery.fn.datepicker) {
                            var currentLang = '<?php echo $current_lang; ?>';
                            var datepickerLang = currentLang === 'en' ? 'en' : currentLang;

                            jQuery('#gimimo-data').datepicker({
                                format: 'yyyy-mm-dd',
                                autoclose: true,
                                todayHighlight: false,
                                startView: 'decade',
                                endDate: '0d',
                                orientation: 'bottom auto',
                                language: datepickerLang,
                                startDate: '-120y',
                                defaultViewDate: { year: 1990, month: 0, day: 1 },
                                yearRange: 120,
                                maxViewMode: 2,
                                showOnFocus: true,
                                forceParse: false,
                                clearBtn: true,
                                toggleActive: true
                            }).on('show', function(e) {
                                // Add calendar icon visual feedback
                                jQuery(this).closest('.form-group').addClass('datepicker-active');
                            }).on('hide', function(e) {
                                jQuery(this).closest('.form-group').removeClass('datepicker-active');
                            });
                        }

                        const form = document.getElementById('loyalty-form');
                        const consentCheckbox = document.querySelector('input[name="sutinku"]');
                        const submitButton = document.getElementById('loyalty-submit-btn');
                        const cardNumberField = document.getElementById('korteles-nr');
                        const cardValidationMessage = document.getElementById('card-validation-message');

                        // Card validation state
                        let cardIsValid = false;
                        let cardValidationTimeout = null;

                        // Define required field IDs
                        const requiredFieldIds = ['vardas', 'pavarde', 'gimimo-data', 'miestas', 'el-pastas', 'tel-numeris', 'korteles-nr'];
                        const requiredFields = requiredFieldIds.map(id => document.getElementById(id)).filter(field => field !== null);

                        if (consentCheckbox && submitButton) {
                            // Enable/disable submit button based on checkbox
                            consentCheckbox.addEventListener('change', function() {
                                submitButton.disabled = !this.checked;
                            });
                        }

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
                                        error: function(xhr, status, error) {
                                            cardIsValid = false;
                                            cardValidationMessage.textContent = '✗ <?php
                                                switch($current_lang) {
                                                    case 'en': echo 'Validation error. Please try again.'; break;
                                                    case 'ru': echo 'Ошибка проверки. Попробуйте еще раз.'; break;
                                                    default: echo 'Tikrinimo klaida. Bandykite dar kartą.';
                                                }
                                            ?>';
                                            cardValidationMessage.className = 'card-validation-message invalid';
                                        }
                                    });
                                }, 500); // 500ms debounce
                            });
                        }

                        // Validate on input change - remove red border when field is filled
                        requiredFields.forEach(function(field) {
                            field.addEventListener('input', function() {
                                if (this.value.trim() !== '') {
                                    this.style.borderColor = '';
                                    this.style.borderWidth = '';
                                }
                            });
                        });

                        // Validate on form submit
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            let isValid = true;

                            // Check all required fields
                            requiredFields.forEach(function(field) {
                                if (field.value.trim() === '') {
                                    field.style.borderColor = '#dc3545';
                                    field.style.borderWidth = '2px';
                                    isValid = false;
                                } else {
                                    field.style.borderColor = '';
                                    field.style.borderWidth = '';
                                }
                            });

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
                    </script>

                    <?php endif; // End show_form check ?>
                </div>
            </div>
        </div>
    </div>
</section>

<?php get_footer(); ?>
