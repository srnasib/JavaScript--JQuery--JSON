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


 //$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
// $stmt->execute(array(":xyz" => $_GET['profile_id']));
 //$row = $stmt->fetch(PDO::FETCH_ASSOC);
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
echo "<p> <strong>  Positions : </strong> </p>";
$stmt = $pdo->prepare("SELECT * FROM position where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row1 = $stmt->fetchAll();
//while  ($row =$stmt->fetchAll())  
$rank=1;
foreach ($row1 as $row)
{echo  ( " <ul> <li> <strong>   year : </strong>".htmlentities($row['year'])."<strong>  
     Description : </strong>".htmlentities($row['description'])." </li> </ul>" );
   // echo  ( "<p> <strong>   First Name : </strong>".htmlentities($row['description'])."</p>");   
  //  echo "<div id= position" . $rank . ">
//<p>Year: <input type=text name=year1 value=".$row['year'].">
//<input type=button value='-'  onclick= $('#position". $rank ."').remove();return false;></p>
//<textarea name=desc". $rank ."'). rows=8 cols=80>".$row['description']."</textarea>
//</div>";
    $rank++;
}

echo "<p> <strong>  Education : </strong> </p>";
$stmt2= $pdo->prepare("SELECT name ,year  FROM Education JOIN Institution ON Education.institution_id=Institution.institution_id WHERE profile_id=:hello");
$stmt2->execute(array(":hello" => $_GET['profile_id']));
$row2 = $stmt2->fetchAll();

foreach ($row2 as $rows)
{echo  ( " <ul> <li> <strong>   year : </strong>".htmlentities($rows['year'])."<strong>  
     School : </strong>".htmlentities($rows['name'])." </li> </ul>" );
  
    $rank++;
}

?>

<a href="index.php"
 target="_blank">Done</a>