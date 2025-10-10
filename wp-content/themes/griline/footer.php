<!-- ==== footer section start ==== -->
<footer class="section-space bg-img" data-background="<?php echo esc_url(get_template_directory_uri() . '/assets/images/gallery/footer-bg.png'); ?>">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="footer-content">
                    <div class="social">
                        <?php
                        $social_links = array(
                            'facebook' => get_theme_mod('griline_facebook_url', '#'),
                            'instagram' => get_theme_mod('griline_instagram_url', '#')
                        );
                        ?>
                        <a href="<?php echo esc_url($social_links['facebook']); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo esc_url($social_links['instagram']); ?>" target="_blank" rel="noopener noreferrer">
                            <i class="fa-brands fa-instagram"></i>
                        </a>
                    </div>
                    <p class="copy">
                        <?php
                        printf(
                            esc_html__('Visos teisės saugomos, © %1$s', 'griline'),
                            date('Y')
                        );
                        ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- ==== #footer section end ==== -->

<!-- ==== scroll bottom to top ==== -->
<a href="javascript:void(0)" class="scrollToTop">
    <i class="fa-solid fa-angle-up"></i>
</a>

<?php wp_footer(); ?>

</body>
</html>