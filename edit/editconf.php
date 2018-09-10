<?php
header("Content-type: text/html; charset=utf-8");

if(empty($_POST)) {
	header("Location: edit.php");
	exit();
}else{
	//名前入力判定
	if (!isset($_POST['univ_id'])  || $_POST['univ_id'] === "" ){
		$errors['name'] = "エラーです。TOPに戻ってください。";
	}
}

if(count($errors) === 0){

	$dsn = 'mysql:host=mysql1.php.xdomain.ne.jp;dbname=kgkjapaninfo_admin;charset=utf8';
	$user = 'kgkjapaninfo_abc';
	$password = 'jesusLOVESyou';

	try{
		$dbh = new PDO($dsn, $user, $password);
		$statement = $dbh->prepare("SELECT * FROM univ_data WHERE univ_id = (:name) ");

		if($statement){
			$univ_id = $_POST['univ_id'];
			$univ_email = $_POST['univ_email'];
			$univ_resp = $_POST['univ_resp'];
			$univ_url1 = $_POST['univ_url1'];
			$univ_url2 = $_POST['univ_url2'];
			$univ_url3 = $_POST['univ_url3'];
			$univ_comment = $_POST['univ_comment'];
			$date_renew = date("Y/m/d");
			//プレースホルダへ実際の値を設定する
			$statement->bindValue(':name', $univ_id, PDO::PARAM_STR);

			if($statement->execute()){
				//レコード件数取得
				$row_count = $statement->rowCount();

				while($row = $statement->fetch()){
					$rows[] = $row;
				}

			}else{
				$errors['error'] = "検索失敗しました。";
			}

			//データベース接続切断
			$dbh = null;
		}

	}catch (PDOException $e){
		print('Error:'.$e->getMessage());
		$errors['error'] = "データベース接続失敗しました。";
	}
}

?>

<!DOCTYPE html>
<html>
	<head>
		<title>編集確認　-　KGK Japan info</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="../assets/css/main.css" />
	</head>
	<body>
		<script src="//accaii.com/kgkinfo/script.js" async></script><noscript><img src="//accaii.com/kgkinfo/script?guid=on"></noscript>

		<!-- Wrapper -->
		<div id="wrapper" class="divided">

			<!-- Five -->
				<section class="wrapper style1 align-center">
					<div class="inner">

					<?php if (count($errors) === 0): ?>

					<?php
					foreach($rows as $row){
					?>

					<h2><?=htmlspecialchars($row['univ_name'],ENT_QUOTES,'UTF-8')?></h2>


					<table border="1" class="tbresp">
						<tr><td>項目</td><td>内容</td></tr>


						<tr>
							<td>コメント</td>
							<td><?=nl2br($univ_comment)?></td>
						</tr>
						<tr>
							<td>担当</td>
							<td><?=$univ_resp?></td>
						</tr>
						<tr>
							<td>メール</td>
							<td><?=$univ_email?></td>
						</tr>
						<tr>
							<td>URL①</td>
							<td><?=$univ_url1?></td>
						</tr>
						<tr>
							<td>URL②</td>
							<td><?=$univ_url2?></td>
						</tr>
						<tr>
							<td>URL③</td>
							<td><?=$univ_url3?></td>
						</tr>
						<tr>
							<td>最終更新</td>
							<td><?=$date_renew?></td>
						</tr>
					</table>
					<form method="post" action="editcomp.php">
						<input type="hidden" name="univ_id" id="univ_id" value="<?=$univ_id?>">
						<input type="hidden" name="univ_comment" id="univ_comment" value="<?=$univ_comment?>">
						<input type="hidden" name="univ_resp" id="univ_resp" value="<?=$univ_resp?>">
						<input type="hidden" name="univ_email" id="univ_email" value="<?=$univ_email?>">
						<input type="hidden" name="univ_url1" id="univ_url1" value="<?=$univ_url1?>">
						<input type="hidden" name="univ_url2" id="univ_url2" value="<?=$univ_url2?>">
						<input type="hidden" name="univ_url3" id="univ_url3" value="<?=$univ_url3?>">
						<input type="hidden" name="date_renew" id="date_renew" value="<?=$date_renew?>">
						<ul class="actions">
									<input type="submit" name="submit" id="submit" value="登録する" />
						</ul>
					</form>
						<?php
						}
						?>

						<?php elseif(count($errors) > 0): ?>
						<?php
						foreach($errors as $value){
							echo "<p>".$value."</p>";
						}
						?>
						<?php endif; ?>


			</div>

	</body>
</html>