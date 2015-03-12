<?php
session_start( );

require_once('config.php');

if (empty($_SESSION['usr_id'])) {

if (isset($_POST['login'])){
$connection = mysql_connect($db_host, $db_username, $db_password);
if (!$connection) {
	die ("Could not connect to the database: <br />". mysql_error());
}
$db_select=mysql_select_db($db_database);
if (!$db_select){
	die ("Could not select the database: <br />". mysql_error());
}

$username=$_POST['username'];
$password=$_POST['password'];
$query = "SELECT usr_id FROM users WHERE usr_id='" . $username . "' AND password=MD5('" . $password . "') LIMIT 1";


$result = mysql_query($query);

if(!($row = mysql_fetch_array($result))) {
    header("HTTP/1.0 401 Unauthorized");
    echo "Your username and password combination was incorrect!";
    exit;
}
else{
$_SESSION['usr_id'] = $row['usr_id'];

if (empty($_SESSION['task_id'])) {
	$_SESSION['task_id']=1;
}

header( 'Location: ./manage.php' ) ;
}

}
}
else{
header( 'Location: ./manage.php' ) ;
}





?>


<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link rel="shortcut icon" href="./pic/favicon.ico" type="image/x-png"/>
<title>Welcome</title> 
</head> 
 
<body> 
<div align="center" style="position:absolute; width:544px; height:162px; left:50%; top:40%; margin-left:-272px; margin-top:-81px;"> 
<form action="<?php echo(htmlentities($_SERVER['PHP_SELF'])); ?>" method="POST">
<table>
<tr><td>Username:</td><td><input type='text' name='username' /></td></tr>
<tr><td>Password:</td><td><input type='password' name='password' /></td></tr>
</table>
<input type='submit' name='login' value='Login' />
</form>
</div> 
</body> 
 
</html>



