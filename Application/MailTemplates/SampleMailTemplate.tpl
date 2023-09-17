<!-- 
  =============================== 
  HOW TEMPLATE WORKS
  ===============================

  DESCRIPTION:
  This is an HTML email template used for sending new contact form inquiries.

  HOW IT WORKS:
  - Placeholders are wrapped in double curly braces (e.g., {{name}}, {{email}}).
  - These placeholders will be replaced with actual data using server-side code.
  - Common in PHP, Node.js, Python, etc., via string replacement or templating engine.

  USAGE:
  1. Store this template as a .tpl file or in a string variable.
  2. Prepare an associative array or object with key-value pairs (e.g., "name" => "Haresh").
  3. Replace all placeholders with actual values in your backend code.
  4. Send it via an email library like PHPMailer, Nodemailer, or SMTP.

  NOTES:
  - Use inline CSS for maximum compatibility with email clients.
  - Ensure content type is set to text/html when sending.
  - Always sanitize user input before inserting into the template.
-->

<div>
	<table border="1" cellspacing="0" cellpadding="8" style="width:100%; border-collapse: collapse; font-family: Arial, sans-serif;">
		<thead>
			<tr>
				<th colspan="2" style="background-color: #f2f2f2; text-align: left; font-size: 18px;">New Contact Inquiry Received</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align: left;">Name</th>
				<td>{{name}}</td>
			</tr>
			<tr>
				<th style="text-align: left;">Email</th>
				<td>{{email}}</td>
			</tr>
			<tr>
				<th style="text-align: left;">Category</th>
				<td>{{category}}</td>
			</tr>
			<tr>
				<th style="text-align: left;">Phone Number</th>
				<td>{{phone_number}}</td>
			</tr>
			<tr>
				<th style="text-align: left;">Message</th>
				<td>{{message}}</td>
			</tr>
		</tbody>
	</table>
</div>