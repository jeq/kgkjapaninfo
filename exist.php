<?php
header("Content-type: text/html; charset=utf-8");



if(count($errors) === 0){

	$dsn = '***';
	$user = '***';
	$password = '***';

	try{
		$dbh = new PDO($dsn, $user, $password);
		$statement = $dbh->prepare("SELECT * FROM univ_data WHERE univ_fill = '〇' ORDER BY date_renew DESC; ");

		if($statement){

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
		<title>情報があるページ　-　KGK Japan info</title>
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

					<p><?=$row_count?>件です。</p>

					<table class="alt tbresp">
						<tr><td>更新日</td><td>学校名</td></tr>

						<?php
						foreach($rows as $row){
						?>
						<tr>
							<td><?=htmlspecialchars($row['date_renew'],ENT_QUOTES,'UTF-8')?></td>
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