<?php
session_start();

require_once "pdo.php";


if (!isset($_SESSION['name'])) {
    die('Not logged in');
}


if (isset($_POST['first_name']) && isset($_POST['summary']) && isset($_POST['last_name']) && isset($_POST['email'])
    && isset($_POST['headline'])) {


if ( strlen($_POST['first_name']) <1  || strlen($_POST['last_name']) <1 || strlen($_POST['email']) <1  || strlen($_POST['headline'])<1 || strlen($_POST['summary']) <1 )
      {  $_SESSION['error'] = "All fields are required";}
    
    if (strpos($_POST['email'], '@') === false) {
        $_SESSION['error'] = 'Bad Email';
    } else {

        $sql = "UPDATE Profile SET first_name = :fn, last_name = :ln,email=:em,headline=:he,summary=:su
            WHERE profile_id = :profile_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(array(
                ':fn' => $_POST['first_name'],
                ':ln' => $_POST['last_name'],
                ':em' => $_POST['email'],
                ':he' => $_POST['headline'],
                ':su' => $_POST['summary'],
                ':profile_id' => $_GET['profile_id'])
        );
        $_SESSION['success'] = 'Record updated';
        header('Location: index.php');
        return;
    }
}

// Guardian: Make sure that user_id is present
if (!isset($_GET['profile_id'])) {
    $_SESSION['error'] = "Missing profile_id";
    header('Location: index.php');
    return;
}

$stmt = $pdo->prepare("SELECT * FROM Profile where profile_id = :xyz");
$stmt->execute(array(":xyz" => $_GET['profile_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row === false) {
    $_SESSION['error'] = 'Bad value for user_id';
    header('Location: index.php');
    return;
}
?>
<!DOCTYPE html>
<html>
<head>
    <?php require_once "bootstrap.php"; ?>
    <title>Samarth Srivastava Login Page</title>
</head>
<body>
<div class="container">
    <h1>Editing Profile for UMSI</h1>
    <?php
    if (isset($_SESSION['error'])) {
        echo('<p style="color: red;">' . htmlentities($_SESSION['error']) . "</p>\n");
        unset($_SESSION['error']);
    }
    ?>
    <form method="post">
        <p>First Name:
            <input type="text" name="first_name" size="60" value="<?php echo $row['first_name'] ?>"/></p>
        <p>Last Name:
            <input type="text" name="last_name" size="60" value="<?php echo $row['last_name'] ?>"/></p>
        <p>Email:
            <input type="text" name="email" size="30" value="<?php echo $row['email'] ?>"/></p>
        <p>Headline:<br/>
            <input type="text" name="headline" size="80" value="<?php echo $row['headline'] ?>"/></p>
        <p>Summary:<br/>
            <textarea name="summary" rows="8" cols="80"><?php echo $row['summary'] ?></textarea>
        <p>
            <input type="submit" value="Save">
            <input type="submit" name="cancel" value="Cancel">
        </p>
    </form>
    <p>

</body>
</html>
