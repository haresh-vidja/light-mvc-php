<?php
/*
==============================================================
PARTIAL: MENU / NAVIGATION
--------------------------------------------------------------
This is a reusable menu partial (menu.php) located at:
  /Pages/Partials/menu.php

🛠 Purpose:
- Include the site's main navigation menu (header or sidebar)
- Add links to key sections like Home, About, Services, Contact, etc.
- Used across multiple pages for consistent navigation

How to include this partial in a page:
--------------------------------------------------------------
<?php \Core\Utils::partial('menu'); ?>

Use the above PHP code wherever you want the navigation to appear,
     typically inside the <header> or as a sidebar in your layout.

Keep only navigation-related HTML here. Avoid page-specific logic.
==============================================================
*/
?>

<nav>
  <ul>
    <li><a href="/">Home</a></li>
    <li><a href="/about-us">About Us</a></li>
    <li><a href="/services">Services</a></li>
    <li><a href="/contact-us">Contact</a></li>
  </ul>
</nav>