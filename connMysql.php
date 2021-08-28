<?php 
	//資料庫主機設定
	$db_host = "localhost";
	$db_username = "misvip";
	$db_password = "mis888";
	//登入後要使用的資料庫
	$db_name = 'whattoeat';

	//連線伺服器
	$db_link = mysqli_connect($db_host, $db_username, $db_password, $db_name);
	if ($db_link)
	{
	//若傳回正值，就代表已經連線
	//設定連線編碼為UTF-8
	//mysqli_query(資料庫連線, "語法內容") 為執行sql語法的函式
	mysqli_query($db_link, "SET NAMES utf8");
	}
	else
	{
	//否則就代表連線失敗 mysqli_connect_error() 是顯示連線錯誤訊息
	echo '無法連線mysql資料庫 :<br/>' . mysqli_connect_error();
	}
?>

