<?php
require 'includes/db.php';?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Form Builder</title>
	<link rel="stylesheet" href="style1.css">
</head>
<body id="start">
<div id="logindiv">
<nav>
<form action="includes/login.php" method="post">
	<p>Already a User?</p>
    <input type="text" name="lname" placeholder="username"/>
    <input type="password" name="lpwd" placeholder="password"/>
    <button type="submit" name="login">Login</button> 
</form>
</nav>
</div>
<?php
if(isset($_GET['error']))
  	{
  		if($_GET['error'] == "sempty")
  		{
  			echo "<h2>Fill all the fields</h2>";
  		}
  		if($_GET['error'] == "smismatch")
  		{
  			echo "<h2>Password Mismatch</h2>";
  		}
  		if($_GET['error'] == "sdberror")
  		{
  			echo "<h2>Retry error:404</h2>";
  		}
  		if($_GET['error'] == "susralrdy")
  		{
  			echo "<h2>Try different username</h2>";
  		}
  		if($_GET['error'] == "lempty")
  		{
  			echo "<h2>Fill all the fields</h2>";
  		}
  		if($_GET['error'] == "lmismatch")
  		{
  			echo "<h2>Password Mismatch</h2>";
  		}
  		if($_GET['error'] == "ldberror")
  		{
  			echo "<h2>Retry error:404</h2>";
  		}
  		if($_GET['error'] == "lnousr")
  		{
  			echo "<h2>Username does not exist</h2>";
  		}

  	}
?>
<div id="signupdiv">
<p>First Time?</p>
<form action="includes/signup.php" method="post">
    <input type="text" name="sname" placeholder="username"/>
    <input type="password" name="spwd" placeholder="password"/>
    <input type="password" name="spwdc" placeholder="re-type password"/>
    <br>
    <button type="submit" name="signup">Signup</button> 
</form>
</div>
</body>
</html>