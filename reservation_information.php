<!DOCTYPE html>
<?php
    include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
?>
<html lang="zh-Hant-TW">
<head>
    <meta charset ="UTF-8">
	<title>訂單查詢及評分</title>
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
				<li class="nav-item"><a href="#jumpToUpdate">UPDATE</a></li>
				<li class="nav-item active"><a href="reservation_information.php">RESERVATION</a></li>
				<li class="nav-item"><a href="reservation_meal.php">RESERVATION MEAL</a></li>
				<li class="nav-item"><a href="logout.php">LOG OUT</a></li>
			</ul>
		</nav>
	</header>
    <form name="reservation_information" method="post">
		<fieldset>
			<legend>查詢會員預約日期</legend>
			<label for="date">日期：</label><br>
			<input type="date" name="date" required/><br><br>
			<label for="time">時間：</label><br>
			<input type="time" name="time"><br><br>
			<input type="submit" name="submit" value="查詢" /> 
	   </fieldset> 
   </form> 
   <fieldset>
     <legend>會員預約資訊</legend>
		<?php
		if(isset($_POST["submit"])) {
			$nowdate = date("Y-m-d");
			$input_date = $_POST["date"];
			//過去訂單
			if(strtotime($nowdate) > strtotime($input_date)){
				$sql = "SELECT * 
					FROM `reservationrecord` 
					NATURAL JOIN `memberinfo` 
					WHERE `date`= '".$_POST["date"]."'
					ORDER BY time";
				$result = mysqli_query($db_link, $sql);  
				$num = 1;
		    
				while($row_result=mysqli_fetch_assoc($result)){ 
					$rrId = $row_result["rrId"];
					echo "</br><tr>No. $num";
					echo "<td></br>會員電話：".$row_result["mPhone"]."</td></br>";
					echo "<td>預約日期：".$row_result["date"]."</td></br>";
					echo "<td>預約時間：".$row_result["time"]."</td></br>";
					echo "<td>預約人數：".$row_result["numOfpeople"]."</td></br>";
					echo "<td>特殊需求：".$row_result["needs"]."</td></br></br>";
					echo "<td>特殊餐點：</br>";
					$sql_meal = "SELECT * 
								FROM `rmeal` 
								WHERE `rrId` = '".$rrId."'";
					
					$result_meal = mysqli_query($db_link, $sql_meal);  
					while($row_result_meal=mysqli_fetch_assoc($result_meal)) {
						echo "<td>".$row_result_meal["mealName"]."： ".$row_result_meal["quantity"]."份</td></br>";
					}							
					echo "</tr></br>";
					if($row_result["rate"] == "尚未評分") {
						?>
						<form method="post" action="reservation_information.php"> 
							<label>請對該筆訂位進行評價：</label><br>
							<select name="rate">
								<option value=1>訂單完美達成</option>
								<option value=2>訂位會員並未出現（沒預訂餐點）</option>
								<option value=3>訂位會員並未出現（有預訂餐點）</option>
								<input type="hidden" name="aNo" value=<?php echo $row_result["aNo"]; ?>>
								<input type="hidden" name="rrId" value=<?php echo $row_result["rrId"]; ?>>
							</select>
							<input type="submit" name="rating" value="送出評分" /> 
						</form> 
						<?php
						echo "</br></br>";
					}
					else {
						?>
					    <p><b>已完成顧客的信用評價!</b></p>
						<?php
						echo "</br></br>";
					}
					$num++;
				}   
			} 
			//今天的訂單要以時間來判斷是否為過去的訂單
			else if(strtotime($nowdate) == strtotime($input_date)) {
				$sql = "SELECT * 
					FROM `reservationrecord` 
					NATURAL JOIN `memberinfo` 
					WHERE `date`= '".$_POST["date"]."' AND `time` < CURTIME()
					ORDER BY time";
				$result = mysqli_query($db_link, $sql);  
				$num = 1;
		    
				while($row_result=mysqli_fetch_assoc($result)){ 				
					$rrId = $row_result["rrId"];
					echo "</br><tr>No. $num";
					echo "<td></br>會員電話：".$row_result["mPhone"]."</td></br>";
					echo "<td>預約日期：".$row_result["date"]."</td></br>";
					echo "<td>預約時間：".$row_result["time"]."</td></br>";
					echo "<td>預約人數：".$row_result["numOfpeople"]."</td></br>";
					echo "<td>特殊需求：".$row_result["needs"]."</td></br></br>";
					echo "<td>特殊餐點：</br>";
					$sql_meal = "SELECT * 
								FROM `rmeal` 
								WHERE `rrId` = '".$rrId."'";
					
					$result_meal = mysqli_query($db_link, $sql_meal);  
					while($row_result_meal=mysqli_fetch_assoc($result_meal)) {
						echo "<td>".$row_result_meal["mealName"]."： ".$row_result_meal["quantity"]."份</td></br>";
					}							
					echo "</tr></br>";
					if($row_result["rate"] == "尚未評分") {
						?>
						<form method="post" action="reservation_information.php"> 
							<label>請對該筆訂位進行評價：</label><br>
							<select name="rate">
								<option value=1>訂單完美達成</option>
								<option value=2>訂位會員並未出現（沒預訂餐點）</option>
								<option value=3>訂位會員並未出現（有預訂餐點）</option>
								<input type="hidden" name="aNo" value=<?php echo $row_result["aNo"]; ?>>
								<input type="hidden" name="rrId" value=<?php echo $row_result["rrId"]; ?>>
							</select>
							<input type="submit" name="rating" value="送出評分" /> 
						</form> 
						<?php
						echo "</br></br>";
					}
					else {
						?>
					    <p><b>已完成顧客的信用評價!</b></p>
						<?php
						echo "</br></br>";
					}
					$num++;
				}   
			}
			else {
				if(!$_POST["time"]) {
					$sql = "SELECT * 
						FROM `reservationrecord` 
						NATURAL JOIN `memberinfo` 
						WHERE `date`= '".$_POST["date"]."'
						ORDER BY time";
					$result = mysqli_query($db_link, $sql);  
					$num = 1;		    
					while($row_result=mysqli_fetch_assoc($result)){ 
						$rrId = $row_result["rrId"];
						echo "</br><tr>No. $num";
						echo "<td></br>會員電話：".$row_result["mPhone"]."</td></br>";
						echo "<td>預約日期：".$row_result["date"]."</td></br>";
						echo "<td>預約時間：".$row_result["time"]."</td></br>";
						echo "<td>預約人數：".$row_result["numOfpeople"]."</td></br>";
						echo "<td>特殊需求：".$row_result["needs"]."</td></br></br>";
						echo "<td>特殊餐點：</br>";
						$sql_meal = "SELECT * 
								FROM `rmeal` 
								WHERE `rrId` = '".$rrId."'";
					
						$result_meal = mysqli_query($db_link, $sql_meal);  
						while($row_result_meal=mysqli_fetch_assoc($result_meal)) {
							echo "<td>".$row_result_meal["mealName"]."： ".$row_result_meal["quantity"]."份</td></br>";
						}							
						echo "</tr></br></br>";
						$num++;
						
					}
				}
				//如果有選時間
				else {
					$sql = "SELECT * 
						FROM `reservationrecord` 
						NATURAL JOIN `memberinfo` 
						WHERE `date`= '".$_POST["date"]."' AND `time` > '".$_POST["time"]."'
						ORDER BY time";
					$result = mysqli_query($db_link, $sql);  
					$num = 1;
				
					while($row_result=mysqli_fetch_assoc($result)){ 
						$rrId = $row_result["rrId"];
						echo "<tr>No. $num";
						echo "<td></br>會員電話：".$row_result["mPhone"]."</td></br>";
						echo "<td>預約日期：".$row_result["date"]."</td></br>";
						echo "<td>預約時間：".$row_result["time"]."</td></br>";
						echo "<td>預約人數：".$row_result["numOfpeople"]."</td></br>";
						echo "<td>特殊需求：".$row_result["needs"]."</td></br></br>";
						echo "<td>特殊餐點：</br>";
						
						$sql_meal = "SELECT * 
							FROM `rmeal` 
							NATURAL JOIN `specialmeal` 
							WHERE `rrId` = '".$rrId."'";					
						$result_meal = mysqli_query($db_link, $sql_meal);  
						while($row_result_meal=mysqli_fetch_assoc($result_meal)) {
							echo "<td>".$row_result_meal["mealName"]."： ".$row_result_meal["quantity"]."份</td></br>";
						}
						
						echo "</tr></br></br></br>";
						$num++;
					}
				}
			}
		}
		
		
		//這是送出評分表單後的php
		if(isset($_POST["rating"])) {
			if($_POST["rate"] == 2) {
				$sql = "UPDATE `memberinfo` 
						SET `creditScore`= creditScore - 5
						WHERE `aNo`='".$_POST['aNo']."'";
				mysqli_query($db_link, $sql);
				$sql = "UPDATE `reservationrecord` 
						SET `rate`= '已評分'
						WHERE `rrId`='".$_POST['rrId']."'";
				mysqli_query($db_link, $sql);
				echo "評分完成！";
				echo '<meta http-equiv="refresh" content="0; url=pasteorders.php">';
				
			}
			else if($_POST["rate"] == 3) {
				$sql = "UPDATE `memberinfo` 
						SET `creditScore`= creditScore - 20
						WHERE `aNo`='".$_POST['aNo']."'";
				mysqli_query($db_link, $sql);
				$sql = "UPDATE `reservationrecord` 
						SET `rate`= '已評分'
						WHERE `rrId`='".$_POST['rrId']."'";
				mysqli_query($db_link, $sql);
				echo "評分完成！";
			}
			else {
				$sql = "UPDATE `reservationrecord` 
						SET `rate`= '已評分'
						WHERE `rrId`='".$_POST['rrId']."'";
				mysqli_query($db_link, $sql);
				echo "評分完成！";
			}
		}
	   ?>
   </fieldset> 
   <br>
   <br>
   <br>
   <br>
   <br>
   
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


