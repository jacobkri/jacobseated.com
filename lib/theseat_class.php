<?php

// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 The Main Class 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

require_once $_SERVER["DOCUMENT_ROOT"] . 'lib/page_handler_class.php';

class TheSeat {
  private $protocol; // I.e.: HTTP/1.1
    
  private $page_content = array();
  private $response_headers = array();

  private $page_handler; // Page Handler Object
  
  // Caching Headers
  private $last_modified;

  public $etag_header = false;
  
  public $template; // Contains the resulting "text/html" after loading the template file
  public $requested_page; // Contains the "$_GET" value
  public $json_dir; // Path for the json_dir containing individual pages
  
  public function __construct() {
    // Determine the protocol used by the client  
    $this->protocol = $_SERVER["SERVER_PROTOCOL"];
    if (($this->protocol != 'HTTP/1.1') && ($this->protocol != 'HTTP/1.0')) {
      $this->protocol = 'HTTP/1.0';
    }
    
    // Default HTTP response headers
    $this->response_headers['status_code']   = $this->protocol . ' 200 OK'; // Default Status Code
    $this->response_headers['X-Powered-By']  = 'The Seat';
   
    $this->page_handler = new page_handler();
    
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
  foreach (getallheaders() as $name => $value) {
  if (strpos($name, 'If-Modified-Since') !== false) {
    echo $name . "<br>";
  }
}
    // HTTP_IF_MODIFIED_SINCE (If-Modified-Since header) is empty on first view
    // Possibly causing a "notice" in the HTML, which is then cached by the browser.
    // First checking if empty solves this problem
    if(!empty($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {$IF_MODIFIED_SINCE = $_SERVER['HTTP_IF_MODIFIED_SINCE'];} else { $IF_MODIFIED_SINCE = false; }
    if(!empty($_SERVER['HTTP_IF_NONE_MATCH'])) {$IF_NONE_MATCH = $_SERVER['HTTP_IF_NONE_MATCH'];} else { $IF_NONE_MATCH = false; }

    if (($IF_MODIFIED_SINCE == $last_modified_readable) && ($IF_NONE_MATCH == $this->etag_header)) {
        $this->response_headers['status_code'] = $this->protocol. ' 304 Not Modified'; // Set status-code to "304 Not Modified"
        $this->send_headers();
        exit(); // Exit without sending response-body
    } else {
      echo $IF_MODIFIED_SINCE;
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
      $frontpage_access = ($_GET['page'] == 'frontpage') ? true : false;
    } else {
      $this->requested_page = 'frontpage';
      $frontpage_access = false;
    }
    
    // Check if requested page exists, and load page content
    $json_file = $this->page_handler->json_dir . $this->requested_page .'.json';
    
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
      // echo $fparts[1];exit();
      if ($this->requested_page !== 'frontpage') {
        $html_list = '<li><a href="/">Home</a></li>';
      } else {
        $html_list = '';
      }
      foreach ($this->page_handler->page_list as &$file) {
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


