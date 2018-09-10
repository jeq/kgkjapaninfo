<?php
header("Content-type: text/html; charset=utf-8");

if(empty($_POST)) {
	header("Location: editconf.php");
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
		$statement = $dbh->prepare("SELECT * FROM univ_data WHERE univ_id = (:id) ");

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
			$statement->bindValue(':id', $univ_id, PDO::PARAM_STR);

			// UPDATE文を変数に格納
			$sql = "UPDATE univ_data SET univ_resp = :resp, univ_email = :email, univ_url1 = :url1, univ_url2 = :url2, univ_url3 = :url3, univ_comment = :comment, date_renew = :renew, univ_fill = :fill WHERE univ_id = :id";

			// 更新する値と該当のIDは空のまま、SQL実行の準備をする
			$stmt = $dbh->prepare($sql);

			// 挿入する値が複数の場合はカンマ区切りで追加する
			$params = array(':id' => $univ_id, ':resp' => $univ_resp, ':email' => $univ_email, ':url1' => $univ_url1, ':url2' => $univ_url2, ':url3' => $univ_url3, ':comment' => $univ_comment, ':renew' => $date_renew, ':fill' => "〇");

			// 修正する値と該当のIDが入った変数をexecuteにセットしてSQLを実行
			$stmt->execute($params);

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
		<title>編集完了　-　KGK Japan info</title>
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


<?php


// 更新完了のメッセージ
echo '更新完了しました';
?>
<div class="inner">
		<a href="../result.php?univ_id=<?php echo $univ_id?>" class="button">詳細に戻る</a>
		<a href="../index.html" class="button special">TOPに戻る</a>
</div>

			</div>

	</body>
</html>