<?php
session_start();
include 'includes/db.php';
if(isset($_SESSION['redirect']))
{
 header("Location:".$_SESSION['redirect']);
 exit();
}
if(!isset($_SESSION['NAME']))
{
   header("Location:index.php?error=wrngaccess");
   exit();  
}
if(isset($_POST['username']))
{
  $user=mysqli_real_escape_string($conn,$_POST['username']);
  $name=$_SESSION['NAME'];
  $personal=array();
  $sql="SELECT T1.* FROM personal T1, users T2 WHERE T1.name='".$user."' AND T2.name='".$user."' AND T2.mode='public' ORDER BY T1.type";
  $rows=$conn->query($sql);
  if($rows->num_rows>0)
  {
    while($row=$rows->fetch_assoc())
    {
      array_push($personal,$row);
    }
  }
}
if(isset($_POST['favid']))
{
    $stmt=mysqli_stmt_init($conn);
	$favid=$_POST['favid'];
	$name=$_SESSION['NAME'];
	$type='fav';
  $sql="SELECT * FROM personal WHERE id='".$favid."' AND type='fav' AND name='".$name."'";
  $rows=$conn->query($sql);
  if($rows->num_rows==0)
  {
    $sql="INSERT INTO personal (name,type,id) VALUES (?,?,?)";
    if(!mysqli_stmt_prepare($stmt,$sql))
    {
	  echo "Error";
    }
    mysqli_stmt_bind_param($stmt,"sss",$name,$type,$favid);
    mysqli_stmt_execute($stmt);
  }
}
if(isset($_POST['watid']))
{
    $stmt=mysqli_stmt_init($conn);
	$watid=$_POST['watid'];
	$name=$_SESSION['NAME'];
	$type='wat';
	$sql="SELECT * FROM personal WHERE id='".$watid."' AND type='wat' AND name='".$name."'";
  $rows=$conn->query($sql);
  if($rows->num_rows==0)
  {
    $sql="INSERT INTO personal (name,type,id) VALUES (?,?,?)";
    if(!mysqli_stmt_prepare($stmt,$sql))
    {
	  echo "Error";
    }
    mysqli_stmt_bind_param($stmt,"sss",$name,$type,$watid);
    mysqli_stmt_execute($stmt);
  }
}
if(isset($_POST['like']))
{
  $stmt=mysqli_stmt_init($conn);
  $likeid=$_POST['like'];
  $name=$_SESSION['NAME'];
  $type='like';
  $sql="SELECT * FROM personal WHERE id='".$likeid."' AND type='like' AND name='".$name."'";
  if(!$conn->query($sql))
  {
    header("Location:error.php");
  }
  $rows=$conn->query($sql);
  if($rows->num_rows==0)
  {
    $sql="INSERT INTO personal (name,type,id) VALUES (?,?,?)";
    if(!mysqli_stmt_prepare($stmt,$sql))
    {
    header("Location:error.php");
    }
    mysqli_stmt_bind_param($stmt,"sss",$name,$type,$likeid);
    mysqli_stmt_execute($stmt);
    $sql="INSERT IGNORE INTO likes (id) VALUES ('".$likeid."')";
    $conn->query($sql);
    $sql="UPDATE likes SET likes=likes+1 WHERE id='".$likeid."'";
    $conn->query($sql);
  }
  else
  {
    $sql="DELETE FROM personal WHERE id='".$likeid."' AND type='like' AND name='".$name."'";
    $conn->query($sql);
    $sql="UPDATE likes SET likes=likes-1 WHERE id='".$likeid."'";
    $conn->query($sql);
  }
}
if(isset($_POST['commentid']))
{
  $stmt=mysqli_stmt_init($conn);
  $commentid=$_POST['commentid'];
  $comment=$_POST['comment'];
  $name=$_SESSION['NAME'];
  $sql="INSERT INTO comments (name,comment,id) VALUES (?,?,?)";
  if(!mysqli_stmt_prepare($stmt,$sql))
  {
   header("Location:error.php");
  }
  mysqli_stmt_bind_param($stmt,"sss",$name,$comment,$commentid);
  mysqli_stmt_execute($stmt);
}
if(isset($_POST['mode']))
{
 $mode=$_POST['mode'];
 $name=$_SESSION['NAME'];
 $sql="UPDATE users SET mode='".$mode."' WHERE name='".$name."'";
 if(!$conn->query($sql))
 {
  echo "ERROR ERROR ERROR";
 }
}
$sql="SELECT * FROM users WHERE name='".$name."'";
if(!$conn->query($sql))
 {
  echo "ERROR ERROR ERROR";
 }
 $rows=$conn->query($sql);
 $row=$rows->fetch_assoc();
 $state=$row['mode'];
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
  <?php
