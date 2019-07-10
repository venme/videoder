<?php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
	<link rel="stylesheet" href="index.css">
	<title>
		Form Builder
	</title>
</head>
<body>
  <h1>Form Builder</h1>
<?php
  if(isset($_SESSION['NAME']))
  {
  header("Location:profile.php?login=success");
  exit();
  }
  else
  {
    require 'startup.php'; 
  }
?>
</body>
</html>