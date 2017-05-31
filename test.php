<?php
	require_once "config.php";
	require_once "db.php";
	require_once "user.php";

	session_start();

	$grade = get_grade_by_id($_SESSION["id"], $dbh);
	if($grade != null){
		header("location: mainweb.php");
	}

	$sql = $dbh->prepare("SELECT * FROM question");
	$sql->execute();
	$row = $sql->fetchAll(PDO::FETCH_ASSOC);

	foreach ($row as $key) {
		/*
		echo "<h4>".$key["question"]."</h4>";
		echo $key["choiceA"]."</br>";
		echo $key["choiceB"]."</br>";
		echo $key["choiceC"]."</br>";
		echo $key["choiceD"];
		*/
		$answers = array();
		$answers[0] = $key["choiceA"]; 
		$answers[1] = $key["choiceB"]; 
		$answers[2] = $key["choiceC"];
		$answers[3] = $key["choiceD"];
		$arr[] = array(
			'sid' => $_SESSION["id"],
			'qid' => $key['id'],
			'question' => $key['question'],
			'answers' => $answers
		);
	}

	$json = json_encode($arr);


?>

<!DOCTYPE html>
<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" href="/css/style.css">
	<link rel="stylesheet" href="https://cdn.bootcss.com/bootstrap/3.3.0/css/bootstrap.min.css">
	<script src="https://cdn.bootcss.com/jquery/1.11.1/jquery.min.js"></script>
	<script src="https://cdn.bootcss.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<link rel="stylesheet" type="text/css" href="styles.css" />
	<style type="text/css">
		.demo{width:1080px; margin:60px auto 10px auto}
	</style>
	<script type="text/javascript" src="http://lib.baomitu.com/jquery/1.7.2/jquery.min.js"></script>
	<script src="quizs.js"></script>
	<script>
		$(function(){
			$('#quiz-container').jquizzy({
		        questions: <?php echo $json;?>,
				sendResultsURL: 'submit.php'
		    });
		});
	</script>

	<title>浙江大学广播电视台精品课程在线答题系统</title>
</head>

<body>
	<nav class="navbar navbar-default" role="navigation" style="background: white;">
		<div class="container-fluid">
			<!-- Brand and toggle get grouped for better mobile display -->
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a class="navbar-brand" href="http://10.214.4.212:3001/#" style="padding: 0 1rem 0 1rem ;"><img src="images/logo.png" style="height:5rem"></a>
			</div>

			<!-- Collect the nav links, forms, and other content for toggling -->
			<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
				<ul class="nav navbar-nav">
					<li><p class="navbar-text navbar-right">浙江大学广播电视台精品课程在线答题系统&nbsp;&nbsp;</p></li>
				</ul>

				<div class="nav navbar-nav navbar-right">
	                <p class="navbar-text navbar-right">祝考试顺利~&nbsp;<?php echo $_SESSION["username"]?>&nbsp;</p>
				</div>
		    </div><!-- /.navbar-collapse -->
		</div><!-- /.container-fluid -->
	</nav>

	<div class="well">
		<div style="margin-top:0rem;">
			<div id='quiz-container'></div>
		</div>
	</div>

	<?php include "footer.php" ?>

</body>
</html>
