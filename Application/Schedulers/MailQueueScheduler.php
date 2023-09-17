
<?php
use Core\Scheduler;
use Core\Database;
use Core\Application;
use Core\Logger;

/**
 * ============================================================================
 * SCHEDULER: MailQueueScheduler
 * ----------------------------------------------------------------------------
 * File Path: /Application/Schedulers/MailQueueScheduler.php
 * Extends   : Core\Scheduler
 *
 * Purpose:
 * - This is a custom scheduler class used for managing queued emails.
 * - It is designed to be executed from the command line using the scheduler utility.
 *
 * How to Run via CLI:
 * --------------------------------------------------------------------------
 * php run.php scheduler mail-queue          → executes the default `execute()` method
 * php run.php scheduler mail-queue:send     → executes the `send()` method
 *
 * defined Methods:
 * - execute() → Default method, can be used for summaries, test logs, etc.
 * - send()    → Place logic here to fetch queued emails and send using MailQueueHelper
 *
 * Useful Classes You Can Use:
 * - Database::get()         → To interact with the DB
 * - Logger::debug()         → For logging activity
 * - Application::loadHelper('mail-queue') → Load the mail sending helper
 *
 * Security Note:
 * - Make sure this script is only executed via CLI and not exposed to the web.
 *
 * ============================================================================
 */

class MailQueueScheduler extends Scheduler
{
    /**
     * Constructor
     * - Calls the parent constructor.
     * - Extend here if you need to initialize DB, configs, etc.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Default method called when:
     * php run.php scheduler mail-queue
     *
     * Useful for testing, summary logs, or fallback logic.
     */
    public function execute()
    {
        // Example: Log entry showing that scheduler is working
        Logger::debug("MailQueueScheduler -> execute() method called.");
    }

    /**
     * Main method to process and send queued emails.
     * 
     * Run via:
     * php run.php scheduler mail-queue:send
     */
    public function send()
    {
        /**
         * ✅ TODO: Add logic to process mail queue
         * Steps may include:
         * 1. Load the mail queue helper
         * 2. Fetch queued mails from database
         * 3. Loop through each mail and send it
         * 4. Mark mail as sent or log any error
         */

        // Example code stub
        Application::loadHelper('mail-queue');

        // Example: Fetch emails from DB (logic to be implemented)
        // $db = Database::get();
        // $mails = $db->query("SELECT * FROM mail_queue WHERE status = 'pending'")->fetchAll();

        // foreach ($mails as $mail) {
        //     MailQueueHelper::send($mail);
        //     // Update status in DB...
        // }

        Logger::debug("MailQueueScheduler ->send() method executed.");
    }
}