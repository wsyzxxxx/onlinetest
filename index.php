<?php

session_start();
if (isset($_SESSION["id"]))
    header("location: test.php");
else {
    header("location: login.php");
}

?>

<html>
<body>
<h4>Redirecting</h4>
</body>
</html>
