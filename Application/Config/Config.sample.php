<?php
return [

	/**
	 * SITE URL
	 * The base URL of your website.
	 * Example: "http://yoursite.com"
	 */
	"siteUrl" => "<your website url>",

	/**
	 * STATIC RESOURCE
	 * Set Full URL or Path for image, javascript, css and favicon.
	 * Example: "/images/" or "http://yoursite.com/images/" or "http://image.othersite.com/"
	 */
	"resources" => [
		"image" => '/images/',
		"js" => '/js/',
		"css" => '/css/',
		"favicon" => '/favicon/'
	],


	/**
	 * BLOG URL
	 * URL of your WordPress blog for fetching RSS feeds.
	 * Example: "http://yoursite.com/blog/"
	 */
	"blogUrl" => "<your wordpress website url>",

	/**
	 * DEBUG MODE
	 * true  - Show PHP errors/warnings on screen (for development)
	 * false - Hide errors from users (for production)
	 */
	"debug" => true,

	/**
	 * ENABLE LOGGING
	 * true  - Logs system activities and errors to log files
	 * false - No logs will be recorded
	 */
	"log" => true,

	/**
	 * HTML MINIFICATION
	 * true  - Minify HTML output (removes spaces, comments)
	 * false - Keep raw HTML
	 * *Note: Works only for GET requests*
	 */
	"minify" => true,

	/**
	 * PAGE CACHE SETTINGS
	 * Enables full page caching for GET requests.
	 * - enable: Turn caching on or off.
	 * - key: A unique string to generate versioned cache files.
	 */
	"cache" => [
		"enable" => true,
		"key" => "<any random string>" // e.g., "v1.0.0" or project-specific key
	],

	/**
	 * DATABASE CONFIGURATION
	 * Connection settings for MySQL database.
	 */
	"database" => [
		"host"     => "<database host name with port number>", // e.g., "localhost:3306"
		"user"     => "<database user name>",
		"password" => "<database password>",
		"name"     => "<database name>"
	],

	/**
	 * GOOGLE RECAPTCHA SETTINGS (v2)
	 * - enable: true to activate Recaptcha on forms
	 * - key: your site key from Google Recaptcha
	 */
	"gcaptcha" => [
		"enable" => true,
		"key"    => "<google recaptcha key for v2>"
	],

	/**
	 * GOOGLE SITE VERIFICATION
	 * For search console ownership verification.
	 * - key: Meta tag content string provided by Google.
	 */
	"googeSiteVerification" => [
		"enable" => true,
		"key"    => "<verification key>"
	],

	/**
	 * GOOGLE TAG MANAGER
	 * - enable: Include GTM script if true.
	 * - key: GTM container ID.
	 */
	"googleTagManager" => [
		"enable" => true,
		"key"    => "<tag manager key>"
	],

	/**
	 * GOOGLE ANALYTICS
	 * - enable: Enable tracking on the site.
	 * - key: Your GA tracking ID (e.g., "UA-XXXXXXXXX-X")
	 */
	"ganalytics" => [
		"enable" => false,
		"key"    => "<google analytics key here>"
	],

	/**
	 * SMTP EMAIL SETTINGS
	 * Used to send system emails (contact, career, quote, etc.).
	 */
	"smtp" => [
		"host"       => "<smtp host name>",
		"smtpauth"   => true, // SMTP authentication: true or false
		"username"   => "<smtp username>",
		"password"   => "<smtp password>",
		"smtpsecure" => "<tls or ssl>", // Encryption type
		"port"       => "<port number>",

		// From email details
		"from" => [
			"name"  => "<mail from name>",
			"email" => "<mail from email>"
		]
	],

	/**
	 * NOTIFICATION EMAIL RECEIVERS
	 * Define recipients for different form submissions.
	 */
	"mailReceiver" => [

		// Recipients for contact form submissions
		"contact" => [
			["name" => "<name>", "email" => "<email id>"]
		],

		// Recipients for career form submissions
		"career" => [
			["name" => "<name>", "email" => "<email id>"]
		],

		// Recipients for quote request form submissions
		"quote" => [
			["name" => "<name>", "email" => "<email id>"]
		]
	]
];
