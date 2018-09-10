<?php
header("Content-type: text/html; charset=utf-8");

if(empty($_GET)) {
	header("Location: ../result.php");
	exit();
}else{
	//名前入力判定
	if (!isset($_GET['univ_id'])  || $_GET['univ_id'] === "" ){
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
			$univ_id = $_GET['univ_id'];
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
		<title>編集する　-　KGK Japan info</title>
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


					<form method="post" action="editconf.php">
						<div class="field half first">
							<label for="text">担当者名</label>
							<input type="text" name="univ_resp" id="univ_resp" value="<?=htmlspecialchars($row['univ_resp'],ENT_QUOTES,'UTF-8')?>" />
						</div>
						<div class="field half">
							<label for="email">メールアドレス</label>
							<input type="email" name="univ_email" id="univ_email" value="<?=htmlspecialchars($row['univ_email'],ENT_QUOTES,'UTF-8')?>" />
						</div>
						<div class="field half">
							<label for="text">URL①</label>
							<input type="url" name="univ_url1" id="univ_url1" value="<?=htmlspecialchars($row['univ_url1'],ENT_QUOTES,'UTF-8')?>" />
						</div>
						<div class="field half">
							<label for="text">URL②</label>
							<input type="url" name="univ_url2" id="univ_url2" value="<?=htmlspecialchars($row['univ_url2'],ENT_QUOTES,'UTF-8')?>" />
						</div>
						<div class="field half">
							<label for="text">URL③</label>
							<input type="url" name="univ_url3" id="univ_url3" value="<?=htmlspecialchars($row['univ_url3'],ENT_QUOTES,'UTF-8')?>" />
						</div>
						<div class="field">
							<label for="text">コメント</label>
							<textarea name="univ_comment" id="univ_comment" rows="6"><?=htmlspecialchars($row['univ_comment'],ENT_QUOTES,'UTF-8')?></textarea>
						</div>

						<input type="hidden" name="univ_id" id="univ_id" value="<?=$univ_id?>">

						<ul class="actions">
									<input type="submit" name="submit" id="submit" value="確認画面へ" />
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