<?php
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 CLASSES 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

require_once $_SERVER["DOCUMENT_ROOT"] . 'lib/theseat_class.php';

$main = new TheSeat();


// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 DISPLAY OUTPUT 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

$main->gdpr_consent(); // Include consent dialog if user did not consent to cookies

$template_file = $_SERVER["DOCUMENT_ROOT"] . 'templates/default/general.php';
$main->template = $main->load_template($template_file); // Populate the $template with the $page_content data
$main->etag_header = md5($main->template); // Create etag based on populated template

// Send output to client
$main->send_headers(); // Send HTTP response headers before echo'ing out the response body
echo $main->template; // Response body (typically text/html)