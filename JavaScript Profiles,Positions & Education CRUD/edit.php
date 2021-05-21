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

    else if ( strpos(($_POST['email']),'@' ) === false )
     {   $_SESSION['error'] = "Email address must contain @"; 
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
        return;
    }
    else if ( (validatePos()!==true)  )
     {
        $_SESSION['error'] = validatePos();
        header("Location: edit.php?profile_id=".$_GET['profile_id']);
        return;
     }
     else if ( (validateEdu() ) !== true )
     {
        $_SESSION['error'] = validateEdu();
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
            

        $stmt = $pdo->prepare('DELETE FROM Position WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));
      
        $rank=1;
        

        for($i=1; $i<=9; $i++) {
          if ( ! isset($_POST['year'.$i]) ) continue;
          if ( ! isset($_POST['desc'.$i]) ) continue;
      
          $year = $_POST['year'.$i];
          $desc = $_POST['desc'.$i];

          $stmt = $pdo->prepare('INSERT INTO Position (profile_id, rank, year, description) VALUES ( :pid, :rank, :year, :desc)');

          $stmt->execute(array(
          ':pid' => $_REQUEST['profile_id'],
          ':rank' => $rank,
          ':year' => $year,
          ':desc' => $desc)
        );
        
        $rank++;}




        $stmt = $pdo->prepare('DELETE FROM Education WHERE profile_id=:pid');
        $stmt->execute(array( ':pid' => $_REQUEST['profile_id']));

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
            ':pid1' => $_REQUEST['profile_id'],
            ':rank' => $rank,
            ':year1' => $year1,
            ':eid' => $insid)
          );
          
          $rank++;}
 
        $_SESSION['success'] = "Record Updated";
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
  
  $stmt1 = $pdo->prepare("SELECT * FROM Position where profile_id = :xyz");
  $stmt1->execute(array(":xyz" => $_GET['profile_id']));
  $row1 = $stmt1->fetchAll();

  $stmt2= $pdo->prepare("SELECT name ,year  FROM Education JOIN Institution ON Education.institution_id=Institution.institution_id WHERE profile_id=:hello");
  $stmt2->execute(array(":hello" => $_GET['profile_id']));
  $row2 = $stmt2->fetchAll();


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
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.2.1.js" integrity="sha256-DZAnKJ/6XZ9si04Hgrsxu/8s717jcIzLy3oi35EouyE=" crossorigin="anonymous"></script>
<?php require_once "bootstrap.php"; 
require_once "head.php";?>
<body>
<div class="container">

<h1>Edit Resume </h1>


<h2>Edit Entry</h2>
<?php
 if ( isset($_SESSION['error']) ) {
  echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);}

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
<p>
<form method="POST">
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


        <p>Education: <input type="submit" id="addEdu" value="+"> </p>
       <div id="education_fields">

       <?php
            $rank = 1;
            foreach ($row2 as $rows) {
                echo "<div id= education" . $rank . ">
  <p>Year: <input type=text name=year1 value=".$rows['year'].">
  <input type=button value='-'  onclick= $('#education". $rank ."').remove();return false;></p>
  <p>School: <input type=text name=school". $rank ."'). size=50 value=".$rows['name']." class=school />
</div>";
                $rank++;
            } ?>

     </div>

        <p>Position: <input type="submit" id="addPos" value="+"> </p>
<div id="position_fields">
<?php
            $rank = 1;
            foreach ($row1 as $rown) {
                echo "<div id= position" . $rank . ">
  <p>Year: <input type=text name=year value=".$rown['year'].">
  <input type=button value='-'  onclick= $('#position". $rank ."').remove();return false;></p>
  <textarea name=desc". $rank ."'). rows=8 cols=80>".$rown['description']."</textarea>
</div>";
                $rank++;
            } ?>
</div>
<input type="submit" onclick="return doValidate();" value="Save" >
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



        // http://stackoverflow.com/questions/17650776/add-remove-html-inside-div-using-javascript
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
                onclick="$(#position' + countPos + ').remove();return false;"></p> \
            <textarea name="desc' + countPos + '" rows="8" cols="80"></textarea>\
            </div>');
            });
        });
    </script>


</p>


</body>

</html>


