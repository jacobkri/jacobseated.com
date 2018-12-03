<?php
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 CLASSES 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
require_once $_SERVER["DOCUMENT_ROOT"] . 'lib/settings_handler_class.php'; // 1
require_once $_SERVER["DOCUMENT_ROOT"] . 'lib/theseat_class.php';
require_once $_SERVER["DOCUMENT_ROOT"] . 'lib/module_handler_class.php'; 

$settings = new settings_handler();
$main = new TheSeat($settings);
$modules = new module_handler($settings);

// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 DISPLAY OUTPUT 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

$main->gdpr_consent(); // Include consent dialog if user did not consent to cookies

$template_file = $_SERVER["DOCUMENT_ROOT"] . 'templates/default/general.php';
$main->template = $main->load_template($template_file); // Populate the $template with the $page_content data

// Scan template for "[module=some_module_name]" blocks, and replace with outpus from the corresponding module
$main->template = $modules->load($main->template);

// Send output to client
$main->respond(); // Send HTTP response headers + body