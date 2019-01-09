<?php

// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨
//          😎 Interface to create .json datafiles 😎
// ✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨✨

require $_SERVER['DOCUMENT_ROOT'] . 'lib/settings_handler_class.php';

$html_options_list = ''; // When no pages are available, do not show <select> <obtion>'s
$js_page_list = '{}'; // Empty JS object
$message = ''; // Used for Success & Error messages later

$s = new settings_handler(); // Load shared settings

// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺ Functions ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺

function create_lists($page_list,  $selected_file=false) {
  $lists = false;
  $js_object = '{';$options_list = '<select id="page_selector_list">';
  $max = count($page_list);$i = 0;
  if ($max >= 1) {
    while($i < $max) {
      if ($selected_file == $page_list["$i"]) {
        $selected = ' selected';
      } else {$selected = '';}

      if (preg_match('/^([^\.]{1,250})\.json$/', $page_list["$i"], $matches, PREG_OFFSET_CAPTURE)) {
        $page_name = $matches[1][0]; // Grab Page name from the file name (We could also load the .json, but meh...)
        $js_object .= '"'.$page_name . '":"' . $page_list["$i"].'"'; // Build js object
        $options_list .= '<option value="'.$page_list["$i"].'"'.$selected.'>' . $page_name . '</option>';
      }
      ++$i;
      if ($i !== $max) { // Only add "," to js object if not the last item in the list
        $js_object .= ',';
      }
    }
    $js_object .= '}';$options_list .= '</select><button id="new_page" class="button">New Page</button>';
    $lists = array($js_object, $options_list);
  }
  return $lists;
}

// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺ Blah blah blah ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺

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
          $json_file_name = $file_name . '.json';
          $html_file_name = $file_name . '.html';
      }
      
      if($text_html_size > 63000) {
          echo '<h1>Text/html too long</h1><p>The field can contain a maximum of <b>63000</b> characters.</p>';
          exit();
      }
      // OPTIONAL FIELDS
      if(!empty($_POST['description'])) {
        if (!preg_match('/^[A-Za-z0-9_\., \-!]{1,255}$/', $_POST['description'])) {
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
      if(isset($_POST['nav_include'])) {$include_in_navigation = 1;} else {$include_in_navigation = 0;}
      
      
      // If the POST passed validation, create the .json file
      $page_content['title']                    = $_POST['title'];
      $page_content['description']              = $post_description;
      $page_content['custom_css']               = $post_custom_css;
      $page_content['text_html']                = $_POST['text_html'];
      $page_content['last_modified']            = time(); // May be used for caching in the Last-Modified header
      $page_content['include_in_navigation']    = $include_in_navigation;
      
      $json_data = json_encode($page_content);
      $json_file = $s->json_dir . $json_file_name;
      
      if (!file_exists($json_file)) { // If the page does not exists
        $message = '<p>Page successfully created!</p><p>Want to create another one?</p>';
      } else { // If the page already exists
        $message = '<p>Page successfully updated!</p>';
      }
      
      if(file_put_contents($json_file, $json_data) === false) {
        $message = '<p><b>Error:</b> Failed to create page. Check that your server has write permission to the <b>json/</b> directory.</p><p><b>File:</b> <i>'.$json_file.'</i></p>';
      } else {
        // The file was successfully created/updated
        file_put_contents($s->html_dir . $html_file_name, $page_content['text_html']); // Save copy of HTML for editing and future caching

        $lists = create_lists($s->page_list, $json_file_name); // Create <option> list ($lists[1]) and JS object ($lists[0])
      }
    $message = '<div id="message">' . $message . '</div>';
  } else { // If the REQUIRED FIELDS are empty 
    echo 'What are you trying to accomplish? (Rhetorical question)';exit();
  }
} else {
  $lists = create_lists($s->page_list); // Create <option> list ($lists[1]) and JS object ($lists[0])
}

if ($lists !== false) {
  $html_options_list = 'Edit existing page: ' . $lists[1];
  $js_page_list = $lists[0];
}

// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺ HTML+PHP Spageti ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺
// ☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺☺

