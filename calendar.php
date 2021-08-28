<!DOCTYPE html>
<?php 
    include("connMysql.php");
    session_start();
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
?>
<html lang="zh-Hant-TW">
  <head>
    <meta charset ="UTF-8">
	<title>日曆</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/calendar.css">
  </head>
  <body>
    <header class="header">
	  <h1 class="logo">
	    <a href="homepage.php">What to EAT</a>
	  </h1>
	  <nav class="global-nav">
	    <ul>
		  <li class="nav-item"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item active"><a href="calendar.php">CALENDAR</a></li>
		  <li class="nav-item"><a href="homepage.php#jumpToRestaurant">RESTAURANTS</a></li>
		  <li class="nav-item"><a href="#">FAVORITES</a></li>
		  <li class="nav-item"><a href="memberinfo.php">MEMBER INFO</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>
		</ul>
	  </nav>
	</header>
	
	<div id="calendar" class="calendar" style="float:left; display:inline; margin-left:350px;"></div>
    <script src="js/jquery.min.js"></script>
    <script src="js/calendar.js"></script>
    <div style="text-align:center;"></div>
	
	
	<div style="float:left; display:inline; margin-left:80px; margin-top: -30px;"><fieldset>
		<legend>預約項目</legend>
		<form id="calendarform" name="calendarform" method="post">
		<input type="hidden" id="test" name="test"/>
		</form>
		<script>
		function displayReservation(d){
			var t = d.getAttribute("data-id");
			var s = t.toString();
			var dateStr = s.substr(0, 4) + '-' + s.substr(4, 2) + '-' + s.substring(6);
			//將表單中id為test的變數賦值
			document.getElementById("test").value = dateStr;
			formSubmit();
		}
		function formSubmit(){
			//將表單送出
			document.getElementById("calendarform").submit();
		}
		/*不一定用得上*/
		function changingStr(fDate) { // 字符串轉換成日期
			var fullDate = fDate.split("-");   
			return new Date(fullDate[0], fullDate[1] - 1, fullDate[2]); 
		};
		
		</script>
		<?php
		if(isset($_POST['test'])){
			$dateStr = $_POST['test'];
			$clickDay = strtotime($dateStr); //點擊日期的時間戳記
			$thisDay = strtotime(date("Y-m-d"));
			$forbirth = date("m-d", strtotime($dateStr));
			if($result = mysqli_query($db_link, "SELECT * FROM `reservationrecord` AS RR NATURAL JOIN `restaurant` AS R WHERE `aNo`= '".$_SESSION["aNo"]."' AND R.rId = RR.rId AND `date`= '".$dateStr."'")) {				
				$num = 1;
				if($row_result=mysqli_fetch_assoc($result)) {
					$result_birth = mysqli_query($db_link, "SELECT * FROM `memberinfo` NATURAL JOIN `account` WHERE `aNo`= '".$_SESSION["aNo"]."' AND `birthdate` LIKE '%{$forbirth}'");					
					if($rowresult=mysqli_fetch_assoc($result_birth)) {
						echo "<tr>Happy Birthday！<br></br>";
					}
					
					do {
						
						$rrId = $row_result["rrId"];
						echo "<tr>No. $num</br>";
						echo "<td>預約餐廳：".$row_result["rName"]."</td></br>";
						echo "<td>預約日期：".$row_result["date"]."</td></br>";
						echo "<td>預約時間：".$row_result["time"]."</td></br>";
						echo "<td>預約人數：".$row_result["numOfpeople"]."</td></br>";
						echo "<td>特殊需求：".$row_result["needs"]."</td></br>";
						echo "<td>特殊餐點：</br>";
						$num++;
						$sql_meal = "SELECT * 
									FROM `rmeal` 
									WHERE `rrId` = '".$rrId."'";					
						$result_meal = mysqli_query($db_link, $sql_meal);  
						while($row_result_meal=mysqli_fetch_assoc($result_meal)) {
							echo "<td>".$row_result_meal["mealName"]."： ".$row_result_meal["quantity"]."份</td></br>";
						}
						echo "</br>";
						
						$c = ceil(($clickDay - $thisDay)/(3600*24)); //計算相差天數
						if($c > $row_result["cancelDay"]){
							echo "<td>此餐廳須於預約用餐日".$row_result["cancelDay"]."天前取消！</br>";
							?>
							<!--取消的表單-->
							<form action="cancelReservation.php" method="post" name="cancelReservation">
							<input type="hidden" name="rrId" value=<?php echo $row_result["rrId"]; ?>>
							<input type="submit" name="cancel" value="取消預約" />
							</form>
							</br>
							<?php
						}
						else if ($c > 0) {
						  	echo "<td>此餐廳須於預約用餐日".$row_result["cancelDay"]."天前取消！</br>";
						}
					} while ($row_result=mysqli_fetch_assoc($result));	
				}	
				else {
					echo "".$dateStr."";
					$result_birth = mysqli_query($db_link, "SELECT * FROM `memberinfo` NATURAL JOIN `account` WHERE `aNo`= '".$_SESSION["aNo"]."' AND `birthdate` LIKE '%{$forbirth}'");
					
					if($row_result=mysqli_fetch_assoc($result_birth)) {
						echo "<tr><br>Happy Birthday！</br>";
					}
					echo "<tr><br>無餐廳預約紀錄</br>";
					
				}
			}	
			
		}
		?>
	</fieldset></div> 
	
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
 