<?php
session_start( );

if (empty($_SESSION['usr_id'])){
   header( 'Location: ./login.php');
}

$ID=$_SESSION['usr_id'];

require_once('config.php');

$connection = mysql_connect($db_host, $db_username, $db_password);
if (!$connection){
   die('Could not connect: ' . mysql_error());
}
mysql_select_db($db_database, $connection);

//$query = "SELECT * FROM infos WHERE infos.field='class' LIMIT 1";
//$CLASS = mysql_fetch_array(mysql_query($query));
//$query = "SELECT * FROM infos WHERE infos.field='session' LIMIT 1";
//$SESSION = mysql_fetch_array(mysql_query($query));

$PW = mysql_fetch_array(mysql_query("SELECT DISTINCT password FROM users WHERE usr_id = 'admin'"));


if (isset($_POST['ADD'])){
   if (strcmp(MD5($_POST['PWD']),$PW['password'])!=0){
       echo "Your admin passwd is not correct! Please type it again.\n";
}  else{
   //mysql_query("INSERT INTO users VALUES ('". $_POST['usr_id'] ."', '" . $_POST['NAME'] ."', '', '', '', '', '', '', '', '" . date('Y-m-d H:i:s',time()) . "')");
   $password = substr(MD5($_POST['usr_id']. time()),-8);
   echo "Account Generated: ". $_POST['usr_id'] . " Password: " . $password;
   mysql_query("INSERT INTO users VALUES ('". $_POST['usr_id'] ."', '" .MD5($password)."')");

}
}

if (isset($_POST['DEL'])){
   if (strcmp(MD5($_POST['PWD']),$PW['password'])!=0){
       echo "Your admin passwd is not correct! Please type it again.\n";
}  else{
   mysql_query("DELETE FROM users WHERE usr_id='". $_POST['usr_id']."'");
}
}


?>


<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
<link rel="stylesheet" type="text/css" href="stylesheet.css" />
</head>
<body>
<div id="content">

<h1 class="title"></h1>

<p>You have successfully logged in as <?php echo $ID; ?>! </p>




<div id="outline-container-1" class="outline-2">
<!-- <h2><?php echo $CLASS['value']; ?> Go Abroad(<?php echo $SESSION['value']; ?>)</h2> -->
<h3>User Add</h3>
<div class="box">
<form name="personal" action="<?php echo(htmlentities($_SERVER['PHP_SELF'])); ?>" method="POST">
 LOGIN USERNAME : <input size=10 type="text" name="usr_id" value="" maxlength=10/>
 USER EMAIL : <input size=30 type="text" name="EMAIL" onfocus="this.value=''" value=" "  maxlength=30/>
 ADMIN PASSWD: <input size=10 type="password" name="PWD" value="" maxlength=10/>
<input type="submit" name="ADD" value="Add User" /><br/>
After you press the above button, a password(8 characters) will be auto-generated for the user. As admin, you should inform the user his account(username, password) and let him/her login to update his application information and change his/her password.
</form>
</div>
<h3>User List</h3>

<div class="table">
<table border="1px" width="775">
  <tr>
    <th>usr_id</th>
    <th>Manage</th>
  </tr>
  

  <?php

  $result=mysql_query("SELECT DISTINCT usr_id FROM users");
  while ($row=mysql_fetch_array($result)){
  echo "<form action='".htmlentities($_SERVER['PHP_SELF'])."' method='POST'>";

  echo "<tr>";     
  echo "<td>";
  echo $row['usr_id'];
  echo "</td>";  

  echo "<td><input type='submit' name='DEL' value='Delete'/> ADMIN PASSWD: <input size=10 type='password' name='PWD' value='' /></td>";
  echo "<input type='hidden' name='usr_id' value='".$row['usr_id']."'/>";

  echo "<tr>";

  echo "</form>\n";
  }
  ?>
</table>
</div>

</div>
</div>
</body>
</html>

