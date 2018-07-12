<!DOCTYPE html>
<html lang = "ja">
<head>
<meta charset = "utf-8">
</head>
<body>
<?php
$dsn = 'データベース名';
$user = 'ユーザ名';
$password = 'パスワード';
$pdo = new PDO($dsn,$user,$password);

header('Content-Type: text/html; charset=UTF-8');

//新規投稿の場合
if(empty($_POST["editing"])) { //もしhiddenが空だったら
	if (isset($_POST["comment"]) && $_POST["comment"] != ""){//もしコメントに値が入っていれば
		$sql = 'SELECT * FROM 4_1';
		$sql = $pdo -> prepare("INSERT INTO 4_1 (name, comment, time, pass) VALUES (:name, :comment, :time, :pass)");
		$sql -> bindParam(':name', $n, PDO::PARAM_STR);
		$sql -> bindParam(':comment', $c, PDO::PARAM_STR);
		$sql -> bindParam(':time', $d, PDO::PARAM_STR);
		$sql -> bindParam(':pass', $p, PDO::PARAM_STR);
		$n = $_POST['name'];//nameの値を$nに
		$c = $_POST['comment']; //コメントの値を$cに
		$d = date('Y/m/d H:i:s');//dateの値を$dに
		$p = $_POST['password']; 
		$sql -> execute();
	}
}

//削除機能
if (isset($_POST["delete"]) && $_POST["delete"] != "") {
	$delNum = $_POST["delete"];
	$sql = 'SELECT * FROM 4_1';
	$results = $pdo -> query($sql);
		foreach($results as $row) {
			if ($row['id']  ==  $delNum) {
				$password2 = $_POST["password2"];
					if($row['pass'] == $password2) {//パスワードが一致していたら
						$id = $delNum;
						$sql = "delete from 4_1 where id=$id";
 						$result = $pdo->query($sql);
 					}
 			}
 		}
 }
 
 // 編集機能
if(isset($_POST["edit_btn"])){ //編集ボタンが押されたら
    if($_POST["edit_num"]){ //もし編集対象番号に値があったら
    	$edit_num =  $_POST["edit_num"];//変数#edit_numに編集対象の値を格納する
	  	$sql = 'SELECT * FROM 4_1';
	  	$results = $pdo -> query($sql);
			foreach($results as $row) {
				if ($row['id'] == $_POST["edit_num"]){//もしdb内の番号と編集対象番号が一緒なら
					$password1 = $_POST["password1"];
						if($row['pass'] == $password1) {//パスワードが一致していたら
							$ediName = $row['name'];  //変数$ediNameに名前を格納
        		 			$ediComment = $row['comment']; //変数ediCommentにコメントを格納
         		 			$enum = $row['id']; //ファイル番号を変数$enumに格納
         		 			$edit_num =  $enum;
         		 		}
         		 }
         		}
        }
      }
      
if(isset($_POST["edited"]) && isset( $_POST["editing"]))  {//送信ボタンが押されたら
	$enum = $_POST["editing"]; //変数$enumに編集対象の番号を追加
    $edit_num =  $_POST["edit_num"];//変数#edit_numに編集対象の値を格納する
	$sql = 'SELECT * FROM 4_1';
	$results = $pdo -> query($sql);
		foreach($results as $row) {
			if ($row['id']  == $enum) {//もしファイル内の番号と編集対象番号が一緒なら
				$edit_num = $_POST["edit_num"];
				$id = $enum; 
				$ediName = $_POST["name"];  //変数$ediNameに名前を格納
        		$ediComment = $_POST["comment"]; //変数ediCommentにコメントを格納
         		$date = date('Y/m/d H:i:s');//dateの値を$dに]; //ファイル番号を変数$enumに格納
         		$pass = $_POST["password1"];
         		$edit_num =  $enum;//ファイル番号と編集対象番号は等しい。
         		$sql = "update 4_1 set name = '$ediName' , comment = '$ediComment', time = '$date' , pass = '$pass' where id = $id";
         		$result = $pdo->query($sql);
         	}
         }
       }
?>
         <form action="mission_4-1_Miyuki.php" method="post">
		<input type="text" name="name" placeholder = "名前" value = "<?php echo $ediName;?>"><br>
		<input type="text" name="comment" placeholder ="コメント" value = "<?php echo $ediComment;?>"><br>
		<input type = "password" name = "password" placeholder = "パスワード"><br/>
		<input type = "hidden" name = "editing" value ="<?php echo $enum;?>">
		<input type="submit" name = "edited" value="送信"><br/>
		</form> 
		<form action="mission_4-1_Miyuki.php" method="post">
		<input type = "text" name="edit_num" placeholder = "編集対象番号"><br/>
		<input type = "password" name = "password1" placeholder = "パスワード"><br/>
		<input type="submit" name = "edit_btn" value="送信">
		</form>
		<form action="mission_4-1_Miyuki.php" method="post">
		<input type="text" name="delete" placeholder = "削除対象番号"><br/>
		<input type = "password" name = "password2" placeholder = "パスワード"><br/>
		<input type="submit" value="送信">
		</form>
     </body>
</html>
<?php
$sql = 'SELECT * FROM 4_1 ORDER by id ASC';
	$results = $pdo -> query($sql);
	foreach($results as $row) {
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['time'].'<br>';
	}
?>