

<?php
	require_once "config.php";
	require_once "db.php";
	require_once "user.php";

	session_start();

	echo "<h1>欢迎你 ".$_SESSION["id"].$_SESSION["username"]."</h1>";

	$sql = $dbh->prepare("SELECT cid FROM test WHERE sid=:sid");
	$sql->execute([":sid" => $_SESSION["id"]]);
	$row = $sql->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row as $key) {
		$sql = $dbh->prepare("SELECT * FROM courses WHERE id=:cid");
		$sql->execute([":cid" => $key['cid']]);
		$row2 = $sql->fetch(PDO::FETCH_ASSOC);
		echo $row2["name"]."<button>开始考试</button></br>";
	}

?>



