<?php
/**
 * Template part for displaying page banner with title and breadcrumb
 *
 * @package Griline
 */

$current_page_title = get_the_title();
?>
<!-- ==== banner section start ==== -->
<section class="banner">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-sm-12">
                <div class="banner-content">
                    <h3><?php echo esc_html($current_page_title); ?></h3>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/">Titulinis</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo esc_html($current_page_title); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- ==== #banner section end ==== --> 