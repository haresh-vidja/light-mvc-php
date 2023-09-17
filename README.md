### Static File Mapping Guide
In this project, all static assets like CSS, JavaScript, images, and icons are served from the `/Static` directory. The routing mechanism is designed to **map static URLs directly to files inside this folder**, making them accessible under the root of your website.

---
#### Static Directory Structure
```plaintext
/Static/
├── css/
│ └── style.css
├── js/
│ └── script.js
├── images/
│ └── logo.png
└── favicon.ico
````
---
#### URL Mapping Rules
* Files inside `/Static` are **publicly accessible** via root-level URLs.
* This is typically achieved using web server configuration (e.g., Apache or NGINX) to route `/css`, `/js`, `/images`, etc., to the `/Static` directory.
---
#### Examples
| Static File Path | Public URL |
| ------------------------- | -------------------------------------- |
| `/Static/css/style.css` | `https://yoursite.com/css/style.css` |
| `/Static/js/script.js` | `https://yoursite.com/js/script.js` |
| `/Static/favicon.ico` | `https://yoursite.com/favicon.ico` |
| `/Static/images/logo.png` | `https://yoursite.com/images/logo.png` |
---
This setup ensures that all static files are served efficiently without routing through PHP, improving performance and simplifying asset management.

### Project File Structure & Routing Overview
This document explains how the folder structure maps to URLs and how controllers are used when a page is not found under the `/Pages` directory.

---
#### Folder Structure
```plaintext
/
├── Controller/ # Contains controller classes
│ ├── ContactUsController.php
│ ├── BlogController.php
│ ├── ServicesController.php
│ └── ...
│
├── Pages/ # Main public-facing page files
│ ├── index.php # Homepage → https://yoursite.com
│ ├── contact-us.php # Static page → https://yoursite.com/contact-us
│ ├── blog/
│ │ ├── index.php # → https://yoursite.com/blog
│ │ ├── single-post.php # → https://yoursite.com/blog/single-post
│ │ └── categories/
│ │ └── technology.php # → https://yoursite.com/blog/categories/technology
│ │
│ └── Partials/ # Reusable partial components
│ ├── head.php
│ ├── footer.php
│ ├── menu.php
│ ├── layouts/
│ │ └── main-menu.php
│ └── contact/
│ └── form.php
│
├── Static/ # Public assets (CSS, JS, images)
│ ├── css/
│ ├── js/
│ └── images/
│
├── core/ # Core framework files (Utils, Router, etc.)
│ └── Utils.php
│
├── .htaccess # Apache routing rules (if applicable)
├── index.php # Entry point for routing (optional)
└── README.md # Project documentation
```
---
### URL Mapping & Controller Fallback
1. URL first map with static files from `/Static` directory.
2. If not found from static files, URLs first map to files inside the `/Pages` directory.
3. If a file is not found, it falls back to a corresponding controller class in `/Controller`.
#### Examples
| URL | Resolution Path |
| ------------------ | --------------------------------------------------------------------- |
| `/` | `/Pages/index.php` |
| `/contact-us` | `/Pages/contact-us.php` or `/Controller/ContactUsController::index()` |
| `/abc/test` | `/Pages/abc/test.php` |
| `/contact-us/form` | `/Controller/ContactUsController::form()` |
---
### Partial Usage
To include reusable components from `Pages/Partials/`, use:
```php
<?php Core\Utils::partial('head'); ?>
<?php Core\Utils::partial('contact/form'); ?>
```
#### Examples
| Code | Includes File |
| ------------------------------ | --------------------------------------- |
| `Core\Utils::partial('head')` | `/Pages/Partials/head.php` |
| `Core\Utils::partial('footer')` | `/Pages/Partials/footer.php` |
| `Core\Utils::partial('contact/form')` | `/Pages/Partials/contact/form.php` |
| `Core\Utils::partial('layouts/main-menu')` | `/Pages/Partials/layouts/main-menu.php` |
---

### Best Practices

* Use **kebab-case** for URL slugs (e.g., `/contact-us`) and **PascalCase** for controller class names (e.g., `ContactUsController`).
* Place view-only files in `/Pages`, and any logic or backend processing in `/Controller`.
* Use `Partials/` for reusable UI components like headers, footers, menus, and forms.
* Sanitize and validate all inputs passed to controllers or used in rendering views.
---
### Security Tips
* Ensure the routing mechanism sanitizes paths to prevent path traversal vulnerabilities.
Do not allow direct access to partial or controller files via browser URL.
* Keep business logic in controllers and avoid heavy logic in view files.


