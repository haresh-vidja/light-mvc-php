<!-- 
==============================================================
PAGE: HOME / INDEX
--------------------------------------------------------------
File Path: /Pages/index.php

THIS IS DEFAULT PAGE DONT DELETE IT

🛠 Purpose:
- This is the default landing page of the website.
- It is served when users visit the root URL: https://yoursite.com/

Includes:
- <head> partial → Loads meta tags, stylesheets, favicon
- <header> partial → Displays the site’s header/navigation
- <footer> partial → Shared footer with scripts or links
- Custom content inside <section class="main-banner">

Rendering Logic:
- This file is automatically loaded when URL is "/"
- No controller is required unless additional backend logic is needed

Customize:
- Modify banner section for hero image, intro text, or CTA
- Add additional sections or dynamic content as needed

==============================================================
-->

<!DOCTYPE html>
<html lang="en">

<head>
	<title>Welcome to Your Site</title>
	<?php Core\Utils::partial('head'); ?>
</head>

<body>
	<!-- Header Start -->
	<?php Core\Utils::partial('header'); ?>
	<!-- Header End -->
	<section class="main-banner">
	Hello This is index page content
	</section>
	<?php Core\Utils::partial('footer'); ?>

	<script src="/js/script.js"></script>
	
</body>

</html>