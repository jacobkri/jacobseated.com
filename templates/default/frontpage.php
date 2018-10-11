<?php

$template_css_file = 'templates/default/css/general.css';
$template_css_file = '/'. $template_css_file . '?v=' . filemtime($_SERVER["DOCUMENT_ROOT"] . $template_css_file);
// matric sys
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
       <ol class="width_control"> <li></li> <li>Contact</li> </ol>
      </nav>
    </header>
    <article id="main_content">
      <div id="splash">
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
          <h2>About</h2>
          <p>My interests are currently in technology and business, and I like to create things to solve problems and make life easier for myself and other people. Another interest i have is <i>investing</i> in <i>stocks</i>.</p></p>
          <p>One day I would love to join a startup or build my own company.</p>
          <p>This website was created to showcase some of my work. Use the top navigation to see examples of things I have made. My main projects are linked from the <a href="/#projects">My Projects</a> section.</p>
      </section>

      <section class="content_wrap fade_in_delayed width_control">
          <h2>My Superpowers</h2>
          <p>I have a broad set of skills with a <b>&lt;strong&gt;</b> focus on coding, and most things to do with Web Development.
             I am also very creative, and I know how to use several different programs for video and photo editing.</p>
          <p>Since my main operating system is now Linux (dual boot with Windows), I have learned to use Open Source programs such as: <i>LibreOffice</i>, <i>OBS</i>, <i>Handbrake</i>, <i>Gimp</i> and <i>Kdenlive</i>.
          But, I am also able to use "industry standard" Adobe CC, including <i>Photoshop</i>, <i>Premiere</i>, <i>Illustrator</i> and <i>InDesign</i>.
          <p>I am also familier with <i>3D Studio MAX</i>, although I have not used it for many years. However, it has given me a feel with 3D modeling and Animation, which might be useful at some point.</p>
          <p>Some years ago, I used to make animated Gifs. But, I have since moved on to animated SVGs using CSS and JavaScript.</p>
          <p>When I work, I am usually very independent. I solve problems on my own, and come up with my own solutions.
             I learned early on to use Google before asking questions. It seems like a good approach. Though sometimes it may be faster to just ask.</p>
      </section>

      <section class="content_wrap fade_in_delayed width_control">
          <h2>The History</h2>
          <p>I started coding and creating websites as a kid, first using plain <b>notepad</b> in Windows, later moving on to editors with <i>Syntax Highlighting</i>.
          Now, I mainly use Linux with <b>Eclipse</b> and <b>Visual Studio Code</b>.</p>

          <p><b>January 2013</b> I decided to seak an unpaid internship for a few months as a Web Developer. Doing this time,
             I was allowed to improve my existing skills with <i>HTML</i>, <i>CSS</i>, <i>JavaScript</i> and <i>PHP</i>.</p>
          <p>In addition, I also learned more about <i>Linux</i>, how to use <i>AWS (Amazon Web Services)</i>, <i>Git</i>, and <i>Bootstrap</i>.</p>
          <p>It was doing this time I decided to go back to school to get an education. However, first I had to finish high school, which took me a few years. This is required to join higher education in Denmark.</p>

          <p><b>October 2017</b> I started studying <i>Multimedia Design and Communication</i> at <i>KEA</i> (Copenhagen School of Design and Technology),
             and expect to complete my education on time.</p>
          <p>This education has a broad focus, including many of my existing interests. Including <i>video</i>, <i>marketing</i>, <i>business</i>, and <i>web development</i>.</p>
      </section>

    </article>

<footer id="site_footer">
-
</footer>

<script>
let alreadySlided = false;
window.addEventListener("scroll", isInView('#projects'), false);


function isInView(elementToCheck) {
  let projects = document.querySelector(elementToCheck);
  let position = projects.getBoundingClientRect();
  let winHeight = (window.innerHeight || document.documentElement.clientHeight);
  if ((position.y <= winHeight-200) || (alreadySlided)) {
    console.log(position.y + ' ' + (winHeight-200));
    let odd = document.querySelector('.odd');
    let even = document.querySelector('.even');
    odd.className += " right_slide_in";
    even.className += " left_slide_in";
    alreadySlided = true;
    // window.removeEventListener("scroll", isInView);
  }
}
</script>

</body>

</html>
LOADTEMPLATE;
