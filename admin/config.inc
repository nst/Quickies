<?

$title_faq = "Quickies Admin";

$footer_text = "© 2011 seriot.ch";

$srv_host = "localhost";
$srv_user = "quickies_admin";
$srv_pass = "";
$srv_db = "quickies";

$con = mysqli_connect($srv_host, $srv_user, $srv_pass, $srv_db) or die("Invalid server or user". mysql_error());

if (mysqli_connect_errno($con))
{
    echo "Failed to connect to MySQL:" . mysqli_connect_error();
}

?>
