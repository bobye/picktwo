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


$ID=$_SESSION['usr_id'];
$TASK_ID=$_SESSION['task_id'];

if (isset($_SESSION['warning'])) {
	echo $_SESSION['warning'];
	unset($_SESSION['warning']);
}


// retrieve saved result if existed
$query = "SELECT * FROM events WHERE events.usr_id='$ID' AND events.task_id='$TASK_ID' LIMIT 1"; 
$result = mysql_query($query);
$numResults = mysql_num_rows($result);
$chosen=[];
if ($numResults > 0) {
	$row = mysql_fetch_array($result);
	$chosen=json_decode($row['chosen'], true);
}

// calculate how many tasks in total
$query="SELECT * FROM tasks";
$result=mysql_query($query);
$num_tasks=mysql_num_rows($result);

// retrieve the image ids and url as list in the task
$query ="SELECT * FROM tasks WHERE tasks.task_id ='$TASK_ID' LIMIT 1";
$result =mysql_query($query);
$row=mysql_fetch_array($result);
$image_list=json_decode($row['image_list'], true);
shuffle($image_list);
$num_image=sizeof($image_list);
$image_url=[];
$image_check=[];
for ($i=0; $i< $num_image; $i++) {
	$id=$image_list[$i];
	if ($id == $chosen[0] || $id == $chosen[1]) 
		$image_check[$i]='checked';
	else 
		$image_check[$i]='';
	$result =mysql_query("SELECT * FROM images WHERE images.img_id={$id} LIMIT 1");
	$row = mysql_fetch_array($result);
	$image_url[$i]=$row['url'];
}


mysql_close($connection);

?>
<html>
<head>
	<title>Which two paintings are by the same artist?</title>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
</head>
<div id="content" align="center" >
<p>You have successfully logged in as <?php echo $ID; ?>! <a href="./logout.php">Logout</a></p>
<h1>Which two paintings are by the same artist? (<?php echo "$TASK_ID / $num_tasks" ?>)</h1>
<form action='events.php' method='POST'>
<table>
	<tr>
	<?php 	
	$html = "<tr>";
	for ($i=0; $i< $num_image; $i++)  {
		$html .="<td>";
		$html .='<label for="'. $image_list[$i] . '"><img src="images/' . $image_url[$i] .'" height=400/></label>';
		$html .='<input type="checkbox" class="chk " '. $image_check[$i] . ' id="' . $image_list[$i] . '" name="image_select[]" value="'. $image_list[$i] . '" />';
		$html .="</td>";
	}
	echo $html; 
	?>
	</tr>
</table>	
	<input type='submit' name='formsubmit' value='Save' style="font-size:20px"/>
	<input type='submit' name='backward' value='Back'  style="font-size:20px"/>
	<input type='submit' name='continue' value='Continue' style="font-size:20px"/>
</form>
<p style="width:800; text-align:left;">Note: Please answer the question based on your own <i>judgements and feelings</i>. The "Continue" button automatically direct you to the most recent unfinished task. 
	You can logout and re-login to restart your tasks from the beginning. (Your past selections are recorded, so feel free to do that.)</p>
<!--<p> The task is about <?php print_r($image_url); ?>.</p> -->
</div>
</html>
