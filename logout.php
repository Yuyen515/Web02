<?php
	session_start();
	include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
	unset($_SESSION['aNo']);
	unset($_SESSION['category']);
	
	header("Location: homepage.php");
?>