<!-- 
==============================================================
PAGE: 404 NOT FOUND
--------------------------------------------------------------
File Path: /Pages/404.php

THIS IS DEFAULT PAGE DONT DELETE IT

Purpose:
- Displayed when a requested page does not exist.
- This is the default error page rendered via `Core\Http::render404()`
  when neither a static page nor a controller is found.

Includes:
- <head> partial → loads meta tags, CSS, favicon
- <header> partial → for consistent page header
- Custom 404 content inside a styled <section>

Rendering Logic:
- Automatically triggered by the framework when a route is not matched.
- Do not use this page manually in routes; it's auto-handled.

==============================================================
-->
<!DOCTYPE html>
<html lang="en">

<head>
<title>404 Not Found</title>
<?php Core\Utils::partial('head'); ?>
</head>
<body>
    <!-- Header Start -->
    <?php Core\Utils::partial('header'); ?>
    <!-- Header End -->
    <!-- Slidebar end -->
    <section >
       <h1>Page not found</h1>
    </section>
    <script type="text/javascript" src="/js/bundle.min.js"></script>
</body>

</html>
