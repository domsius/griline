<section class="counter section-space">
    <div class="container">
        <div class="row neutral-row">
            <div class="col-sm-6 col-lg-4">
                <div class="counter-single single-item">
                    <div class="odometer-item">
                        <div class="counter-thumb-wrapper">
                            <div class="counter-thumb">
                                <h2 class="title odometer" data-odometer-final="400">0</h2>
                                <h2 class="title">+</h2>
                            </div>
                            <p class="subtitle"><?php _e('Burgerių parduota', 'griline'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="counter-single single-item">
                    <div class="odometer-item">
                        <div class="counter-thumb-wrapper">
                            <div class="counter-thumb">
                                <h2 class="title odometer" data-odometer-final="5">0</h2>
                                <h2 class="title">K+</h2>
                            </div>
                            <p class="subtitle"><?php _e('Svečių apsilankė', 'griline'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6 col-lg-4">
                <div class="counter-single single-item">
                    <div class="odometer-item">
                        <div class="counter-thumb-wrapper">
                            <div class="counter-thumb">
                                <h2 class="title odometer" data-odometer-final="<?php 
                                    $start_date = new DateTime('2025-03-01');
                                    $now = new DateTime();
                                    $interval = $start_date->diff($now);
                                    echo $interval->days + 1;
                                ?>">0</h2>
                                <h2 class="title"><?php _e('d.', 'griline'); ?></h2>
                            </div>
                            <p class="subtitle"><?php _e('Jau dirbame', 'griline'); ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>