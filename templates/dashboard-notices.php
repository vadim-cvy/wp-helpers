<?php

if ( ! defined( 'ABSPATH' ) ) exit;

foreach ( $this->notices as $notices_type => $notices )
{
    foreach ( $notices as $notice_text )
    { ?>
        <div class="notice notice-<?php echo $notices_type; ?>">
            <p>
                <?php echo $notice_text; ?>
            </p>
        </div>
    <?php
    }
}