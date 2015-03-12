<?php
session_start( );
if (empty($_SESSION['usr_id'])){
   header( 'Location: ./login.php');
}

require_once('config.php');

$connection = mysql_connect($db_host, $db_username, $db_password);
if (!$connection){
   die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_database, $connection);


if (isset($_POST['formsubmit'])) {
$selects = $_POST['image_select'];
$Nsel = count($selects);
if ($Nsel != 2 || empty($selects)) {
	$_SESSION['warning'] = "Warning: you must select two paintings before proceeding to next task.";
} else {
	$chosen="[" . $selects[0] . "," . $selects[1] . "]";
	$query="INSERT INTO events (usr_id, task_id, chosen, timestamp) VALUES ('" . 
		$_SESSION['usr_id']. "', ". $_SESSION['task_id'] . ", '$chosen', now()) 
		ON duplicate key update chosen=VALUES(chosen), timestamp=VALUES(timestamp)";
	$result = mysql_query($query);
	$_SESSION['task_id']=$_SESSION['task_id'] + 1;
}
}
if (isset($_POST['backward'])) {
	if ($_SESSION['task_id'] > 1) 
		$_SESSION['task_id']=$_SESSION['task_id'] - 1;
}

if (isset($_POST['continue'])) {
	while (true) {
		$query="SELECT * FROM events WHERE events.usr_id='". $_SESSION['usr_id'] . "' AND events.task_id=". $_SESSION['task_id'] . " LIMIT 1";
		$result=mysql_query($query);
		$num_rows = mysql_num_rows($result);
		if ($num_rows == 0)
			break;
		$_SESSION['task_id']=$_SESSION['task_id'] + 1;
	}
	
}
mysql_close($connection);
header('Location: ./manage.php');
?>