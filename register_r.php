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
			echo '<script>alert("此用戶已存在，請重新創建其他帳號。")</script>'; 
			echo '<meta http-equiv="refresh" content="0; url=創建餐廳帳號.php">';
        } else {
			if($_POST["account"] == '' || $_POST['passwd'] == '') {
				echo '<script>alert("請填寫所有欄位。")</script>'; 
				echo '<meta http-equiv="refresh" content="0; url=創建餐廳帳號.php">';
	        # password == verifyPass 檢測第二次輸入密碼是否與第一次相同
	        } else if($_POST['verifyPass'] == $_POST['passwd']){
                # 取出最後一個rId
				$sql_query = "SELECT MAX(rId) FROM `restaurant`";
				$result = mysqli_query($db_link, $sql_query);
				$row = mysqli_fetch_array($result);
				$rId = $row["MAX(rId)"] + 1;
				# 餐廳寫進資料庫
				$sql_query = "INSERT INTO `restaurant` (`rId`, `rName`) VALUES ('$rId', 'None')";
				$result = mysqli_query($db_link, $sql_query);

				# 餐廳帳號寫進資料庫
				$sql_query = "INSERT INTO `account` (`aNo` ,`password` ,`category`, `rId`) VALUES ('".$_POST['account']."', '".$_POST['passwd']."', 'R', '$rId');";
		        $result = mysqli_query($db_link, $sql_query);
				
				$sql_query = "SELECT * FROM `restaurant`";
				$result = mysqli_query($db_link, $sql_query);
				$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
				
				echo '<script>alert("創建成功!")</script>'; 
		        echo '<a href="創建餐廳帳號.php"></a>';
				echo '<meta http-equiv="refresh" content="0; url=創建餐廳帳號.php">';
	        } else {
				echo '<script>alert("密碼輸入與確認密碼不同，請重新創建。")</script>';
				echo '<meta http-equiv="refresh" content="0; url=創建餐廳帳號.php">';
	        }
	    }
?>