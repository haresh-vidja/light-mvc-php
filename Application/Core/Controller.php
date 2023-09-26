<?php
namespace Core;

/**
 * ============================================================================
 * Class: Controller
 * ----------------------------------------------------------------------------
 * Purpose:
 * - Serves as the base class for all controllers in the application.
 * - Provides common utility methods such as redirects, error handling,
 *   JSON responses, and request type checks.
 * ============================================================================
 */
class Controller
{
    /**
     * Constructor
     * - Can be extended by child controllers.
     */
    public function __construct()
    {
        // Default constructor (extendable)
    }

    /**
     * Check if the current HTTP request is a POST request.
     *
     * @return bool
     */
    protected function isPostRequest()
    {
        return !empty($_POST);
    }

    /**
     * Send a structured JSON response with an error code and message.
     *
     * @param string $message    The error or status message.
     * @param int    $errorcode  HTTP-like error code (e.g., 403, 500).
     * @param string $type       Custom response type (e.g., 'error', 'warning').
     */
    protected function response($message, $errorcode, $type)
    {
        // Clean any previously buffered output
        if (ob_get_length() > 0) {
            ob_clean();
        }

        // Prepare response structure
        $response = [
            'code' => $errorcode,
            'message' => $message,
            'type' => $type
        ];

        // Return JSON and stop execution
        echo json_encode($response);
        exit;
    }

    /**
     * Redirect to a specified URL.
     *
     * @param string $location  URL or relative path to redirect to.
     */
    protected function redirect($location)
    {
        if (ob_get_length() > 0) {
            ob_clean();
        }
        header("Location: " . $location);
        die();
    }

    /**
     * Redirect to a 505 (server error) page.
     */
    protected function render505()
    {
        if (ob_get_length() > 0) {
            ob_clean();
        }
        header("Location: /505");
        die();
    }

    /**
     * Render a 404 error page.
     */
    protected function render404()
    {
        // Include the 404 error view file
        require_once(PAGE_DIR . "404.php");

        // Optional: Minify HTML if enabled
        $content = (Config::get("minify") === true)
            ? Http::compressHTML(ob_get_contents())
            : ob_get_contents();

        // Note: content is captured but not echoed here
        // echo $content; ← can be added if needed
    }

    /**
     * Output a JSON response and end execution.
     *
     * @param array $array  Data array to be returned as JSON.
     */
    protected function jsonResponse($array)
    {
        // Clean buffer only in production mode (no debug output)
        if (Config::get('debug') === false) {
            if (ob_get_length() > 0) {
                ob_clean();
            }
        }

        echo json_encode($array);
        exit;
    }

    /**
     * Debugging utility to print array/object in `<pre>` block.
     *
     * @param mixed $var  Any variable or array to be printed.
     */
    protected function pre($var)
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
}
