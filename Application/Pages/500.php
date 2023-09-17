<!-- 
==============================================================
🧾 PAGE: 500 INTERNAL SERVER ERROR
--------------------------------------------------------------
File Path: /Pages/500.php

THIS IS DEFAULT PAGE DONT DELETE IT

Purpose:
- Displayed when a server-side error occurs (unexpected exceptions, 
  database failures, etc.).
- This is the default error page rendered via `Core\Controller::render505()`.

Includes:
- <head> partial → loads meta tags, CSS, favicon
- <header> partial → for consistent page layout
- Simple error message inside a <section> block

Rendering Logic:
- Automatically triggered when `render505()` is called from a controller.
- Useful for unexpected application failures or fallback error handling.

Customize:
- You can enhance the message, add support contact info,
  or link to the homepage for better user experience.

Important:
- Do not expose sensitive debug info on this page in production.

==============================================================
-->

<!DOCTYPE html>
<html lang="en">

<head>
    <?php Core\Utils::partial('head'); ?>
    <title>500-Internal Server Error</title>
</head>

<body>
    <!-- Header Start -->
    <?php Core\Utils::partial('header'); ?>
    <!-- Header End -->
    <!-- Slidebar end -->
    <section>
        <h1>500:Server Error</H1>
    </section>
   
</body>

</html>