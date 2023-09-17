<?php

// Import core framework components
use Core\Controller;
use Core\Database;
use Verum\Validator;
use Core\Application;
use Core\Http;
use Core\Config;

/**
 * =========================================================================
 * InquiryController
 * =========================================================================
 * This is a custom controller that handles contact/inquiry form submission.
 * It demonstrates how to:
 * - Define a controller
 * - Handle POST form data
 * - Apply input validation rules
 * - Execute custom logic (e.g., send email or insert to DB)
 * - Redirect or render error pages
 */
class InquiryController extends Controller
{
	/**
	 * Constructor
	 * Calls the parent constructor if needed (e.g., for auth, DB setup).
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * index()
	 * This method handles the default route `/inquiry`
	 * It processes the submitted inquiry form (typically via POST).
	 */
	public function index()
	{
		// Retrieve POST data
		$inquiry = $_POST;

		/**
		 * Define validation rules for input fields
		 * Each field must satisfy the associated rules.
		 */
		$rules = [
			'name' => [
				'rules' => ['required'],
			],
			'email' => [
				'rules' => ['required', 'email'],
			],
			'phone_number' => [
				'rules' => ['required'],
			],
			'category' => [
				'rules' => ['required'],
			],
			'message' => [
				'rules' => ['required'],
			]
		];

		/**
		 * Validate the input using Verum Validator
		 */
		$validator = new Validator($_POST, $rules);

		if ($validator->validate()) {
			/**
			 * If validation passes:
			 * - Add your custom logic here
			 *   (e.g., save to database, send email notification)
			 */

			// Redirect to a thank-you page
			$this->redirect("/thank-you");

		} else {
			/**
			 * If validation fails:
			 * - You can return JSON errors, flash messages, or show a generic error page
			 * - Currently, it renders a 505 error page
			 */
			$this->render505();
		}
	}
}