?><!doctype html>
<html lang="en">

  <head>
   <title>Page Maker</title>
   <link rel="StyleSheet" href="/templates/default/css/roboto.css">
   <link rel="StyleSheet" href="/templates/default/css/open-sans.css">
   <style type="text/css">
     body {background:rgb(240,240,240);color:rgb(60,60,60);font-family:"Open Sans", Georgia, serif;}
     form {margin:1em 0;width:100%;}
     form input {display:block;margin:0 0 1em;height:1.2em;padding:0.5em;width:100%;}
     form textarea {margin:0 0 1em;padding:0.5em;width:100%;min-height:300px;max-width:100%;}
     .ui_style {background:rgb(250,250,250);color:rgb(50,50,50);border:1px solid rgb(200,200,200);font-family:"Roboto", serif;}

    .button {width:8em;height:2.5em;background:rgb(135,145,235);border:none;cursor:pointer;color:rgb(245,245,245);}
    .button:hover {background:rgb(155,165,255);color:rgb(245,245,245);}
    label {font-family:"Roboto";}
    #message {background:rgb(240,240,240);border-radius:1em;padding:0.5em;width:100%;}
    article {width:50%;min-width:300px;max-width:1600px;margin:0 auto 200px;}
    select {margin:0 1em;}

    @font-face {
      font-family: "Open Sans";
      src: url("/fonts/OpenSans-Regular.woff2") format("woff2"),
      url("/fonts/OpenSans-Regular.ttf") format("truetype");
    }
    @font-face {
      font-family: "Roboto";
      src: url("/fonts/Roboto-Regular.woff2") format("woff2");
    }
   </style>
  </head>

  <body>
   <article>
    <h1>Page Maker</h1>
    <p>Saves data to <i>.json</i> files for use with site templates.</p>
    <div id="page_selector"><?php echo $html_options_list; ?></div>
    <?php echo $message; ?>
    <form action="page_maker.php" method="post">
      <label for="title">Title:</label>
      <input type="text" name="title" id="title" placeholder="Title" class="ui_style">
      
      <label for="description">Description (Optional):</label>
      <input type="text" name="description" id="description" placeholder="Description" class="ui_style">
      
      <label for="template">Template css file <b>I.e:</b> <i>yourpage.css</i> <b>||</b> <i>general.css</i> (Optional):</label>
      <input type="text" name="custom_css" id="custom_css" placeholder="frontpage.css" class="ui_style">
      <label for="nav_include">Include in Navigation:</label>
      <input style="width:auto;display:inline;" type="checkbox" name="nav_include" id="nav_include" checked>
      <textarea name="text_html" id="text_html" rows="4" cols="30" class="ui_style" placeholder="text/html"></textarea>
      <input type="submit" class="button" value="Create" id="edit_button">
    </form>
    <h2>Readme</h2>
    <p>Individual pages can be coded in your favorite HTML editor. When done, copy the source (from within the <i>&lt;article&gt;</i> part of the HTML) and paste it here to generate the <b>.json</b>.
    A copy of the HTML source is saved along with the json file, in case you should later need it.</p>
    <p>If you have any custom CSS for the page, remember to manually save a css file in <b>templates/[template_name]/css</b> and enter the name of the file in the template field.</p>
   </article>

   <script>
   let jsonDir = "/json/"; // Root-relative path to json directory
   let pages_array = <?php echo $js_page_list. ';'; ?>
   
   // Form Fields
   titleField       = document.querySelector("#title");
   cssFilePathField = document.querySelector("#custom_css");
   descriptionField = document.querySelector("#description");
   textHtmlField    = document.querySelector("#text_html");
   editButton       = document.querySelector("#edit_button");

   optionsListHTML = '';

   document.addEventListener("DOMContentLoaded", main);
   
   function main() {
    optionsList = document.querySelector("#page_selector_list");

    optionsListHTML = optionsList.innerHTML; // Remember initial HTML content

    if (optionsList !== null) {
      jsonURL = jsonDir + optionsList.value;
      
      updateForm(); // Initial view

      optionsList.addEventListener("change", function(){
        jsonURL = jsonDir + optionsList.value;
        updateForm();
      });
      newPageButton = document.querySelector("#new_page");
      newPageButton.addEventListener("click", clearForm);
    }
   }

   async function loadJson(JSON_URL) {
    // Load json into jsonObject, then save it in wp_data for later use
    let jsonObject = await fetch(JSON_URL);
    jsonData = await jsonObject.json();
  
    if (jsonData.length < 1) {
      return false;
    } else {
	  return jsonData;
    }
  }
  async function updateForm() {
    jsonData = await loadJson(jsonURL);

    titleField.value       = jsonData['title'];
    descriptionField.value = jsonData['description'];
    textHtmlField.value    = jsonData['text_html'];
    cssFilePathField.value = jsonData['custom_css'];
    editButton.value       = 'Update';
    console.log(jsonData['include_in_navigation']);
    if(jsonData['include_in_navigation'] !== 1) {
      
      document.querySelector("#nav_include").checked = false;
    }
  }
  function clearForm() {

    options = document.querySelectorAll("option");
    //console.table(options);

    options.forEach(function(element) {
      // Technically, it is probably best to remove selected attribute before we add the "New" <option selected>
      // Note. The <option> list is not used for anything when creating a new page
      if (element.hasAttribute("selected")) {
        element.removeAttribute("selected");
      }
    });

    optionsList.innerHTML  = optionsListHTML + '<option selected>New</option>';
    titleField.value       = '';
    descriptionField.value = '';
    textHtmlField.value    = '';
    cssFilePathField.value = '';
    editButton.value       = 'Create';
  }

   </script>
  </body>

</html>