<?php

// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 Settings Handler Class 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

// This class contain commonly used paths and other information.
// Other classes that are part of the project should extend this class.

class settings_handler {
    public $json_dir;
    public $page_list;
    public $protocol; // I.e.: HTTP/1.1
    public $title_separator = '☕'; // Used in HTML <title> to seperate parts. I.e.: About us ☕ example.com
    public $site_name = 'JacobSeated.com'; // Used in HTML <title> for branding

    // Functions below

    public function __construct() {
      $this->protocol    = $this->determine_protocol();
      $this->json_dir    = $_SERVER["DOCUMENT_ROOT"] . 'json/';
      $this->modules_dir = $_SERVER["DOCUMENT_ROOT"] . 'lib/modules/';
      $this->page_list   = $this->list_pages();
    }

    private function list_pages() {
        return array_slice(scandir($this->json_dir), 2); // Remove ".." and "." with array_slice()
    }

    private function determine_protocol() {
      // Determine the protocol used by the client to communicate with the server
      $protocol = $_SERVER["SERVER_PROTOCOL"];
      if (($this->protocol != 'HTTP/1.1') && ($this->protocol != 'HTTP/1.0')) {
        $protocol = 'HTTP/1.0'; // Fall back to 1.0 if unknown
      }
      return $protocol;
    }
}