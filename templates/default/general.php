<?php

if(!empty($page_content['custom_css'])) {
  $custom_css_file = 'templates/default/css/' . $page_content['custom_css'];
  $custom_css_file = '/'. $custom_css_file . '?v=' . filemtime($_SERVER["DOCUMENT_ROOT"] . $custom_css_file);
} else {
  $custom_css_file = '';
}

$general_css_file = 'templates/default/css/general.css';
$general_css_file = '/'. $general_css_file . '?v=' . filemtime($_SERVER["DOCUMENT_ROOT"] . $general_css_file);

// matric sys
$template = <<<LOADTEMPLATE
<!doctype html>
<html>

<head>
    <title>☕ JacobSeated.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="StyleSheet" href="{$general_css_file}">
    <link rel="StyleSheet" href="{$custom_css_file}">
    <link rel="StyleSheet" href="/templates/default/css/roboto.css">
    <link rel="StyleSheet" href="/templates/default/css/open-sans.css">
    <meta name="description" content="{$page_content['description']}">
</head>

<body>
    <header id="site_header">
      <nav>
       <ol class="width_control"> <li></li> <li>Skills</li> <li>Projects</li></ol>
      </nav>
    </header>
    <article id="main_content">
{$page_content['text_html']}
    </article>

<footer id="site_footer">
-
</footer>

</body>

</html>
LOADTEMPLATE;
