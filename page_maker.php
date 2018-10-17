<?php

   // Interface to create .json datafiles
$json_dir  = $_SERVER["DOCUMENT_ROOT"] . 'json/';
$json_file = '';
$message   = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $page_content = array();
  
  if((!empty($_POST['title'])) && (!empty($_POST['text_html']))) { // TEST REQUIRED FIELDS
      $text_html_size = strlen($_POST['text_html']);
      
      // REQUIRED FIELDS
      if (!preg_match('/^[A-Za-z0-9_ -]{1,255}$/', $_POST['title'])) {
        echo '<h1>Invalid title</h1><p>The field can include <b>[a-zA-Z0-9_- ]</b></p><p>And can be <b>255</b> characters long.</p>';
        exit();
      } else {
          $post_title = preg_replace('!\s+!', ' ', $_POST['title']);
          $file_name = preg_replace('! !', '-', $post_title); // Without (.ext) extension at this point
      }
      
      if($text_html_size > 63000) {
          echo '<h1>Text/html too long</h1><p>The field can contain a maximum of <b>63000</b> characters.</p>';
          exit();
      }
      // OPTIONAL FIELDS
      if(!empty($_POST['description'])) {
        if (!preg_match('/^[A-Za-z0-9_\., -]{1,255}$/', $_POST['description'])) {
          echo '<h1>Invalid description</h1><p>The field can contain a maximum of <b>255</b> characters.</p>';
          exit();
        }
        $post_description = $_POST['description'];
      } else {$post_description = '';}
      
      if (!empty($_POST['custom_css'])) { // OPTIONAL FIELD
        if (!preg_match('/^[A-Za-z0-9_\.-]{1,100}$/', $_POST['custom_css'])) {
          echo '<h1>Invalid custom CSS file</h1><p>The field can include <b>[a-zA-Z0-9_\. -]</b> and contain a maximum of <b>100</b> characters.</p>';
          exit();
        }
        $post_custom_css = $_POST['custom_css'];
      } else {
        $post_custom_css = '';
      }
      
      
      // If the POST passed validation, create the .json file
      $page_content['title']         = $_POST['title'];
      $page_content['description']   = $post_description;
      $page_content['custom_css']    = $post_custom_css;
      $page_content['text_html']     = $_POST['text_html'];
      $page_content['last_modified'] = time(); // May be used for caching in the Last-Modified header
      
      $json_data = json_encode($page_content);
      $json_file = $json_dir . $file_name . '.json';
      
      if (!file_exists($json_file)) { // If the page already exists
        $message = '<p>Page successfully created!</p><p>Want to create another one?</p>';
      } else {
        $message = '<p>Page successfully updated!</p>';
      }
      
      if(file_put_contents($json_file, $json_data) === false) {
        $message = '<p><b>Error:</b> Failed to create page. Check that your server has write permission to the <b>json/</b> directory.</p><p><b>File:</b> <i>'.$json_file.'</i></p>';
      } else {
        file_put_contents($json_dir . $file_name . '_html.html', $page_content['text_html']); // Save copy of HTML for easy editing
      }
    $message = '<div id="message">' . $message . '</div>';
  } else { // If the REQUIRED FIELDS are empty 
    echo 'What are you trying to accomplish? (Rhetorical question)';exit();
  }
}

?><!doctype html>
<html>

  <head>
   <title>Page Maker</title>
   <link rel="StyleSheet" href="/templates/default/css/roboto.css">
   <link rel="StyleSheet" href="/templates/default/css/open-sans.css">
   <style type="text/css">
     form {margin:1em 0;width:100%;}
     form input {display:block;margin:0 0 1em;height:1.2em;padding:0.5em;width:100%;}
     form textarea {margin:0 0 1em;padding:0.5em;width:100%;min-height:300px;max-width:100%;}

    .button {width:8em;height:2em;background:rgb(210,210,210);border:none;cursor:pointer;}
    .button:hover {background:rgb(230,230,230);}
    label {font-family:"Roboto";}
    #message {background:rgb(240,240,240);border-radius:1em;padding:0.5em;width:100%;}
    article {width:50%;min-width:300px;max-width:1600px;margin:0 auto 200px;}
   </style>
  </head>

  <body>
   <article>
    <h1>Page Maker</h1>
    <p>Saves data to <i>.json</i> files for use with site templates.</p>
    <?php echo $message; ?>
    <form action="page_maker.php" method="post">
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" placeholder="Title" autocomplete="off">
      
      <label for="description">Description (Optional):</label>
      <input type="text" name="description" id="description" placeholder="Description" autocomplete="off">
      
      <label for="template">Template css file <b>I.e:</b> <i>yourpage.css</i> <b>||</b> <i>general.css</i> (Optional):</label>
      <input type="text" name="custom_css" id="custom_css" placeholder="frontpage.css">
      <textarea name="text_html" rows="4" cols="30" class="inputs" placeholder="text/html"></textarea>
      <input type="submit" class="button" value="Create">
    </form>
    <h2>Readme</h2>
    <p>Individual pages can be coded in your favorite HTML editor. When done, copy the source (from within the <i>&lt;article&gt;</i> part of the HTML) and paste it here to generate the <b>.json</b>.
    A copy of the HTML source is saved along with the json file, in case you should later need it.</p>
    <p>If you have any custom CSS for the page, remember to manually save a css file in <b>templates/[template_name]/css</b> and enter the name of the file in the template field.</p>
   </article>
  </body>

</html>