if(isset($_GET['error']))
    {
      if($_GET['error'] == "empty")
      {
        echo "<h2>Fill all the fields</h2>";
      }
      if($_GET['error'] == "noentry")
      {
        echo "<h2>Invalid Entry</h2>";
      }
      if($_GET['error'] == "dberror")
      {
        echo "<h2>try again after sometime</h2>";
      }
      if($_GET['error'] == "nametaken")
      {
        echo "<h2>Try Different Name</h2>";
      }
      if($_GET['error']=="wrngaccess")
      {
        echo "<h2>Access Denied</h2>";
      }
      if($_GET['error']=="another")
      {
        echo "<h2>Finish Launching current form</h2>";
      }
    }
  ?>
  <br>
  <?php 
  include 'includes/db.php';
  $name=$_SESSION['NAME'];
$sql="SELECT * FROM users WHERE name='".$name."'";
if(!$conn->query($sql))
 {
  echo "ERROR ERROR ERROR";
 }
 $rows=$conn->query($sql);
 $row=$rows->fetch_assoc();
 $state=$row['mode'];
  echo '<div id="modediv">
    <label>Go_Public</label>
  <label class="switch">';
  if($state=='public')
    echo '<input type="checkbox" id="mode" name="mode" value="public" checked>';
  else  
    echo  '<input type="checkbox" id="mode" name="mode" value="public">';
  echo '<span class="slider round"></span></label></div>';
?>
  <br>
  <form action="profile.php" method="post">
     <label for="Mname">Search Public Users</label>
    <input type="text" name="username" placeholder="Username" required>
    <button type="submit">Search</button>
  </form>
  <br>
<div id="searchview"></div>
<form id="searchform">
  <label for="Mname">Search movie by title or id</label>
  <input type="text" name="mname" id="mname" placeholder="Movie_imdbID" onkeyup="searchmo()"/>
  <input type="text" name="Mname" id="Mname" placeholder="Movie_Title" onkeyup="searchmov()"/>
  <button type="submit" name="search" id="Msearch" onclick="searchmov(),searchmo()">Search</button>
</form>
<script>
  var personal=<?php echo json_encode($personal);?>;
  var i=0
  if(personal.length >0)
  {
    var output="";
    document.getElementById('searchview').innerHTML="<h1>Your Friend's movie list</h1>";
   while(i<personal.length)
    {
     var xhr = new XMLHttpRequest();
     xhr.open('GET','http://www.omdbapi.com/?i='+personal[i].id+'&apikey=59a7fd23',true);
     xhr.onload=function(){
      var cont=JSON.parse(this.responseText);
      console.log(cont.Poster+" THIS MOVIE");
      output='<div class="container"><img src="'+cont.Poster+'" class="image" height="300" width="250"><div class="overlay"><div class="content">'+cont.Title+'<button type="submit" value="'+cont.imdbID+'" onclick="fav(this.value)">Favourites</button><button type="submit" value="'+cont.imdbID+'" onclick="watchlist(this.value)">Watchlist</button><button type="submit" value="'+cont.imdbID+'" onclick="more(this.value)">More</button></div></div></div>';
       document.getElementById('searchview').innerHTML+=output;
     }
     xhr.send();
     i=i+1;
    }
  }
</script>
<div id="searchdiv"></div>
<div id="detailedview"></div>
<br>
<div id="view">
</div>
 <br>
 <?php
 
 ?>
 <script src="https://apis.google.com/js/api.js"></script>
