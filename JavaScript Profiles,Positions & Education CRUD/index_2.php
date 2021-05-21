


<?php 
session_start();
require_once "pdo.php";
if ( ! isset($_SESSION['name'])   ) {
  die('Not logged in');
 
}

echo('<table border="2">'."\n");
$st= $pdo->query("SELECT * FROM autos");
while ($row =$st->fetch(PDO::FETCH_ASSOC))
{echo "<p>";
echo "<tr><td>";
echo (htmlentities($row['make']));
echo ("</td><td>");
echo (htmlentities($row['model']));
echo ("</td><td>");
echo (htmlentities($row['year']));
echo ("</td><td>");
echo (htmlentities($row['mileage']));
echo ("</td><td>");
echo('<a href="edit.php?autos_id='.$row['autos_id'].'">Edit</a> / ');
echo('<a href="delete.php?autos_id='.$row['autos_id'].'">Delete</a>');
echo ("</td></tr>\n");
echo "</p>";
}
?>

</p>
<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Md Shohanoor Rahman's AUTOS DATABASE</title>
</head>
<body>

<div class="container">

<p>
</body>
<?php

echo "Welcome ".($_SESSION['name']);

if ( isset($_SESSION['success']) ) {
    echo('<p style="color: green;">'.htmlentities($_SESSION['success'])."</p>\n");
    unset($_SESSION['success']);
  }
  

?>
<p>
<a href="add.php">Add New Entry</a>
<a href="logout.php">Logout</a>


</p>

</p>
</body>
