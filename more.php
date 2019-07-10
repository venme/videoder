<!DOCTYPE html>
<html>
<head>
	<title></title>
<link rel="stylesheet" type="text/css" href="style1.css">
</head>
<body class="profile">
<?php
session_start();
if(!isset($_SESSION['NAME']))
{
$link="http://";
$link.=$_SERVER['HTTP_HOST'];
$link.=$_SERVER['REQUEST_URI'];
$_SESSION['redirect']=$link;
header("Location:startup.php");
exit();
}
unset($_SESSION['redirect']);
$moreid=$_GET['id'];
include 'includes/db.php';
$sql="SELECT * FROM likes WHERE id='".$moreid."'";
$rows=$conn->query($sql);
$row=$rows->fetch_assoc();
$likescount=$row['likes'];
?>
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
<div id="detailedview" style="width: 100%;"></div>
<script>
var id="<?php echo $moreid ?>";
var likescount="<?php echo $likescount ?>";
var xh =new XMLHttpRequest();
xh.open('POST','http://www.omdbapi.com/?i='+id+'&apikey=59a7fd23',true);
xh.onload=function(){
if(this.status == 200)
{ 
 var cont=JSON.parse(this.responseText);
 var out='';
 title=cont.Title;
 out+='<div class="container" style="float:left;margin-left:50px;"><img src="'+cont.Poster+'" class="image" height="600" width="500"><div class="overlay"><div class="content">'+cont.Title+'<button type="submit" value="'+cont.imdbID+'" onclick="fav(this.value)">Favourites</button><button type="submit" value="'+cont.imdbID+'" onclick="watchlist(this.value)">Watchlist</button></div></div></div><br><p style="text-align:left;max-width:1500px;font-size:25px;float:left;">Title:'+cont.Title+'<br><br>  Genre:'+cont.Genre+'<br><br>  Director:'+cont.Director+'<br><br>  Actors:'+cont.Actors+'<br><br>  Year:'+cont.Year+'<br><br>  Plot:'+cont.Plot+'<br><br>  Runtime:'+cont.Runtime+'<br><br>  Production:'+cont.Production+'<br><br>  Boxoffice:'+cont.Boxoffice+'<br><br>  Ratings:'+cont.Ratings[0].Source+'  :  '+cont.Ratings[0].Value+' </p>';
 document.getElementById('detailedview').innerHTML=out;
 player(title);
 }
 else 
  {
   console.log('Not ready');
  }
 }
 xh.send();
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

 function player(val){
  var key='AIzaSyCPocjMGIbhFF8XG-PfUKf9QiMsei4_BuE';
  var out='';
console.log(val+"TITLE");
 var xh=new XMLHttpRequest();
 xh.open('GET','https://www.googleapis.com/youtube/v3/search?part=snippet&q="'+val+'%20trailer"&maxResults=1&key='+key);
 xh.onload=function(){
 if(this.status == 200)
  { 
    var cont=JSON.parse(this.responseText);
    console.log(cont);
    var out='';
    out+='';
    var videoid=cont.items[0].id.videoId;
    out='<br><br><br><br><iframe width="800" height="500" src="https://www.youtube.com/embed/'+videoid+'?autoplay=1"></iframe><br><button type="submit" value="'+id+'" onclick="like(this.value)">Like '+likescount+'</button><input type="text" id="comment" placeholder="Comment"><button type="submit" value="'+id+'" onclick="comment(this.value)">Comment</button>';
    document.getElementById('detailedview').innerHTML+=out;
  }
 else 
  {
   console.log('Not ready');
  }
 }
 xh.send();
}
function comment(val){
 var commentid=val;
 var comment=document.getElementById('comment').value;
 var param='commentid='+commentid+'&comment='+comment;
 var xhr =new XMLHttpRequest();
 xhr.open('POST','profile.php',true);
 xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xhr.send(param);
}
function like(val){
 var likeid=val;
 var param='like='+likeid;
 var xhr =new XMLHttpRequest();
 xhr.open('POST','profile.php',true);
 xhr.setRequestHeader('Content-type','application/x-www-form-urlencoded');
 xhr.send(param);
}
</script>
<?php 
$link="http://";
$link.=$_SERVER['HTTP_HOST'];
$link.=$_SERVER['REQUEST_URI'];
echo '<br><br><iframe src="https://www.facebook.com/plugins/share_button.php?href='.$link.'&layout=button_count&size=large&width=84&height=28&appId" width="84" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe><a href="'.$link.'" class="twitter-share-button" data-size="large" data-show-count="false">Tweet</a><script async src="https://platform.twitter.com/widgets.js" charset="utf-8"></script>'; 
$sql="SELECT * FROM comments WHERE id='".$moreid."'";
$rows=$conn->query($sql);
echo "<h3>Comments</h3><table>";
while($row=$rows->fetch_assoc())
{
	echo "<tr><td>".$row['name']."</td><td>".$row['comment']."</td></tr>";
}
echo "</table>";
?>
</body>
</html>
