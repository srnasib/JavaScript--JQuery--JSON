<!DOCTYPE html>
<html>
<head>
<title>Md Shohanoor Rahman</title>

</head>
<body>

<div class="container">
<h1>Profile Information </h1>
</body>
<?php
require_once "pdo.php";
 require_once "bootstrap.php";


 $c=  $_GET['profile_id'] ;

$pro1= $pdo->query("SELECT first_name, last_name, headline,email,summary FROM Profile WHERE profile_id=$c");
while  ($row1 =$pro1->fetch(PDO::FETCH_ASSOC))    

{
 echo  ( "<p> <strong>   First Name : </strong>".htmlentities($row1['first_name'])."</p>");
 echo  ( "<p> <strong>   Last Name : </strong>".htmlentities($row1['last_name'])."</p>");
 echo  ( "<p> <strong>   Email : </strong>".htmlentities($row1['email'])."</p>");
 echo  ( "<p> <strong>   Headline : </p>  </strong>".htmlentities($row1['headline'])."<p> </p>");
 echo  ( "<p> <strong>   Summary : </p>  </strong>".htmlentities($row1['summary'])."<p> </p>");
 




}

?>

<a href="index.php"
 target="_blank">Done</a>