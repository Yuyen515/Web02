<!DOCTYPE html>
<?php
    include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
?>
<html lang="zh-Hant-TW">
<head>
    <meta charset ="UTF-8">
	<title>預約餐點更改</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">	
	<link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
	    <h1 class="logo">
			<a href="homepage.php">What to EAT</a>
	    </h1>
	    <nav class="global-nav">
			<ul>
				<li class="nav-item"><a href="homepage.php">HOME</a></li>
				<li class="nav-item"><a href="homepage.php#jumpToUpdate">UPDATE</a></li>
				<li class="nav-item"><a href="reservation_information.php">RESERVATION</a></li>
				<li class="nav-item active"><a href="reservation_meal.php">RESERVATION MEAL</a></li>
				<li class="nav-item"><a href="logout.php">LOG OUT</a></li>
			</ul>
		</nav>
	</header>
	
	<form name="reservation_meal" method="post" action="reservation_meal.php">
		<fieldset>
			<legend>餐廳可供預約餐點</legend>
			<?php
				//如果表單被送出
				if(isset($_POST["submit"])) {
					
					//檢查有沒有填寫新增餐點的部分
					if(($_POST["addmname"] != NULL) && ($_POST["addmprice"] != NULL)) {
						$sql_query = "INSERT INTO `specialmeal` (`rId` ,`smName` ,`price`)
									VALUES ('".$_SESSION['rId']."', '".$_POST['addmname']."', '".$_POST['addmprice']."')";
						$result = mysqli_query($db_link, $sql_query);
					}
					else if(($_POST["addmname"] != NULL) || ($_POST["addmprice"] != NULL)) {
						echo "<tr></br>新增餐點請完整填寫餐點名稱與價格</br></tr>";
					}
										
					//檢查有沒有填寫刪除餐點的部分									
					if(($_POST["deletemeal"] != NULL) && ($_POST["deletemeal"] <= $_POST["mealvariety"])) {						
						$i = $_POST['deletemeal'];												
						$sql_query = "DELETE FROM `specialmeal` WHERE `smId` = '".$_POST['smId'][$i]."'";
						$result = mysqli_query($db_link, $sql_query);
					}
					
					//檢查有沒有填寫修改餐點的部分
					if(($_POST["updatemeal"] != NULL) && ($_POST["updatemname"] != NULL) && ($_POST["updatemeal"] <= $_POST["mealvariety"])) {
						$i = $_POST['updatemeal'];
						if($_POST["updatemprice"] != NULL) {
							$sql = "UPDATE `specialmeal` 
									SET `smName`='".$_POST["updatemname"]."', `price`='".$_POST["updatemprice"]."' 
									WHERE `smId`='".$_POST['smId'][$i]."'";						
							mysqli_query($db_link, $sql);			
						}
						else {
							$sql = "UPDATE `specialmeal` 
									SET `smName`='".$_POST["updatemname"]."'
									WHERE `smId`='".$_POST['smId'][$i]."'";						
							mysqli_query($db_link, $sql);
						}
					}
					else if(($_POST["updatemeal"] != NULL) && ($_POST["updatemprice"]!= NULL) && ($_POST["updatemeal"] <= $_POST["mealvariety"])) {
						$i = $_POST['updatemeal'];
						if($_POST["updatemname"] != NULL) {
							$sql = "UPDATE `specialmeal` 
									SET `smName`='".$_POST["updatemname"]."', `price`='".$_POST["updatemprice"]."' 
									WHERE `smId`='".$_POST['smId'][$i]."'";						
							mysqli_query($db_link, $sql);
						}
						else {
							$sql = "UPDATE `specialmeal` 
									SET `price`='".$_POST["updatemprice"]."'
									WHERE `smId`='".$_POST['smId'][$i]."'";						
							mysqli_query($db_link, $sql);
						}
					}
				}	
			?>	
			
			<?php 
				$sql_query = "SELECT * FROM `specialmeal` WHERE `rId`= '".$_SESSION["rId"]."'";
				$smeal = mysqli_query($db_link, $sql_query);
				$num = 1;
				
				if(!mysqli_fetch_assoc($smeal)) {
					echo "<tr></br>本餐廳無餐點須提前預約</br></br></br></tr>";
					$num--;
				}
				else {
					$smeal = mysqli_query($db_link, $sql_query);
	
					while($row_result = mysqli_fetch_assoc($smeal)){ 
						echo "<tr>$num. ".$row_result["smName"]."   $".$row_result["price"]."<br>"; 
						?>
						<input type="hidden" name="smId[<?php echo $num; ?>]" value=<?php echo $row_result["smId"]; ?>>
						<?php
						$num++;
					}
					$num--;
					echo "</br>";
					?>
					
					<label>新增餐點：</label><br>
					<input type="text" name="addmname" placeholder="餐點名稱">
					<input type="text" name="addmprice" placeholder="餐點價格">
					<br><label style="margin-top: 15px;">刪除餐點：</label><br>
					<input type="text" name="deletemeal" placeholder="餐點編號">
					<br><label style="margin-top: 15px;">修改餐點：</label><br>
					<input type="text" name="updatemeal" placeholder="餐點編號">
					<input type="text" name="updatemname" placeholder="餐點名稱">
					<input type="text" name="updatemprice" placeholder="餐點價格">
					<?php
				}			
			?>
			<input type="hidden" name="mealvariety" value=<?php echo $num; ?>>
			<br><input style="margin-top: 12px;" type="submit" name="submit" value="更新" /> 
			
	    </fieldset> 
	</form> 
	
	
	<footer class="footer">
	  <ul class="horizontal-list">
	    <li class="horizontal-item"><a href="#">ABOUT ME</a></li>
		<li class="horizontal-item"><a href="#">SITE MAP</a></li>
		<li class="horizontal-item"><a href="#">SNS</a></li>
		<li class="horizontal-item"><a href="#">CONTACT</a></li>
	  </ul>
	<p class="copyright">Copyright © 2021What To EAT</p>
	</footer>
</body>
</html>