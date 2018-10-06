<?php

$template_css_file = 'templates/default/css/general.css';
$template_css_file = '/'. $template_css_file . '?v=' . filemtime($_SERVER["DOCUMENT_ROOT"] . $template_css_file);

$page_content['twitter_feed'] = '<aside id="twitter_feed"><a class="twitter-timeline" data-lang="en" data-tweet-limit="3" href="https://twitter.com/JacobSeated?ref_src=twsrc%5Etfw">Tweets by JacobSeated</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></aside>';

$template = <<<LOADTEMPLATE
<!doctype html>
<html>

<head>
    <title>JacobSeated.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="StyleSheet" href="{$template_css_file}">
</head>

<body>
    <header id="site_header">
      <nav>
       <ol class="width_control"> <li>Projects</li> <li>Contact</li> </ol>
      </nav>
    </header>
    <article id="main_content" class="width_control">
        <header class="fade_in_delayed">
            <h1>Jacob Kristensen</h1>
            <p>EPICNESS HAS ARRIVED</p>
        </header>

          <div id="profile_img" class="fade_in_delayed">
            <div class="radial_stripes"></div>
          </div>
          <div class="content_wrap">
            <p class="fade_in_delayed">I am <b>Jacob</b>, and this is my portfolio.</p>
          </div>
          <div class="content_wrap">
            <p>I currently study <b>Multimedia Design and Communication</b> at <b>KEA</b> which I expect to complete soon.</p>
          </div>
          <section>
            <h2 class="fade_in_delayed" style="text-align:center;">Projects</h2>
            <ol class="il">
              <li><a href="https://beamtic.com/" class="even">Beamtic</a></li>
              <li><a href="https://phpphotogallery.com/" class="odd">PHP Photo Gallery</a></li>
              <!-- <li><a href="" class="even">Contact</a></li> -->
            </ol>
          </section>
    </article>
{$page_content['twitter_feed']}
{$page_content['consent_message']}
</body>

</html>
LOADTEMPLATE;