<script>
var checkbox = document.querySelector("input[name=mode]");
var mode='';
checkbox.addEventListener('change',function(){
  var xhr = new XMLHttpRequest();
  if(this.checked){
  mode="public";
  var param='mode='+mode;
  var xhr =new XMLHttpRequest();
  xhr.open('POST','profile.php',true);
  xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  xhr.send(param);
  }
  else
  {
  mode="private";
  var param='mode='+mode;
  var xhr =new XMLHttpRequest();
  xhr.open('POST','profile.php',true);
  xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
  xhr.send(param);
  }
});
function searchmo(){
  var mid="";
  mid=document.getElementById('mname').value;
  if(mid!=""){
  xhr.open('GET','http://www.omdbapi.com/?i='+mid+'&apikey=59a7fd23',true);
  xhr.onload=function(){
 if(this.status == 200)
  { 
    var content=JSON.parse(this.responseText);
    var i=0;
    var output='';
    if(content.Response == "True")
    {
    console.log(content);
    output+='<div class="container"><img src="'+content.Poster+'" class="image" height="300" width="250"><div class="overlay"><div class="content">'+content.Title+'<button type="submit" value="'+content.imdbID+'" onclick="fav(this.value)">Favourites</button><button type="submit" value="'+content.imdbID+'" onclick="watchlist(this.value)">Watchlist</button><button type="submit" value="'+content.imdbID+'" onclick="more(this.value)">More</button></div></div></div>';
      document.getElementById('searchdiv').innerHTML=output;
    }
  }
  else
  {
    console.log('Not ready');
  }
 }
 xhr.send();
}
}
function searchmov(){
var xhr = new XMLHttpRequest();
var name="";
name=document.getElementById('Mname').value;
if(name!=""){
xhr.open('GET','http://www.omdbapi.com/?s='+name+'&apikey=59a7fd23',true);
xhr.onload=function(){
 if(this.status == 200)
  { 
    var content=JSON.parse(this.responseText);
    var i=0;
    var output='';
    if(content.Response == "True")
    {
    console.log(content.Search.length);
    for(a in content.Search)
    {
    console.log(content.Search[a].Title);
    output+='<div class="container"><img src="'+content.Search[a].Poster+'" class="image" height="300" width="250"><div class="overlay"><div class="content">'+content.Search[a].Title+'<button type="submit" value="'+content.Search[a].imdbID+'" onclick="fav(this.value)">Favourites</button><button type="submit" value="'+content.Search[a].imdbID+'" onclick="watchlist(this.value)">Watchlist</button><button type="submit" value="'+content.Search[a].imdbID+'" onclick="more(this.value)">More</button></div></div></div>';
    }
      document.getElementById('searchdiv').innerHTML=output;
    }
  }
  else
  {
    console.log('Not ready');
  }
 }
 xhr.send();
}
}
var xhr = new XMLHttpRequest();
xhr.open('GET','initial.json',true);
xhr.onload=function(){
console.log('In process');
	if(this.status == 200)
	{	
		var content=JSON.parse(this.responseText);
		var i=0;
		var output='';
		console.log(content.Search.length);
		for(a in content.Search)
		{
		output+='<div class="container"><img src="'+content.Search[a].Poster+'" class="image" height="300" width="250"><div class="overlay"><div class="content">'+content.Search[a].Title+'<button type="submit" value="'+content.Search[a].imdbID+'" onclick="fav(this.value)">Favourites</button><button type="submit" value="'+content.Search[a].imdbID+'" onclick="watchlist(this.value)">Watchlist</button><button type="submit" value="'+content.Search[a].imdbID+'" onclick="more(this.value)">More</button></div></div></div>';
	    }
	    document.getElementById('view').innerHTML=output;
	}
	else
	{
		console.log('Not ready');
	}
}
xhr.send();
function fav(val){
 var favid=val;
 var param='favid='+favid;
 var xhr =new XMLHttpRequest();
 xhr.open('POST','profile.php',true);
 xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xhr.send(param);
}
function watchlist(val){
 var watid=val;
 var param='watid='+watid;
 var xhr =new XMLHttpRequest();
 xhr.open('POST','profile.php',true);
 xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xhr.send(param);
}
function more(val){
 var title='';
 var moreid=val;
 window.location.replace("more.php?id="+moreid);
}

</script>
</body>
</html>
	