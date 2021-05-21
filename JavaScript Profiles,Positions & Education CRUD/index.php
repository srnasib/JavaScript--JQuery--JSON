<!DOCTYPE html>
<html>
<head>
<title>Md Shohanoor Rahman</title>
<?php require_once "bootstrap.php";
session_start();
require_once "pdo.php";

?>
</head>
<body>
<div class="container">
<h1>Welcome to Rahman's Resume Registry</h1>
<p><strong>Note:</strong> This assignment is fully done and ready for Autograding.
</p>

<p>
</body>
<?php

if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
  }
  if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
  } 

  
  
?>

<?php
if ( isset($_SESSION['name']) ) {
    echo "Welcome ".($_SESSION['name']);

    echo('<table border="2">'."\n");
   echo " <tr>
    <th>Name</th>
    <th>Headline</th>
    <th>Action</th>
      </tr> ";
    $pro= $pdo->query("SELECT first_name, last_name, profile_id ,headline FROM profile");
   while  ($row =$pro->fetch(PDO::FETCH_ASSOC))
   {echo "<p>";
    
   
      echo "<tr><td>";     

$d=  ((htmlentities($row['first_name']))." ".htmlentities($row['last_name']));
echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$d.'</a>');

   echo ("</td><td>");
   echo (htmlentities($row['headline']));
   echo ("</td><td>");
  
   echo('<a href="edit.php?profile_id='.$row['profile_id'].'">Edit</a> / ');
   echo('<a href="delete.php?profile_id='.$row['profile_id'].'">Delete</a>');
   echo ("</td></tr>\n");
   echo "</p>";
}



echo '<p> <a href="add.php">Add New Entry</a> </p>';
    echo '<p> <a href="logout.php">Log Out</a> </p>';
    
             }

 if ( !isset($_SESSION['name']) ){
                

    echo '<a href="login.php">Please log in </a>';
    
       echo('<table border="2">'."\n");
       echo " <tr>
        <th>Name</th>
        <th>Headline</th>
        </tr> ";
        $pro2= $pdo->query("SELECT first_name, last_name, profile_id ,headline FROM profile");
        while  ($row =$pro2->fetch(PDO::FETCH_ASSOC))
       {echo "<p>";
       echo "<tr><td>";     
       $e=  ((htmlentities($row['first_name']))." ".htmlentities($row['last_name']));
       echo('<a href="view.php?profile_id='.$row['profile_id'].'">'.$e.'</a>');
       echo ("</td><td>");
       echo (htmlentities($row['headline']));
       echo ("</td></tr>\n");
       echo "</p>";     
        }

    }
?>

<p>
Attempt to go to 
<a href="add.php">add.php</a> without logging in - it should fail with an error message.
<p>
<p>
Attempt to go to 
<a href="edit.php">edit.php</a> without logging in - it should fail with an error message.
<p>
<a href="https://www.wa4e.com/solutions/res-profile/"
 target="_blank">An Example solve of this Application</a>
</p>
</div>
</body>

