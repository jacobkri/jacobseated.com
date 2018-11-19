<?php

// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 Settings Class 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

// NOTE: This can be used inside __constructs() to share settings between classes


class settings_handler {
    public $json_dir;

    function __construct() {
        $this->json_dir = $_SERVER["DOCUMENT_ROOT"] . 'json/';
    }

}