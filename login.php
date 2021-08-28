<?php
    include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
	
	//在Member資料表中搜尋帳號
    $sql_query = "SELECT * FROM `account` WHERE `aNo`= '".$_POST["account"]."'";
    $result = mysqli_query($db_link, $sql_query);
    $user = mysqli_fetch_array($result, MYSQLI_ASSOC);
	
	//如有符合的帳號
    if ($user) {
		//比對帳號密碼是否與資料表中帳密一致以及是否為會員帳號
        if ($_POST['account'] == $user['aNo'] && $_POST['passwd'] == $user['password'] && $user['category'] == 'M') {
			$_SESSION['aNo'] = $user['aNo'];
            $_SESSION['category'] = $user['category'];
			echo '<script>alert("登入成功!請點擊頁面連結進入會員模式。")</script>';
			echo '<meta http-equiv="refresh" content="0; url=homepage.php">';
			//echo '<a href="主頁.php" target="_parent" style="font-size: 100px; color:#fff ;f ont-family: sans-serif;">點此進入會員模式</a>';
		//比對帳號密碼是否與資料表中帳密一致以及是否為餐廳管理員帳號
        } else if($_POST['account'] == $user['aNo'] && $_POST['passwd'] == $user['password'] && $user['category'] == 'R'){
			$_SESSION['aNo'] = $user['aNo'];
            $_SESSION['category'] = $user['category'];
			$_SESSION['rId'] = $user['rId'];			
			echo '<script>alert("登入成功!請點擊頁面連結進入餐廳管理員模式。")</script>'; 
			echo '<meta http-equiv="refresh" content="0; url=homepage.php">';
        } else if($_POST['account'] == $user['aNo'] && $_POST['passwd'] == $user['password'] && $user['category'] == 'S'){
			$_SESSION['aNo'] = $user['aNo'];
            $_SESSION['category'] = $user['category'];
			echo '<script>alert("登入成功!請點擊頁面連結進入系統管理員模式。")</script>';
			echo '<meta http-equiv="refresh" content="0; url=homepage.php">';			
        } else {
			echo '<script>alert("帳號或密碼錯誤，請重新輸入。")</script>'; 
            echo '<meta http-equiv="refresh" content="0; url=會員登入.php">';
		}
    } else {
		echo '<script>alert("帳號或密碼錯誤，請重新輸入。")</script>'; 
        echo '<meta http-equiv="refresh" content="0; url=會員登入.php">';
    }   
?>