<?php
 // Simple Portfolio site powered by PHP

$template_file = $_SERVER["DOCUMENT_ROOT"] . 'templates/default/frontpage.php';
$page_content = array();

$protocol = $_SERVER["SERVER_PROTOCOL"];
if (($protocol != 'HTTP/1.1') && ($protocol != 'HTTP/1.0')) {
  $protocol = 'HTTP/1.0';
}

$response_headers = array();
$response_headers['status_code']   = $protocol . ' 200 OK'; // Default Status Code
$response_headers['X-Powered-By']  = 'The Seat';
$response_headers['Cache-Control'] = 'must-revalidate';
$response_headers['Expires']       = '-1';

$content_file                      = 'seated.php'; // This is a semi-static project, so we just use filetime of seated.php for "Last-Modified"
$last_modified                     = filemtime($_SERVER["DOCUMENT_ROOT"] . $content_file);
$last_modified_readable            = gmdate("D, d M Y H:i:s", $last_modified).' GMT';
$response_headers['Last-Modified'] = $last_modified_readable ;

// GDPR Consent Message
if (!empty($_SERVER['CONTINENT_CODE'])) {
  $continent_code = $_SERVER['CONTINENT_CODE'];
} else {
  $continent_code = 'EU'; // Assume EU if empty or undefined
}

if ($continent_code == 'EU') {
    $page_content['consent_message'] = '<article id="data_consent" class="cbox">
    <h1>Consent to the use of Personal Data and Cookies</h1>
    <p>Website owners are required to gather consent from visitors in the <b>EU</b> before handling personal data and placing cookies on users devices.</p>
    <p>This website, and our partners, use cookies to customize ads and content. The traffic on the page is logged for analysis and security purposes.</p>
    <ol id="consent_options">
      <li><button class="button ui_button" data-action="dataConsent">Accept</button></li>
      <li><a href="/privacy" class="button" rel="nofollow">More info</a></li>
    </ol>
    </article>';
}

// Finalize Output
require $template_file; // Include the template used when responding to a HTTP request
$etag_header = md5($template); // Create Etag from template
$response_headers['Etag'] = $etag_header;


// Check if 304 Not Modified
if (($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $last_modified_readable) && ($_SERVER['HTTP_IF_NONE_MATCH'] == $etag_header)) {
  $response_headers['status_code'] = $protocol. ' 304 Not Modified'; // Set status-code to "304 Not Modified"
  send_headers($response_headers);
  exit(); // Exit without sending response-body
}

// Send output to client
send_headers($response_headers); // Send HTTP response headers before echo'ing out the response body
echo $template; // Response body (typically text/html)


// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 FUNCTIONS 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

function send_headers($response_headers) {
  // http_response_code($response_headers['status_code']);
  
  header($response_headers['status_code']); // Send Status-Line (This should always go first See: rfc2616)
  unset($response_headers['status_code']); // Unset the status_code to avoid sending it again
  foreach ($response_headers as $header_name => $header_content) { // Iterate over array (Loop through)
    header($header_name . ': ' . $header_content); // Send the response header
    unset($response_headers["$header_name"]); // Not critical, but may be good practice on larger web-apps (lets see what happens)
  }
}