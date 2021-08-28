<?php
	include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
	
		# 確認帳號與密碼規則
		# find duplicate account 1. SELECT 2. if exists
		# 以aNo做為檢測帳號是否存在的依據
        $sql_query = "SELECT * FROM `account` WHERE `aNo`= '".$_POST["account"]."'";
        $result = mysqli_query($db_link, $sql_query);
        $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
        if ($user) {
			echo '<script>alert("此用戶已存在，請重新註冊其他帳號。")</script>'; 
			echo '<meta http-equiv="refresh" content="0; url=會員登入.php">';
        } else {
			if($_POST["account"] == '' || $_POST['passwd'] == '' || $_POST['mPhone'] == '' || $_POST['name'] == '') {
				echo '<script>alert("請填寫所有欄位。")</script>'; 
				echo '<meta http-equiv="refresh" content="0; url=會員登入.php">';
	        # password == verifyPass 檢測第二次輸入密碼是否與第一次相同
	        } else if($_POST['verifyPass'] == $_POST['passwd']){
                # 寫進資料庫
				# pId的問題怎麼解決呢?????????????
				$sql_query = "INSERT INTO `account` (`aNo` ,`password` ,`category`)
	            			  VALUES ('".$_POST['account']."', '".$_POST['passwd']."', 'M')";
		        $result = mysqli_query($db_link, $sql_query);
				
				$sql_query = "SELECT * FROM `memberinfo`";
				$result = mysqli_query($db_link, $sql_query);
				$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
				$sql_query = "INSERT INTO `memberinfo` (`aNo`, `mName` ,`mPhone`)
	            			  VALUES ('".$_POST['account']."', '".$_POST['name']."', '".$_POST['mPhone']."')";
				$result = mysqli_query($db_link, $sql_query);
				
				echo '<script>alert("註冊成功!前往登入頁面。")</script>'; 
		        echo '<a href="會員登入.php"></a>';
				echo '<meta http-equiv="refresh" content="0; url=會員登入.php">';
	        } else {
				echo '<script>alert("密碼輸入與確認密碼不同，請重新註冊。")</script>';
				echo '<meta http-equiv="refresh" content="0; url=會員登入.php">';
	        }
	    }
?>
