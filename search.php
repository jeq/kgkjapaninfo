<?php
header("Content-type: text/html; charset=utf-8");

if(empty($_POST)) {
	header("Location: index.html");
	exit();
}else{
	//名前入力判定
	if (!isset($_POST['univ_name'])  || $_POST['univ_name'] === "" ){
		$errors['name'] = "大学名が入力されていません。";
	}
}

if(count($errors) === 0){

	$dsn = 'mysql:host=mysql1.php.xdomain.ne.jp;dbname=kgkjapaninfo_admin;charset=utf8';
	$user = 'kgkjapaninfo_abc';
	$password = 'jesusLOVESyou';

	try{
		$dbh = new PDO($dsn, $user, $password);
		$statement = $dbh->prepare("SELECT * FROM univ_data WHERE univ_name LIKE (:name) ");

		if($statement){
			$univ_name = $_POST['univ_name'];
			$like_univ_name = "%".$univ_name."%";
			//プレースホルダへ実際の値を設定する
			$statement->bindValue(':name', $like_univ_name, PDO::PARAM_STR);

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
		<title>検索結果　-　KGK Japan info</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<link rel="stylesheet" href="assets/css/main.css" />
	</head>
	<body>
		<script src="//accaii.com/kgkinfo/script.js" async></script><noscript><img src="//accaii.com/kgkinfo/script?guid=on"></noscript>

		<!-- Wrapper -->
		<div id="wrapper" class="divided">

			<!-- Five -->
				<section class="wrapper style1 align-center">
					<div class="inner">

					<?php if (count($errors) === 0): ?>

					<p><?=htmlspecialchars($univ_name, ENT_QUOTES, 'UTF-8')."で検索しました。"?></p>
					<p><?=$row_count?>件です。</p>

					<table class="alt tbresp">
						<tr><td>情報登録</td><td>学校名</td></tr>

						<?php
						foreach($rows as $row){
						?>
						<tr>
							<td><?=$row['univ_fill']?></td>
							<td><a href="result.php?univ_id=<?=$row['univ_id']?>"><?=htmlspecialchars($row['univ_name'],ENT_QUOTES,'UTF-8')?></a></td>
						</tr>
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

					</table>
					<a href="index.html" class="button special">TOPに戻る</a>
			</div>

	</body>
</html>