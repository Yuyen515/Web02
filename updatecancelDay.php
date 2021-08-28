<!--從homepage.php輸入要更改的最後取消期限後，會在這個檔案進行修改，修改完成後會再跳回homepage.php-->
<!DOCTYPE html>
<?php
    include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
	
	$sql = "UPDATE `restaurant` 
			SET `cancelDay`='".$_POST["cancelDay"]."' 
			WHERE `rId`='".$_SESSION['rId']."'";						
	mysqli_query($db_link, $sql);	
	
	echo '<script>alert("更新成功!")</script>'; 
	echo '<meta http-equiv="refresh" content="0; url=homepage.php">';
?>