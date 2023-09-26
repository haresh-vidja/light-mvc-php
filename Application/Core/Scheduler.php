<?php
namespace Core;

/**
 * ============================================================================
 * Class: Scheduler
 * ----------------------------------------------------------------------------
 * Purpose:
 * - This is the base class for all custom scheduler (CLI command) classes.
 * - It provides a consistent structure for extending scheduled tasks.
 *
 * Usage:
 * - All schedulers should extend this class and implement their methods.
 * - File should be placed in `/Schedulers/` directory.
 * - Example usage from CLI:
 *     php Application/run.php my-task:run
 *
 * Example:
 * ```php
 * class MyTaskScheduler extends Scheduler {
 *     public function run() {
 *         // custom scheduled logic here
 *     }
 * }
 * ```
 * ============================================================================
 */
class Scheduler
{
    /**
     * Constructor
     * Can be extended by child scheduler classes to initialize dependencies.
     */
    public function __construct()
    {
        // Base constructor - extend as needed
    }
}
