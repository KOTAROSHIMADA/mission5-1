<html>
<html lang= "ja">
<head>
   <meta charset = "utf-8">
</head>
<body>

<?php
//ここはデータベースに接続するために必要///
//
//DB接続設定
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//4-1///////////////////////////////
//4-2//////////////////////////////////////
//テーブルを定義、入力の枠はid,name,comment,dateを定義する必要がある．//
/////
$sql = "CREATE TABLE IF NOT EXISTS wear"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT,"
. "datetime timestamp"
. ");";
$stmt = $pdo -> query($sql);
//4-5///////
//テーブル内にデータを入力する欄//編集も
if(!empty($_POST["name"])&&!empty($_POST["comment"])&&!empty($_POST["pass1"])){
	$pass1=($_POST["pass1"]);
	if($pass1 == "pass1"){
		$name= ($_POST['name']);
		$comment = ($_POST['comment']);
		$datetime = date('Y/m/d H:i:s');
		if(!empty($_POST["editconfirm"])){//編集
			$id = $_POST["editconfirm"];
			$sql = "UPDATE wear set name=:name,comment=:comment,datetime=:datetime WHERE id=:id";
			$stmt = $pdo ->prepare($sql);
			$stmt -> bindParam(':id', $id, PDO::PARAM_INT);
			$stmt -> bindParam(':name', $name, PDO::PARAM_STR);
			$stmt -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt -> bindParam(':datetime', $datetime, PDO::PARAM_STR);
			$stmt -> execute();//編集の実行            
		}else{//新規投稿
			$sql = $pdo -> prepare("INSERT INTO wear (name, comment, datetime) VALUES (:name, :comment, :datetime)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':datetime', $datetime, PDO::PARAM_STR);
			$sql -> execute();
			}

	}
}

////削除フォーム////////////////////////////////
if(!empty($_POST['delete'])&&!empty($_POST["pass2"])){
	$pass2=($_POST["pass2"]);
	if($pass2 == "pass2"){
		$id = $_POST['delete'];
		$sql = 'delete from wear where id=:id';
		$stmt = $pdo -> prepare($sql);
		$stmt -> bindParam(':id',$id, PDO ::PARAM_INT);
		$stmt -> execute();
	}
}

//4-7//
//編集欄///////////////////////
if(!empty($_POST['edit'])&&!empty($_POST["pass3"])){
	$pass3=($_POST["pass3"]);
	if($pass3 == "pass3"){
		$edit=($_POST["edit"]) ;

	}
}
?>
<!--名前の入力フォーム-->
<form method= "post" action="mission5-1.php">
     <p>
         入力フォーム<br>
     </p>
 <input type="text" name="name" placeholder="名前" 
 value="<?php if(isset($edit)){
	 $id = $edit;
	 $sql = 'SELECT * FROM wear WHERE id =:id';
	 $stmt = $pdo ->prepare($sql);
	 $stmt ->bindParam(':id', $id, PDO::PARAM_INT);
	 $stmt ->execute();
	 $results = $stmt->fetchAll(); 
	foreach ($results as $row){
		echo $row['name'];
    }

 }?>"><br>
<!--コメントの入力フォーム-->
 <input type="text" name="comment" placeholder="コメント"
  value="<?php if(isset($edit)){
	  	   $id = $edit;
		   $sql = 'SELECT * FROM wear WHERE id =:id';
		   $stmt = $pdo ->prepare($sql);
		   $stmt ->bindParam(':id', $id, PDO::PARAM_INT);
		   $stmt ->execute();
		   $results = $stmt->fetchAll(); 
	foreach ($results as $row){
		echo $row['comment'];
    }
	  
  }?>"><br>
 <input type="password" name="pass1" placeholder="パスワード"><br>
  <input type="submit" value="送信ボタン">

<p>
    削除フォーム<br>
</p> 
 <input type="text" name="delete" placeholder="削除番号"><br> 
 <input type="password" name="pass2" placeholder="パスワード"><br>
 <input type="submit" value="削除ボタン">

 <p>
     編集フォーム<br>
 </p>
 <input type="text" name="edit"     placeholder="編集番号"><br>
 <input type="password" name="pass3" placeholder="パスワード"><br>
 <input type="submit" value="編集ボタン">

<!--編集番号を次のフォームに移す -->
    <input type="hidden" name="editconfirm"     placeholder="確認用" value="<?php if(isset($edit)){echo $edit;}?>"><br>


 </form>
<?php
 //4-6///////
//中身に何が入っているのかを確認///
$sql = 'SELECT * FROM wear';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		//$rowの中にはテーブルのカラム名が入る
		echo $row['id'].',';
		echo $row['name'].',';
		echo $row['comment'].',';
		echo $row['datetime'].'<br>';
	echo "<hr>";
	}

?>
</body>
</html>
