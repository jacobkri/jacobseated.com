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
<html lang="en">

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
      <nav id="navigation">
       {$this->page_content['site_nav']}
      </nav>
    </header>
    <article id="main_content">
{$page_content['text_html']}
    </article>

    <footer id="site_footer">
    </footer>

    <script>
    "use strict";

    document.addEventListener("DOMContentLoaded", function(event) {
        let burgerState = false;
        let burgerButton = document.querySelector("#burgerButton");
        let navigation = document.querySelector("#navigation");

        let article = document.querySelector("article");

        burgerButton.addEventListener("click", toggleBurger);

        function toggleBurger() {
            if (burgerState === false) {
                navigation.className = "burgerOpen";
                burgerState = true;

                article.className = "blur_element";

                burgerButton.innerHTML = '☕';
            } else {
                navigation.className = "burgerClosed";
                burgerState = false;

                article.className = "deblur_element";
                burgerButton.innerHTML = '☰';
            }
        }
    });
    </script>
</body>

</html>
LOADTEMPLATE;
