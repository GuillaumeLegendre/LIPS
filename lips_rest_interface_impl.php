<?php
require_once(dirname(__FILE__) . '/lips_rest_interface.php');

class lips_rest_interface_impl implements lips_rest_interface {

    public static function execute($source) {


    }

    public static function get_list_languages() {
        $languages = array();
        $json = file_get_contents("http://localhost:4567/available_languages");
        $data = json_decode($json);
        foreach ($data->languages as $language) {
            $languages[$language] = $language;
        }
        return $languages;
    }
}