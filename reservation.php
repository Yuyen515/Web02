<!DOCTYPE html>
<?php
	include 'connMysql.php';	
	if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
?>
<html lang="zh-Hant-TW">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<title>餐廳預約</title>
		<link rel="stylesheet" media="screen" href="css/reservation.css">	
	</head>
	<body>
		<div style="display:none">
			<a href="https://mathiasbynens.be/demo/jquery-size" target="_blank" data-mce-href="http://mathiasbynens.be/demo/jquery-size"></a>
		</div>
			<form id="msform" action="reservation_input.php" method="post">
			<!-- progressbar -->
				<ul id="progressbar">
					<li class="active">Reservation</li>
					<li class="">SPECIAL DISH</li>
					<li>OTHER NEEDS</li>
				</ul>
				<!-- fieldsets -->
				<fieldset style="opacity: 1; transform: scale(1); display: block;">
					<h2 class="fs-title">Reservation</h2>
					<h3 class="fs-subtitle">This is step 1</h3>
					<input type="date" name="date" placeholder="Date" required/>
					<input type="time" name="time" placeholder="Time" required/>
					<input type="text" name="numOfpeople" placeholder="Number of People" required/>
					<input type="button" name="next" class="next action-button" value="Next">
				</fieldset>
				<fieldset style="display: none; left: 50%; opacity: 0;">
					<h2 class="fs-title">SPECIAL DISH</h2>
					<h3 class="fs-subtitle">Some meals must be made in advance,please make an appointment.</h3>
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
								echo "<tr>$num. ".$row_result["smName"]."   $".$row_result["price"].""; 
								?>
										
								</br>
								<select name="amount[<?php echo $num; ?>]"></br>
									<option value=0>0份</option>
									<option value=1>1份</option>
									<option value=2>2份</option>
									<option value=3>3份</option>
									<option value=4>4份</option>
									<option value=5>5份</option>
									<option value=6>6份</option>
									<option value=7>7份</option>
									<option value=8>8份</option>
								</select>
								</br>	
								<input type="hidden" name="smId[<?php echo $num; ?>]" value=<?php echo $row_result["smId"]; ?>>
								<?php
								$num++;
							}
							$num--;
						}
					?>
					
					<input type="hidden" name="mealvariety" value=<?php echo $num; ?>>
					<input type="button" name="previous" class="previous action-button" value="Previous">
					<input type="button" name="next" class="next action-button" value="Next">
				</fieldset>
				<fieldset>
					<h2 class="fs-title">OTHER NEEDS</h2>
					<h3 class="fs-subtitle">Do you have any other needs?</h3>
					<input type="text" name="needs" placeholder="Other need...">
					<input type="button" name="previous" class="previous action-button" value="Previous">
					<input type="submit" name="submit" class="action-button" value="Submit">
				</fieldset>
			</form>
			<script src="js/jquery-1.9.1.min.js" type="text/javascript"></script>
			<script src="js/jquery.easing.min.js" type="text/javascript"></script>
			<script src="js/jQuery.time.js" type="text/javascript"></script>
			<br><br><br><br><br><br><br><br><br><br>
			<br><br><br><br><br><br><br><br><br><br>	
	</body>
</html>