<!-- 
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
-->

<meta charset="utf-8" />
<link rel="canonical" href="<?php echo Core\Utils::getRequestUrl();?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<?php
$googleSiteVerification=Core\Config::get("googeSiteVerification");
if($googleSiteVerification['enable']){
    ?>
    <meta name="google-site-verification" content="<?php echo $googleSiteVerification['key']; ?>" />
    <?php
}
$googleTagManager=Core\Config::get("googleTagManager");
if($googleTagManager['enable']){
    ?>
    <!-- Google Tag Manager -->
    <script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
    new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
    j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
    'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','<?php echo $googleTagManager['key']; ?>');</script>
    <!-- End Google Tag Manager -->
    <?php
}
?>

<!-- For Mobile Meta -->
<meta name="HandheldFriendly" content="true" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />


<!-- Custom CSS -->
<link rel="stylesheet" type="text/css" href="/css/style.css" />