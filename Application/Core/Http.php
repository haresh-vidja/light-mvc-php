<?php
namespace Core;

use Core\Logger;

/**
 * ============================================================================
 * Class: Http
 * ----------------------------------------------------------------------------
 * Purpose:
 * - Central component for handling HTTP requests in the application.
 * - Resolves static page rendering or dynamic controller-based execution.
 * - Supports caching, redirects, and file uploads.
 * ============================================================================
 */
class Http
{
    private $url = "";             // Requested URL path (e.g., 'contact-us')
    private $pageFilePath = "";    // Full file path to the corresponding page

    /**
     * Constructor
     * - Initializes the requested URL and handles redirects and path resolution.
     */
    public function __construct()
    {
        // Set requested URL or fallback to 'index'
        $this->url = isset($_GET['url']) ? $_GET['url'] : "index";

        // Store URL in global utility for reuse
        Utils::$url = $this->url;

        // Handle permanent redirects (301) if configured
        $this->permenantRedirect();

        // Resolve file path for static page
        $this->getFile();
    }

    /**
     * Handle permanent redirects using the /Config/Redirect.php map.
     */
    private function permenantRedirect()
    {
        $filePath = APP_ROOT . "Config" . DS . "Redirect.php";

        if (file_exists($filePath)) {
            $redirectArray = include_once($filePath);

            if (isset($redirectArray[$this->url]) && $this->url !== $redirectArray[$this->url]) {
                if (ob_get_length() > 0) ob_clean();

                header("Location: /" . $redirectArray[$this->url], true, 301);
                exit;
            }
        }
    }

    /**
     * Check if the current request is a POST request.
     */
    public function isPost()
    {
        return strtoupper($_SERVER['REQUEST_METHOD']) === 'POST' && !empty($_POST);
    }

    /**
     * Check if the current request is an AJAX request.
     */
    public function isAjax()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Execute the current request.
     * - Priority: Static page → Controller → 404
     */
    public function execute()
    {
        if (file_exists($this->pageFilePath)) {
            $this->renderPage(); // Serve static page
        } else {
            if (!$this->executeController()) {
                $this->render404(); // Serve 404 error page
            }
        }
    }

    /**
     * Attempt to load and execute a controller method.
     *
     * @return bool True if successful, false otherwise.
     */
    private function executeController()
    {
        list($controller, $action) = $this->parseURL();

        $controllerName = Utils::camelize($controller) . "Controller";
        $controllerFilePath = APP_ROOT . "Controllers" . DS . $controllerName . ".php";

        if (file_exists($controllerFilePath)) {
            include_once($controllerFilePath);

            if (class_exists($controllerName)) {
                $controllerObj = new $controllerName();

                if (method_exists($controllerObj, $action)) {
                    $controllerObj->$action();
                } else {
                    Logger::error("Method $action not found in class $controllerName.");
                    return false;
                }
            } else {
                Logger::error("Class $controllerName not found in file.");
                return false;
            }
        } else {
            Logger::error("Controller file missing: $controllerFilePath");
            return false;
        }

        return true;
    }

    /**
     * Render a static page.
     *
     * @param int $code HTTP status code (default: 200 OK)
     */
    private function renderPage($code = 200)
    {
        ob_start();
        http_response_code($code);
        require_once($this->pageFilePath);

        $content = (Config::get('minify') === true)
            ? self::compressHTML(ob_get_contents())
            : ob_get_contents();

        self::savePageToCache($content, $code);
        Logger::debug("From Code :: " . self::getRequestUrl());
        exit();
    }

    /**
     * Render a 404 Not Found error page.
     */
    private function render404()
    {
        $this->pageFilePath = PAGE_DIR . "404.php";
        $this->renderPage(404);
    }

    /**
     * Determine the PHP file path for the requested static page.
     */
    private function getFile()
    {
        $filepath = str_replace("/", DS, $this->url);

        if (is_dir(PAGE_DIR . $filepath)) {
            $filepath .= DS . "index";
        }

        $this->pageFilePath = PAGE_DIR . $filepath . ".php";
    }

    /**
     * Parse the URL to extract controller and action method.
     *
     * @return array [controller, action]
     */
    private function parseURL()
    {
        $parts = explode("/", $this->url);

        if (!isset($parts[1]) || $parts[1] === "") {
            $parts[1] = "index";
        }

        return $parts;
    }

    /** ====================================
     * 🧠 Utility & Caching Methods
     * ==================================== */

    /**
     * Load page content from cache if available.
     */
    public static function loadPageFromCache($url = null)
    {
        if (empty($_POST)) {
            $path = self::getCaheFilePath($url);

            if (file_exists($path)) {
                $content = file_get_contents($path);
                list($code, $response) = explode("====", $content);

                if (ob_get_length() > 0) ob_clean();

                http_response_code($code);
                echo $response;
                Logger::debug("From Cache :: " . self::getRequestUrl());
                return true;
            }
        }

        return false;
    }

    /**
     * Save the rendered page output to cache.
     */
    public static function savePageToCache($content, $code = 200)
    {
        if (Config::get("cache.enable") === true) {
            $path = self::getCaheFilePath();
            file_put_contents($path, $code . "====" . $content);
        }
    }

    /**
     * Generate the full path to the cache file for a specific URL.
     */
    public static function getCaheFilePath($url = null)
    {
        if (empty($url)) {
            $url = self::getRequestUrl();
        }

        return TEMP_DIR . "Cache" . DS . md5(
            $url . "::" . Config::get("cache.key") . "::" . (Config::get("minify") ? "minified" : "")
        );
    }

    /**
     * Get the full request URL including protocol and path.
     */
    public static function getRequestUrl()
    {
        $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') ? "https" : "http";
        return $protocol . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    }

    /**
     * Get basic client info (used for logs or debugging).
     */
    public static function clientInfo()
    {
        return [
            'HTTP_USER_AGENT'   => $_SERVER['HTTP_USER_AGENT'],
            'REMOTE_ADDR'       => $_SERVER['REMOTE_ADDR'],
            'SERVER_SIGNATURE'  => $_SERVER['SERVER_SIGNATURE']
        ];
    }

    /**
     * Handle secure file upload from a given input field.
     *
     * @param string $field The name of the input field.
     * @param string $path  Directory path where the file will be saved.
     * @return array        File upload result and filename if successful.
     */
    public static function uploadFile($field, $path)
    {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === 0 && $_FILES[$field]['size'] < 50000000) {
            try {
                $extension = pathinfo($_FILES[$field]['name'], PATHINFO_EXTENSION);
                $fileName = pathinfo($_FILES[$field]['name'], PATHINFO_FILENAME);

                if (!file_exists($path)) {
                    mkdir($path, 0777, true);
                }

                $file = $fileName . '_' . rand(10000, 99999) . '.' . $extension;
                move_uploaded_file($_FILES[$field]['tmp_name'], $path . $file);

                return ['status' => true, 'file' => $file];
            } catch (\Exception $e) {
                Logger::error($e->getMessage());
            }
        }

        return ['status' => false];
    }

    /**
     * Minify the final HTML response by stripping whitespace and comments.
     *
     * @param string $code HTML content
     * @return string      Minified HTML
     */
    public static function compressHTML($code)
    {
        $search = [
            '/\>[^\S ]+/s',        // whitespace after tags
            '/[^\S ]+\</s',        // whitespace before tags
            '/(\s)+/s',            // multiple spaces
            '/<!--(.|\s)*?-->/'    // HTML comments
        ];

        $replace = ['>', '<', '\\1'];
        return preg_replace($search, $replace, $code);
    }
}
