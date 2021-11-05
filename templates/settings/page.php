<?php

if ( ! defined( 'ABSPATH' ) ) exit; ?>

<div class="wrap">
    <h1>
        <?php echo $this->get_page_title(); ?>
    </h1>

    <form method="post" action="options.php">
        <?php
        settings_fields( $this->get_slug() );

        do_settings_sections( $this->get_slug() );

        submit_button();
        ?>
    </form>
</div>