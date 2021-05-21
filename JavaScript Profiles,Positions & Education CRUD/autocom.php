<?php

require_once "pdo.php";
require_once "scripts.php";
?>
<!DOCTYPE html>
<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css" integrity="sha384-fLW2N01lMqjakBkx3l/M9EahuwpSfeNvV63J5ezn3uZzapT0u7EYsXMjQV+0En5r" crossorigin="anonymous">
<head>
<title>Md Shohanoor Rahman's Profile Manager</title>
</head>
<body>

<p>School: <input type="text" name="school11" value="" size="50" class="school" />

<script type="text/javascript" src="jquery.min.js">

$('.school').autocomplete({ source: "school.php" });




</script>


</body>
</html>