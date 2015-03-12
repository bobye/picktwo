<?php

require_once('config.php');

$connection = mysql_connect($db_host, $db_username, $db_password);
if (!$connection){
   die('Could not connect: ' . mysql_error());
}


if (isset($_POST['INSTALL']))
{
	if (strcmp($_POST['adminpw'],$_POST['adminpw-2'])!=0) {
	   echo "Your admin passwd and re-entered one do not match! Please set it again.\n";
	}else{
	   $query = "CREATE DATABASE " . $db_database;  
	   mysql_query($query);
	   mysql_select_db($db_database, $connection);

	   $query = "CREATE TABLE users (usr_id VARCHAR (10), password CHAR (32), PRIMARY KEY (usr_id) )"; mysql_query($query);
	   $query = "CREATE TABLE images (img_id INT, artist_id INT, url VARCHAR(512), PRIMARY KEY (img_id))"; mysql_query($query);
	   $query = "LOAD DATA INFILE '/Users/bobye/Sites/picktwo/image_src.csv' INTO TABLE images FIELDS TERMINATED BY ',' ENCLOSED BY '\"' "; mysql_query($query);
	   $query = "CREATE TABLE tasks (task_id INT, image_list VARCHAR(512), artist_id INT, primary key (task_id))"; mysql_query($query);
	   $query = "LOAD DATA INFILE '/Users/bobye/Sites/picktwo/task_src.csv' INTO TABLE tasks FIELDS TERMINATED BY ',' ENCLOSED BY '\"'"; mysql_query($query);
	   $query = "CREATE TABLE events (usr_id VARCHAR(10), task_id INT, chosen VARCHAR(50), timestamp TIMESTAMP, primary key (usr_id, task_id))"; mysql_query($query);
	   //$query = "CREATE TABLE applicants (stu_id VARCHAR (10), name VARCHAR (10), rank VARCHAR (10), gre_v  VARCHAR (10), gre_q  VARCHAR (10), gre_aw  VARCHAR (10), toefl  VARCHAR (10), toefl_s  VARCHAR (10), GPA  VARCHAR (10), ts TIMESTAMP, PRIMARY KEY (stu_id) )";
	   //mysql_query($query);
	   //$query = "CREATE TABLE programs (apply_id INT (11), stu_id VARCHAR (10), tic VARCHAR (4), status  VARCHAR (10), division  VARCHAR (10), comments  VARCHAR (50), rate  VARCHAR (3), PRIMARY KEY (apply_id) )";
	   //mysql_query($query);
	   //$query = "CREATE TABLE schools (tic VARCHAR (4), name VARCHAR (50), abbr  VARCHAR (10), location  VARCHAR (50), us_rank INT(10), PRIMARY KEY (tic) )";
	   //mysql_query($query);

	   $query="INSERT INTO users VALUES ('admin', MD5('" . $_POST['adminpw'] . "')) ";
	   mysql_query($query);

	   //$query="CREATE TABLE infos (field VARCHAR (50), value VARCHAR (20), PRIMARY KEY (field))";
	   //mysql_query($query);

	   //$query="INSERT INTO infos VALUES ('class', '" . $_POST['class'] . "') ";
	   //mysql_query($query);
	   //$query="INSERT INTO infos VALUES ('session', '" . $_POST['session'] . "') ";
	   //mysql_query($query);

	   header( 'Location: ./admin.php' ) ;
 	   
	}

}


mysql_close($connection);


?>



<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" /> 
<link rel="shortcut icon" href="./pic/favicon.ico" type="image/x-png"/>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
<title>Bobye's Product</title> 
</head> 
 
<body> 
<div id="outline-container-1" class="outline-2">
<div style="width:500"> 
Before you come to this page, make sure you have configured the config.php correctly in order to access the database. <br/><br/>

Please edit following information and then press INSTALL button to install the website. Be careful when you set your PERMANENT administration password, you will use it to add/delete regular users. Please keep your password in mind, since you have NO WAY to get it back or have it changed if you forget it.
<form action="<?php echo(htmlentities($_SERVER['PHP_SELF'])); ?>" method="POST">
<table>
<tr><td>Class:</td><td><input type='text' name='class' value='USTC 07 Math'/></td></tr>
<tr><td>Session:</td><td><input type='text' name='session' value='2011 Fall'/></td></tr>
<tr><td>Admin Password:</td><td><input type='password' name='adminpw' value=''/></td></tr>
<tr><td>Re-enter Admin Password:</td><td><input type='password' name='adminpw-2' value=''/></td></tr>
</table><br/>
After you press the button below, you don't need to visit this page again. For safety, you can simply change the reading permission of this file, Or delete it.<br/>
<input type='submit' name='INSTALL' value='INSTALL Now' />
</form>
</div> 
</div>
</body> 
 
</html>


