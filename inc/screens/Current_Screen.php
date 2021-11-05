<?php

namespace JT\helpers\inc\screens;

/**
 * Represents current screen (page).
 */
class Current_Screen
{
    /**
     * Checks if current screen is Beaver Builder editor page.
     *
     * @return boolean
     */
    public static function is_beaver_builder_editor() : bool
    {
        // Todo: find more stable way (maybe did_action() or so)
        return isset( $_GET['fl_builder'] );
    }
}