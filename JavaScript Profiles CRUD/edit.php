<?php

session_start();
require_once "pdo.php";
if ( ! isset($_SESSION['name'])   ) {
    die('Not logged in');
   
}


if (  isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) && isset($_POST['user_id']) )  
{

    if ( strlen($_POST['first_name']) <1  || strlen($_POST['last_name']) <1 || strlen($_POST['email']) <1  || strlen($_POST['headline'])<1 || strlen($_POST['summary']) <1 )
 { $_SESSION['error'] = "All fields are required";
    header("Location: edit.php?profile_id=".$_GET['profile_id']);
    return;
 }

     if ( strpos(($_POST['email']),'@' ) === false )
     {   $_SESSION['error'] = "Email address must contain @"; 
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
        return;
    }

   else {

$sql= "UPDATE Profile SET   first_name = :fn, last_name = :ln, email = :em, headline = :he, summary= :su
WHERE profile_id = :profile_id";
$stmt = $pdo->prepare($sql);      
$stmt->execute(array(
      
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'],
        ':profile_id' => $_GET['profile_id'] )
        
    );
      $_SESSION['success'] = "Resume Updated";
      header("Location: index.php");
      return;
   }
    
}

if ( ! isset($_GET['profile_id']) ) {
    $_SESSION['error'] = "Missing profile_id";
    header("Location: index.php");
    return;
  }

  $stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
  $stmt->execute(array(":xyz" => $_GET['profile_id']));
  $row = $stmt->fetch(PDO::FETCH_ASSOC);


  if ( $row === false ) {
      $_SESSION['error'] = 'Bad value for profile_id';
      header( 'Location: index.php' ) ;
      return;
  } 
 
  if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to login.php
    header("Location: index.php");
    return;
}

$a = htmlentities($row['first_name']);
$b = htmlentities($row['last_name']);
$c = htmlentities($row['email']);
$d = htmlentities($row['headline']);
$e = htmlentities($row['summary']);
$f = htmlentities($row['user_id']);
//$profile_id = $row['profile_id'];
//$user_id = $row['user_id'];

?>
<!DOCTYPE html>
<html>
<head>
<title>Md Shohanoor Rahman</title>

</head>
<?php require_once "bootstrap.php"; ?>
<body>
<div class="container">

<h1>Edit Resume </h1>


<h2>Edit Entry</h2>
<?php
 if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);}
 
?>
<p>
<form method="post">
        <p>First Name:
            <input type="text" name="first_name" size="60" value="<?=$a ?>"/></p>
        <p>Last Name:
            <input type="text" name="last_name" size="60" value="<?=$b ?>"/></p>
        <p>Email:
            <input type="text" name="email" size="30" value="<?= $c ?>"/></p>
        <p>Headline:<br/>
            <input type="text" name="headline" size="80" value="<?= $d ?>"/></p>
        <p>Summary:<br/>
            <textarea name="summary" rows="8" cols="80"><?= $e ?></textarea>
        <p>
        <input type="hidden" name="user_id"  value="<?= $e ?>" ><br/>
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>

</div>
</body>

</html>


