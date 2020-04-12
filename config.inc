<?

include_once "markdown.php";

$title_faq = "Quickies";
$footer_text = "<a href=\"https://github.com/nst/Quickies\">Quickies</a> - © 2011 seriot.ch - <a href=\"http://seriot.ch/contact.php\">contact</a>";

$srv_host = "localhost";
$srv_user = "quickies_public";
$srv_pass = "";
$srv_db = "quickies";

$con = mysqli_connect($srv_host, $srv_user, $srv_pass, $srv_db) or die("Invalid server or user". mysql_error());

if (mysqli_connect_errno($con))
{
    echo "Failed to connect to MySQL:" . mysqli_connect_error();
}

$GLOBALS["con"] = $con;

?>
