<?php
session_start();
$name=$_SESSION['NAME'];
include 'includes/db.php';
$type="wat";
$list=array();
$sql="SELECT id FROM personal WHERE name=? AND type=?";
$stmt=mysqli_stmt_init($conn);
if(!mysqli_stmt_prepare($stmt,$sql))
{
	echo "problem";
}
mysqli_stmt_bind_param($stmt,"ss",$name,$type);
mysqli_stmt_execute($stmt);
mysqli_stmt_bind_result($stmt,$id);
while(mysqli_stmt_fetch($stmt))
{
  array_push($list, $id);
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Movie Logs</title>
	<link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body class="profile">
	<div class="navbar">
    <ul>
      <li><a href="profile.php">Home</a></li>
      <li><a href="fav.php">Favourites</a></li>
      <li><a href="wat.php">Watchlist</a></li>
      <li>Contact
          <ul class="sublist">
          </ul>
           <span class="arrow">&#9660;</span>
      </li>
    <li><form action="includes/logout.php" method="post">
    <button type="submit" name="logout">Logout</button> 
</form></li>
    </ul>
  </div>
	<div id="wat"></div>
<script>
	var list = <?php echo json_encode($list); ?>;
	var i=0;
	var output='';
	console.log(list.length);
	while(i<list.length)
	{
	  console.log(list[i]);
	  var xhr= new XMLHttpRequest();
      xhr.open('GET','http://www.omdbapi.com/?i='+list[i]+'&apikey=59a7fd23',true);
      xhr.onload=function()
      {
	    console.log('In process');
	    if(this.status == 200)
	    {	
		var content=JSON.parse(this.responseText);
		 console.log(content.Title);
		 output='<div class="container"><img src="'+content.Poster+'" class="image" height="200" width="200"><div class="overlay"><div class="content">'+content.Title+'<button type="submit" value="'+content.imdbID+'" onclick="more(this.value)">More</button></div></div></div>';
		 document.getElementById('wat').innerHTML+=output;
	    }
	    else
	    {
	    	console.log('Not ready');
	    }
      }
    xhr.send();
	i=i+1;
	}
	function more(val){
 var title='';
 var moreid=val;
 window.location.replace("more.php?id="+moreid);
}
</script>
</body>
</html>