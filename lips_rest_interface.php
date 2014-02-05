<?php

/**
 * This interface represents the code used to dialog with web services.
 * The are two web services used by the plugin.
 * The first web service permits to obtain the list of languages available.
 * The second permits to execute source code and get the result of execution.
 *
 */
interface lips_rest_interface {
    public static function execute($source);
    public static function get_list_languages();
}