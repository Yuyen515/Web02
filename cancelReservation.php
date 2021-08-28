<!DOCTYPE html>
<?php
    include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
	
	$rrId = $_POST['rrId'];
	$delete_query = "DELETE FROM `rmeal` 
					WHERE `rrId` = '".$_POST["rrId"]."'";
	$result = mysqli_query($db_link, $delete_query); 
	
	$delete_query = "DELETE FROM `reservationrecord` 
					WHERE `rrId`= '".$_POST["rrId"]."'";	
	$result = mysqli_query($db_link, $delete_query); 
	
	echo '<script>alert("取消成功！")</script>'; 
    echo '<meta http-equiv="refresh" content="0; url=calendar.php">';
?>