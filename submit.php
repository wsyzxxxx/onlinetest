<?php

	require_once "config.php";
	require_once "db.php";
	require_once "user.php";

	$form = json_decode($_REQUEST['test'], true);

	$score = 0;
	$mark = 0;
	
	foreach ($form['submit'] as $key) {
		
		$num = $key['choiceA']*1 + $key['choiceB']*2 + $key['choiceC']*4 + $key['choiceD']*8;
		
		$sql = $dbh->prepare("INSERT INTO answer VALUES (:sid, :qid, :answer)");
		$sql->execute([":sid" => $form['sid'], ":qid" => $key['qid'], ":answer" => $num]);
		
		$sth = $dbh->prepare("SELECT right_ans, score FROM question WHERE id=:id");
		$sth->execute([":id" => $key['qid']]);
		$row = $sth->fetch(PDO::FETCH_ASSOC);

		$right_ans = intval($row['right_ans']);


		if($right_ans === $num){
			$score += 3;
		}
		else if ($num == 0) {
			$score += 0;
		}
		else if(((~ $right_ans) & $num) > 0){
			$score += 0;
		}
		else{
			$score += 2;
		}
	}
	
	$sql = $dbh->prepare("INSERT INTO grade (cid,sid,score) VALUES (:cid,:sid,:score)");
	$sql->execute([":cid" => $form['cid'], ":sid" => $form['sid'], ":score" => $score]);
	
	echo json_encode($score);
	
?>