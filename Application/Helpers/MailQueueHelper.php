<?php
use PHPMailer\PHPMailer\PHPMailer;
use Core\Database;
use Core\Config;
use Core\Logger;

/**
 * ============================================================================
 * Class: MailQueueHelper
 * ----------------------------------------------------------------------------
 * Purpose:
 * - A custom helper class for managing email sending via SMTP.
 * - Provides functionality to queue and send emails using PHPMailer.
 *
 * 🔧 How to Load This Helper:
 * -------------------------------------------------------------
 * You can load this helper dynamically wherever required using:
 *
 *     \Core\Application::loadHelper('mail-queue');
 *
 * This will load `MailQueueHelper` from `/Helpers/MailQueueHelper.php`
 * (as long as the file and class follow naming conventions).
 *
 * Usage Example:
 * -------------------------------------------------------------
 *     MailQueueHelper::send([
 *         'mail_from'      => 'sender@example.com',
 *         'mail_from_name' => 'Sender Name',
 *         'mail_to'        => 'recipient@example.com',
 *         'mail_to_name'   => 'Recipient Name',
 *         'subject'        => 'Test Email',
 *         'html_body'      => '<h1>Hello World</h1>',
 *     ]);
 *
 *     OR to queue mail for background processing:
 *     MailQueueHelper::addToQueue([...]);
 * ============================================================================
 */
class MailQueueHelper
{
    /**
     * Instance of PHPMailer used for sending mail
     *
     * @var PHPMailer|null
     */
    public static $mail;

    /**
     * Add email data to mail queue (e.g., DB table or file queue).
     *
     * @param array $options  Email data (to/from/subject/body/etc.)
     * @return bool           True if added successfully
     */
    public static function addToQueue($options)
    {
        /**
         * Your implementation for storing email in queue (e.g., database).
         * This could include status tracking, priority, retry logic, etc.
         */
        return true;
    }

    /**
     * Send a single email via SMTP using PHPMailer.
     *
     * @param array $mailData Required keys:
     *   - mail_from
     *   - mail_from_name
     *   - mail_to
     *   - mail_to_name
     *   - subject
     *   - html_body
     *
     * @return true|string True on success, or error message on failure
     */
    public static function send($mailData)
    {
        // Connect to SMTP server (if not already connected)
        self::connectSMTP();

        // Clear previous recipients
        self::$mail->clearAddresses();

        // Enable HTML mail
        self::$mail->isHTML(true);

        /**
         * Set the sender details
         */
        self::$mail->setFrom($mailData['mail_from'], $mailData['mail_from_name']);

        /**
         * Add recipient
         */
        self::$mail->addAddress($mailData['mail_to'], $mailData['mail_to_name']);

        /**
         * Set subject and body
         */
        self::$mail->Subject = $mailData['subject'];
        self::$mail->Body = $mailData['html_body'];

        /**
         * Attempt to send the email
         */
        try {
            if (self::$mail->send()) {
                return true;
            } else {
                return self::$mail->ErrorInfo;
            }
        } catch (\Exception $e) {
            // Log error and return the message
            Logger::debug($e->getMessage());
            return $e->getMessage();
        }
    }

    /**
     * Establishes SMTP connection and configures PHPMailer.
     * Called once per request/lifecycle.
     */
    public static function connectSMTP()
    {
        if (self::$mail === null) {

            /**
             * Load SMTP credentials from config
             */
            $config = Config::get('smtp');

            // Create PHPMailer instance
            self::$mail = new PHPMailer();

            // Configure SMTP settings
            self::$mail->isSMTP();
            self::$mail->Host       = $config['host'];
            self::$mail->SMTPAuth   = $config['smtpauth'];
            self::$mail->Username   = $config['username'];
            self::$mail->Password   = $config['password'];
            self::$mail->SMTPSecure = $config['smtpsecure'];
            self::$mail->Port       = $config['port'];

            // Optional: Set timeout or additional parameters
            // self::$mail->Timeout = 30;
        }
    }
}
