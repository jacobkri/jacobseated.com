<?php

$template_css_file = 'templates/default/css/general.css';
$template_css_file = '/'. $template_css_file . '?v=' . filemtime($_SERVER["DOCUMENT_ROOT"] . $template_css_file);

$page_content['twitter_feed'] = '<aside id="twitter_feed" class="width_control"><a class="twitter-timeline" data-lang="en" data-tweet-limit="3" href="https://twitter.com/JacobSeated?ref_src=twsrc%5Etfw">Tweets by JacobSeated</a> <script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script></aside>';

$template = <<<LOADTEMPLATE
<!doctype html>
<html>

<head>
    <title>☕ JacobSeated.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="StyleSheet" href="{$template_css_file}">
    <link rel="StyleSheet" href="/templates/default/css/roboto.css">
    <link rel="StyleSheet" href="/templates/default/css/open-sans.css">
</head>

<body>
    <header id="site_header">
      <nav>
       <ol class="width_control"> <li>Projects</li> <li>Contact</li> </ol>
      </nav>
    </header>
    <article id="main_content">
      <div class="width_control">
        <header class="fade_in_delayed">
            <h1>JacobSeated.com</h1>
            <p>EPICNESS HAS ARRIVED</p>
        </header>

        <div id="profile_img_box" class="fade_in_delayed">
          <div id="profile_img">
           <div class="radial_stripes"></div>
          </div>
        </div>


          <p class="fade_in_delayed intro_text">I am <b>Jacob Kristensen</b>, and this is my portfolio.</p>


      </div>

      <section>
        <h2 class="fade_in_delayed width_control" id="projects">My Projects</h2>
        <ol class="il">
        <li><a href="https://beamtic.com/" class="even">Beamtic</a></li>
        <li><a href="https://phpphotogallery.com/" class="odd">PHP Photo Gallery</a></li>
        <!-- <li><a href="" class="even">Contact</a></li> -->
        </ol>
      </section>

      <section class="content_wrap fade_in_delayed width_control">
          <h2>My Superpower</h2>
          <p>I have a broad set of skills with a <b>&lt;strong&gt;</b> focus on coding, and most things to do with Web Development.</p>
          <p>When I work, I am usually very independent. I solve problems on my own, and come up with my own solutions.</p>
      </section>

      <section class="content_wrap fade_in_delayed width_control">
          <h2>The History</h2>
          <p>I started coding and creating websites as a kid, first using plain <b>notepad</b> in Windows, later moving on to editors with syntax highlighting.
          Now, I mainly use Linux with <b>Eclipse</b> and <b>Visual Studio Code</b>.</p>
          <p>I am currently studying <b>Multimedia Design and Communication</b> at KEA which I expect to complete soon.</p>
      </section>

    </article>

<footer id="site_footer">
-
</footer>

</body>

</html>
LOADTEMPLATE;
