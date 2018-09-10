<?php
header("Content-type: text/html; charset=utf-8");

if(empty($_GET)) {
	header("Location: search.php");
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


<?php if (count($errors) === 0): ?>

					<?php
					foreach($rows as $row){
					?>
<!DOCTYPE html>
<html>
	<head>
		<title><?=htmlspecialchars($row['univ_name'],ENT_QUOTES,'UTF-8')?>　詳細ページ　-　KGK Japan info</title>
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



					<h2><?=htmlspecialchars($row['univ_name'],ENT_QUOTES,'UTF-8')?></h2>


					<table border="1">
						<tr><th>項目</th><td>内容</td></tr>


						<tr>
							<th>コメント</th>
							<td><?=nl2br(htmlspecialchars($row['univ_comment'],ENT_QUOTES,'UTF-8'))?></td>
						</tr>
						<tr>
							<th>担当</th>
							<td><?=htmlspecialchars($row['univ_resp'],ENT_QUOTES,'UTF-8')?></td>
						</tr>
						<tr>
							<th>メール</th>
							<td><a href="mailto:<?=htmlspecialchars($row['univ_email'],ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($row['univ_email'],ENT_QUOTES,'UTF-8')?></a></td>
						</tr>
						<tr>
							<th>URL①</th>
							<td><a href="<?=htmlspecialchars($row['univ_url1'],ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($row['univ_url1'],ENT_QUOTES,'UTF-8')?></a></td>
						</tr>
						<tr>
							<th>URL②</th>
							<td><a href="<?=htmlspecialchars($row['univ_url2'],ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($row['univ_url2'],ENT_QUOTES,'UTF-8')?></a></td>
						</tr>
						<tr>
							<th>URL③</th>
							<td><a href="<?=htmlspecialchars($row['univ_url3'],ENT_QUOTES,'UTF-8')?>"><?=htmlspecialchars($row['univ_url3'],ENT_QUOTES,'UTF-8')?></a></td>
						</tr>
						<tr>
							<th>最終更新</th>
							<td><?=htmlspecialchars($row['date_renew'],ENT_QUOTES,'UTF-8')?></td>
						</tr>
					</table>
					<a href="edit/edit.php?univ_id=<?=$row['univ_id']?>" class="button special">編集する</a>
					<br>
					<a href="index.html" class="button special">TOPに戻る</a>

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