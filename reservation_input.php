<!DOCTYPE html>
<?php
	include 'connMysql.php';	
	$con = mysqli_connect("localhost", "misvip", "mis888", "whattoeat"); 
	
	if (!$con) 
	{ 
		echo "連接MySQL失敗: " . mysqli_connect_error(); 
	} 
    session_start();

	mysqli_query($con,"INSERT INTO reservationrecord (rId, aNo, date, time, numOfpeople, needs) 
				VALUES ('".$_SESSION['rId']."', '".$_SESSION['aNo']."', '".$_POST['date']."', '".$_POST['time']."', '".$_POST['numOfpeople']."', '".$_POST['needs']."')");

	$lastID = mysqli_insert_id($con); 	
	
	//放入預約特殊餐點的資料
	$num = $_POST['mealvariety'];
	if($num > 0) {
		$i = 1;
		while($num >= $i) {	
			if($_POST['amount'][$i] != 0) {
				
				$sql_query = "SELECT * 
							FROM `specialmeal` 
							WHERE `smId` = '".$_POST['smId'][$i]."'";
				$result = mysqli_query($db_link, $sql_query); 
				$meal=mysqli_fetch_assoc($result);			
			
				$sql = "INSERT INTO `rmeal`(`rId`, `rrId`, `quantity`, `mealName`)
						VALUES ('".$_SESSION['rId']."', '$lastID', '".$_POST['amount'][$i]."', '".$meal['smName']."')";
				$result = mysqli_query($db_link, $sql);	
			}
				$i++;
		}
	}
	else {
		
	}
?>
<html lang="zh-Hant-TW">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>餐廳預約</title>
	</head>
	<body>
	<style>
	body {
	    background: url('http://thecodeplayer.com/uploads/media/gs.png');
		color: white;
	}
	</style>
	<h2>訂位成功!</h2>
	<p>預約成功!</br>您預約的時間為 <?php echo $_POST['date']?> <?php echo $_POST['time']?> <br>總共<?php echo $_POST['numOfpeople']?>位 </p>
	<!--有空再回頭寫這個
	<script>
			int i = 1;
			while($num >= i) {	
				document.write('訂購的餐點為 ');
			$sql_query = "INSERT INTO `rmeal` (`rId`, `rrId`, `smId`, `quantity`)
						VALUES ('".$_SESSION['rId']."', '$lastID', '".$_POST['smId'][$i]."', '".$_POST['amount'][$i]."')";
			$result = mysqli_query($db_link, $sql_query);
			$i++;	
	
	</script>
	-->
	</body>
</html>
<?php
	mysqli_close($con);
?>

