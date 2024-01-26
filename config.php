<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<?php 
/* 
 * PayPal and database configuration 
 */ 
  
// PayPal configuration
define('PAYPAL_ID', 'sb-xr3nd26154072@business.example.com');
define('PAYPAL_SANDBOX', TRUE); // TRUE or FALSE
define('PAYPAL_RETURN_URL', 'success.php');
define('PAYPAL_CANCEL_URL', 'cancel.php');
define('PAYPAL_CURRENCY', 'USD');

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'umskalfy1_pawadmin');
define('DB_PASSWORD', 'Password@0101');
define('DB_NAME', 'umskalfy1_pawshelter');

// Change not required
define('PAYPAL_URL', (PAYPAL_SANDBOX == true) ? "https://www.sandbox.paypal.com/cgi-bin/webscr" : "https://www.paypal.com/cgi-bin/webscr");
?>
