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
     else if ( (validatePos() ) !== true )
     {
        $_SESSION['invalid'] = validatePos();
        header("Location: add.php");
        return;
     }

     else if ( (validateEdu() ) !== true )
     {
        $_SESSION['invalid'] = validateEdu();
        header("Location: add.php");
        return;
     }

   else {

      $stmt = $pdo->prepare('INSERT INTO Profile (user_id, first_name, last_name, email, headline, summary) VALUES ( :uid, :fn, :ln, :em, :he, :su)');

      $stmt->execute(array(
        ':uid' => $_SESSION['user_id'],
        ':fn' => $_POST['first_name'],
        ':ln' => $_POST['last_name'],
        ':em' => $_POST['email'],
        ':he' => $_POST['headline'],
        ':su' => $_POST['summary'])
      );
      
      $profile_id = $pdo->lastInsertId();
      $rank=1;
    
      
      $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');
      
      for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
    
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];

      $stmt->execute(array(
        ':pid' => $profile_id,
        ':rank' => $rank,
        ':year' => $year,
        ':desc' => $desc)
      );
      
      $rank++;}
      

      $rank=1;
     
      
    
      
      for($i=1; $i<=9; $i++) {
        
        if ( ! isset($_POST['year1'.$i]) ) continue;
        if ( ! isset($_POST['school'.$i]) )  continue;
    
        $year1 = $_POST['year1'.$i];
        $school = $_POST['school'.$i];
      
        $stmt2 = $pdo->prepare('SELECT institution_id FROM Institution WHERE name= :school'); 
        
        $stmt2->execute(array(':school' => $school ));
        $rowinsid =$stmt2->fetch(PDO::FETCH_ASSOC);
        if ($rowinsid!==false)
       { $insid= ($rowinsid['institution_id']);}
       
       if ($rowinsid===false)
       {
        $stmt = $pdo->prepare('INSERT INTO Institution (name) VALUES ( :name)');
        $stmt->execute(array(':name' => $school));
        $insid= $pdo->lastInsertId();
      
       }


        $stmt = $pdo->prepare('INSERT INTO Education (profile_id,institution_id, rank, year) VALUES ( :pid1, :eid, :rank, :year1)');
        $stmt->execute(array(
        ':pid1' => $profile_id,
        ':rank' => $rank,
        ':year1' => $year1,
        ':eid' => $insid)
      );
      
      $rank++;}
      

     
      $_SESSION['success'] = "Record added";
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

    function validatePos() {
      for($i=1; $i<=9; $i++) {
        if ( ! isset($_POST['year'.$i]) ) continue;
        if ( ! isset($_POST['desc'.$i]) ) continue;
    
        $year = $_POST['year'.$i];
        $desc = $_POST['desc'.$i];
    
        if ( strlen($year) == 0 || strlen($desc) == 0 ) {
          return "All fields are required";
        }
    
        if ( ! is_numeric($year) ) {
          return "Position year must be numeric";
        }
      }
      return true;
    }    
  

    if ( isset($_SESSION["invalid"]) ) {
      echo('<p style="color:red">'.$_SESSION["invalid"]."</p>\n");
      unset($_SESSION["invalid"]);
  }

  function validateEdu() {
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['year1'.$i]) ) continue;
      if ( ! isset($_POST['school'.$i]) ) continue;
  
      $year1 = $_POST['year1'.$i];
      $school = $_POST['school'.$i];
  
      if ( strlen($year1) == 0 || strlen($school) == 0 ) {
        return "All fields are required";
      }
  
      if ( ! is_numeric($year1) ) {
        return "Education year must be numeric";
      }
    }
    return true;
  }    



?>


<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<?php require_once "bootstrap.php"; 
 require_once "head.php";
 ?>
<title>Md Shohanoor Rahman's Profile Manager</title>
</head>
<body>

<div class="container">

<p>
<?php

echo "Welcome ".($_SESSION['name']);


?>
<form method="post">

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
<p>Education: <input type="submit" id="addEdu" value="+"> </p>
<div id="education_fields">
</div>
<p>Position: <input type="submit" id="addPos" value="+"> </p>
<div id="position_fields">
</div>
<input type="submit"  value="Add" >
<input type="submit" name="cancel" value="Cancel">
</p>
</form>

    

<script>
        countPos = 0;
        countEdu = 0;
        // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript 
        
        $(document).ready(function () {
            window.console && console.log('Document ready called');
            $('#addEdu').click(function (event) {
                // http://api.jquery.com/event.preventdefault/
                event.preventDefault();
                if (countEdu >= 9) {
                    alert("Maximum of nine education entries exceeded");
                    return;
                }
                countEdu++;
                window.console && console.log("Adding position " + countEdu);
                
                $('#education_fields').append(
                    '<div id="education' + countEdu + '"> \
            <p>Year: <input type="text" name="year1' + countEdu + '" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#education' + countEdu + '\').remove();return false;"></p> \
                <p>School: <input type="text" name="school' + countEdu + '" value="" size="50" class="school" />\
            </div>');
            $('.school').autocomplete({ source: "school.php" });
            });
        });


        $(document).ready(function () {
            window.console && console.log('Document ready called');
            $('#addPos').click(function (event) {
                // http://api.jquery.com/event.preventdefault/
                event.preventDefault();
                if (countPos >= 9) {
                    alert("Maximum of nine position entries exceeded");
                    return;
                }
                countPos++;
                window.console && console.log("Adding position " + countPos);
                $('#position_fields').append(
                    '<div id="position' + countPos + '"> \
            <p>Year: <input type="text" name="year' + countPos + '" value="" /> \
            <input type="button" value="-" \
                onclick="$(\'#position' + countPos + '\').remove();return false;"></p> \
            <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
            </div>');
            });
        });
    </script>


</p>
</body>
</html>
