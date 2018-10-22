<?php
 // Simple Portfolio site powered by PHP

$main = new TheSeat();


$main->gdpr_consent(); // Include consent dialog if user did not consent to cookies

$template_file = $_SERVER["DOCUMENT_ROOT"] . 'templates/default/general.php';
$main->template = $main->load_template($template_file); // Populate the $template with the $page_content data
$main->etag_header = md5($main->template); // Create etag based on populated template

// Send output to client
$main->send_headers(); // Send HTTP response headers before echo'ing out the response body
echo $main->template; // Response body (typically text/html)




// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 FUNCTIONS 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

class TheSeat {
  private $protocol; // I.e.: HTTP/1.1
    
  private $page_content = array();
  private $response_headers = array();

  // File paths
  private $json_dir;
  
  // Caching Headers
  private $last_modified;

  public $etag_header = false;
  
  public $template;
  public $requested_page;
  
  public function __construct() {
    $this->json_dir = $_SERVER["DOCUMENT_ROOT"] . 'json/';
    
    // Determine the protocol used by the client  
    $this->protocol = $_SERVER["SERVER_PROTOCOL"];
    if (($this->protocol != 'HTTP/1.1') && ($this->protocol != 'HTTP/1.0')) {
      $this->protocol = 'HTTP/1.0';
    }
    
    // Default HTTP response headers
    $this->response_headers['status_code']   = $this->protocol . ' 200 OK'; // Default Status Code
    $this->response_headers['X-Powered-By']  = 'The Seat';
   
    
    $this->load_content(); // First load content
    $this->handle_caching(); // Check if content was updated since last visit. Etc.
  }
  
  private function handle_caching() {
    $this->response_headers['Cache-Control'] = 'must-revalidate';
    $this->response_headers['Expires']       = '-1';
    $last_modified_readable                  = gmdate("D, d M Y H:i:s", $this->last_modified).' GMT';
    $this->response_headers['Last-Modified'] = $last_modified_readable;
    if ($this->etag_header !== false) {
      $this->response_headers['Etag']        = $this->etag_header;
    }
    
    // Check if 304 Not Modified
    if (($_SERVER['HTTP_IF_MODIFIED_SINCE'] == $last_modified_readable) && ($_SERVER['HTTP_IF_NONE_MATCH'] == $this->etag_header)) {
        $this->response_headers['status_code'] = $this->protocol. ' 304 Not Modified'; // Set status-code to "304 Not Modified"
        $this->send_headers();
        exit(); // Exit without sending response-body
    }
    
  }
  
  public function send_headers() {
    header($this->response_headers['status_code']); // Send Status-Line (This should always go first See: rfc2616)
    unset($this->response_headers['status_code']); // Unset the status_code to avoid sending it again
    foreach ($this->response_headers as $header_name => $header_content) { // Iterate over array (Loop through)
      header($header_name . ': ' . $header_content); // Send the response header
      unset($this->response_headers["$header_name"]); // Not critical, but may be good practice on larger web-apps (lets see what happens)
    }
  }

  private function load_content() {
    // Check if requested page is valid
    if(isset($_GET['page'])) {
      if(!preg_match('/^[A-Za-z0-9_-]{1,255}$/', $_GET['page'])) {
        // Bad request if something suspicious is going on
        header($this->protocol. ' 400: BAD REQUEST');
        echo 'What are you trying to accomplish? (Rhetorical question)';
        exit();
      } else {
        $this->requested_page = $_GET['page'];
      }
      $frontpage_access = $r = ($_GET['page'] == 'frontpage') ? true : false;
    } else {
      $this->requested_page = 'frontpage';
      $frontpage_access = false;
    }
    
    // Check if requested page exists, and load page content
    $json_file = $this->json_dir . $this->requested_page .'.json';
    if ((!file_exists($json_file)) || ($frontpage_access === true)) {
      // Since "frontpage" is the default (Accessible from "/", do not allow access to this file.
      header($this->protocol. ' 404: Not Found');
      exit();
    } else {
      $json_file_data = file_get_contents($json_file);
    }  
    $this->page_content             = $this->page_content + json_decode($json_file_data, true);
    $this->last_modified            = $this->page_content['last_modified']; // Last Modified timestamp from the .json data
    $this->page_content['site_nav'] = $this->build_navigation();
  }
  private function build_navigation() {
      $files = array_slice(scandir($this->json_dir), 2); // Remove ".." and "." with array_slice()
      
      if ($this->requested_page !== 'frontpage') {
        $html_list = '<li><a href="/">Home '.$fparts[1].'</a></li>';
      } else {
        $html_list = '';
      }
      foreach ($files as &$file) {
        if(preg_match('/^([A-Za-z0-9_-]{1,255})\.json$/',  $file, $fparts)) {
          if(($fparts[1] !==  'frontpage') && ($fparts[1] !== $this->requested_page)) {
            $html_list .= '<li><a href="/?page='.$fparts[1].'">' . $fparts[1] . '</a></li>';
          }
        }
      }
      return '<button id="burgerButton">☰</button><ol class="width_control">' . $html_list . '</ol>';
  }
  public function load_template($template_file) {
    $page_content = $this->page_content; // $page_content used to populate $template with data
    require $template_file; // Include the template used when responding to an HTTP request
    return $template;
  }

  public function gdpr_consent() { // This method is not yet in use
    $consent_cookie = 'granted'; // Not in use yet.
    
    // GDPR Consent Message
    if (!empty($_SERVER['CONTINENT_CODE'])) {
        $continent_code = $_SERVER['CONTINENT_CODE'];
    } else {
        $continent_code = 'EU'; // Assume EU if empty or undefined
    }
    
    if (($continent_code == 'EU') && ($consent_cookie !== 'granted')) {
        $this->page_content['consent_message'] = '<article id="data_consent" class="cbox">
    <h1>Consent to the use of Personal Data and Cookies</h1>
    <p>Website owners are required to gather consent from visitors in the <b>EU</b> before handling personal data and placing cookies on users devices.</p>
    <p>This website, and our partners, use cookies to customize ads and content. The traffic on the page is logged for analysis and security purposes.</p>
    <ol id="consent_options">
      <li><button class="button ui_button" data-action="dataConsent">Accept</button></li>
      <li><a href="/privacy" class="button" rel="nofollow">More info</a></li>
    </ol>
    </article>';
    } else {
      $this->page_content['consent_message'] = '';
    }
  }

}


