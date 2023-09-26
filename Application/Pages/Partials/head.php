<?php
/*
==============================================================
PARTIAL: HEAD
--------------------------------------------------------------
This is a reusable header partial (head.php) located at:
  /Pages/Partials/head.php

Purpose:
- Include <head> content for your HTML pages
- Add meta tags (charset, viewport, SEO)
- Load CSS stylesheets
- Set favicon and other global head elements

How to include this partial in a page:
--------------------------------------------------------------
<?php \Core\Utils::partial('head'); ?>

Use the above PHP code inside your page template 
     (e.g., /Pages/about-us.php or /Pages/abc/index.php) 
     before opening the <body> tag.

Example content inside this file:
- <meta charset="UTF-8">
- <meta name="viewport" content="width=device-width, initial-scale=1.0">
- <link rel="stylesheet" href="/css/style.css">
- <link rel="icon" href="/favicon.ico">

Keep only shared and head-level content here.
==============================================================
*/
?>


<meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  
  <meta name="description" content="">
  <meta name="keywords" content="">

  <!-- Favicons -->
  <link href="img/favicon.png" rel="icon">
  <link href="img/apple-touch-icon.png" rel="apple-touch-icon">

  <!-- Fonts -->
  <link href="https://fonts.googleapis.com" rel="preconnect">
  <link href="https://fonts.gstatic.com" rel="preconnect" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

  <!-- Vendor CSS Files -->
  <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link href="vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
  <link href="vendor/aos/aos.css" rel="stylesheet">
  <link href="vendor/glightbox/css/glightbox.min.css" rel="stylesheet">
  <link href="vendor/swiper/swiper-bundle.min.css" rel="stylesheet">

  <!-- Main CSS File -->
  <link href="css/main.css" rel="stylesheet">
  