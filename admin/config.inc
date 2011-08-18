<?

$title_faq = "Quickies Admin";

$footer_text = "© 2011 seriot.ch";

$srv_host = "localhost";
$srv_user = "quickies_admin";
$srv_pass = "";
$srv_db = "quickies";

$connection = mysql_connect($srv_host, $srv_user, $srv_pass) or die("Invalid server or user". mysql_error());
mysql_select_db($srv_db, $connection) or die ("SQL-error: ". mysql_error());

?>
