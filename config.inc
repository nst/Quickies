<?

include_once "markdown.php";

$title_faq = "Quickies";
$footer_text = "<a href=\"https://github.com/nst/Quickies\">Quickies</a> - © 2011 seriot.ch - <a href=\"http://seriot.ch/contact.php\">contact</a>";

$srv_host = "localhost";
$srv_user = "quickies_public";
$srv_pass = "";
$srv_db = "quickies";

$connection = mysql_connect($srv_host, $srv_user, $srv_pass) or die("Invalid server or user". mysql_error());
mysql_select_db($srv_db, $connection) or die ("SQL-error: ". mysql_error());

?>