### Executing Commands via Scheduler

This guide explains how to run backend commands (like cron jobs or background tasks) using the built-in command execution mechanism. Similar to how controllers handle HTTP requests, **commands are handled through classes in the `Scheduler/` directory**.

---
####  How to Execute a Command
Use the following format to run a command from the root directory:
```bash
/Path/To/Root/Directory/Application/run.php <command-name>:<method>
```
####  Example
```bash
/Application/run.php mail-queue:send
```
----------
#### Command Execution Workflow
1.  The system parses the command in the format `command-name:method`.
2.  It looks for a matching class file inside the `/Scheduler/` directory.
3.  The file name should be based on the command name in **PascalCase** with `Schedule` suffix.
4.  The method called corresponds to the second part after the colon (`:`).  
----------
####  File Structure
```plaintext
/Scheduler/
└── MailQueueSchedule.php    # Handles 'mail-queue' commands
```
----------
## ✏️ Example Class: `MailQueueSchedule.php`
```php
<?php
class MailQueueSchedule {
    public function send() {
        // Logic to send queued emails
        echo "Mail queue processed successfully.";
    }
}
```
----------

#### Best Practices

-   Use **kebab-case** for command names (`mail-queue`, `user-cleanup`).
-   Use **PascalCase** for class names (`MailQueueSchedule`, `UserCleanupSchedule`).
-   Ensure each command class is **self-contained**, performs one job, and handles errors gracefully.
-   Log command output if used in cron jobs.
---------
#### Integration with Cron (Linux Example)
Add this to your crontab (`crontab -e`) to run every 10 minutes:
```bash
*/10 * * * * /usr/bin/php /path/to/Application/run.php mail-queue:send >> /var/log/mail-queue.log 2>&1
```
----------

###  Developer Guide: Page Caching System
This document provides a **developer-level overview** of how to use and configure the page caching system in the MVC framework. It helps speed up **static or infrequently updated pages** by serving cached HTML responses, reducing server load and response time.

---
####  How Page Caching Works
- Only applies to **GET requests**.
- When caching is enabled:
  - The system first checks if a cached file exists for the requested URL.
  - If found, it **sends the cached response directly**, skipping all controller logic.
  - If not found, it **renders the page**, and the output is saved to cache for future use.
---
####  Configuration
Caching is configured in the main project config file:
```
Config/config.php
```
#### Required Configuration
```php
"cache" => [
    "enable" => true,              // true = enable page caching
    "key" => "project-version"     // used to generate unique cache file identifiers
],
"minify" => true                   // (optional) true = minify HTML before caching
```
-   `enable`: Enables or disables the caching system.
-   `key`: Used to separate cache entries by project version or environment.
-   `minify`: Removes whitespaces and comments to reduce HTML size.
----------
####  Cache Storage
-   All cache files are stored in the following directory:
```
Temp/Cache/
```
-   Files are automatically named using a **hash of the request URL**, cache key, and minify flag.
-   Each file includes the full HTTP response code and HTML body.
----------
###  Developer Workflow
#### 1. Enable Caching
Ensure the following in your `config.php`:
```php
"cache" => [
    "enable" => true,
    "key" => "v1.0.0"
],
"minify" => true
```
> No need to manually call cache functions — handled automatically during page rendering.
----------
#### Updating Cached Pages
To force regeneration of cached pages:
-   **Change the `cache.key`** in config (e.g., from `v1.0.0` to `v1.0.1`).
-   **Manually delete** files in `Temp/Cache/`.
-   **Use the CLI command**:
```bash
/Application/run.php cache:clear
```
This will remove all cached files and allow fresh content generation.

----------
### Important Considerations
-   ⚠️ **Caching only works for GET requests** — POST requests are always dynamically processed.
-   ⚠️ **Do not cache pages with user-specific content** (e.g., logged-in dashboards).
-   ✅ Use caching for static pages like:
    -   Home, About, Contact, Terms, Blog listing, etc.
----------
###  Benefits for Developers
-   Reduces PHP execution time on repeated requests.
-   Improves perceived page load speed for end users.
-   Easy to integrate without changing your page logic.
-   Clean separation between controller logic and cache behavior.