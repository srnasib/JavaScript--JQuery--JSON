
<?php 
session_start();
require_once "pdo.php";


if ( ! isset($_SESSION['name'])   ) {
    die('Not logged in');
   
}
if ( isset($_POST['cancel'] ) ) {
    // Redirect the browser to login.php
    header("Location: index.php");
    return;
}


if (  isset($_POST['first_name']) && isset($_POST['last_name']) && isset($_POST['email']) && isset($_POST['headline']) && isset($_POST['summary']) )
{

    if ( strlen($_POST['first_name']) <1  || strlen($_POST['last_name']) <1 || strlen($_POST['email']) <1  || strlen($_POST['headline'])<1 || strlen($_POST['summary']) <1 )
    {
        $_SESSION['invalid'] = "All fields are required";
        header("Location: add.php");
        return;
    
    }

     else if ( strpos(($_POST['email']),'@' ) === false )
     {
        $_SESSION['invalid'] = "Email address must contain @";
        header("Location: add.php");
        return;
     }

   else {
$stmt = $pdo->prepare('INSERT INTO Profile
        (user_id, first_name, last_name, email, headline, summary)
        VALUES ( :uid, :fn, :ln, :em, :he, :su)');
      
$stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
      );
      $_SESSION['success'] = "Resume added";
      header("Location: index.php");
      return;

    }

}


?>
<?php
    if ( isset($_SESSION["invalid"]) ) {
        echo('<p style="color:red">'.$_SESSION["invalid"]."</p>\n");
        unset($_SESSION["invalid"]);
    }
?>


<!DOCTYPE html>
<html>
<head>
<?php require_once "bootstrap.php"; ?>
<title>Md Shohanoor Rahman's Resume Manager</title>
</head>
<body>

<div class="container">

<p>
<?php

echo "Welcome ".($_SESSION['name']);


?>
<form method="POST">
<label for="nam">Make :   </label>
<input type="hidden" name="user_id" id="nam" size ="40" ><br/>
<label for="fn">First Name :   </label>
<input type="text" name="first_name" id="fn" size ="40" ><br/>
<label for="ln">Last Name :   </label>
<input type="text" name="last_name" id="ln" size ="40" ><br/>
<label for="em">Email :   </label>
<input type="text" name="email" id="em" size ="30" ><br/>
<label for="hl">Headline :   </label>
<input type="text" name="headline" id="hl" size ="45" ><br/>
<p> <strong>  Summary : </strong> </p>
<textarea name="summary"  rows="10" cols="40"></textarea> ><br/>
<input type="submit" value="Add">
<input type="submit" name ="cancel" value="Cancel">
 
</form>
</p>
</body>


