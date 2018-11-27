<?php

// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 Page Handler Class 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

// NOTE: This can be used inside __constructs() to share settings between classes


class page_handler {
    public $json_dir;
    public $page_list;

    function __construct() {
        $this->json_dir = $_SERVER["DOCUMENT_ROOT"] . 'json/';
        $this->page_list = $this->list_pages();
    }

    private function list_pages() {
        return array_slice(scandir($this->json_dir), 2); // Remove ".." and "." with array_slice()
      }

}