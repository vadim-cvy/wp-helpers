<?php

if ( ! defined( 'ABSPATH' ) ) exit;

$prefix = \JT\helpers\Helpers::get_instance()->get_slug() . '_'; ?>

<input
    <?php
    foreach ( $this->get_input_attrs() as $key => $value )
    {
        echo $key . '="' . $value . '" ';
    } ?>
>

<?php
if ( $this->has_errors() )
{ ?>
    <p class="<?php echo $prefix; ?>color_error">
        <?php echo $this->get_error_hint(); ?>
    </p>
<?php
}