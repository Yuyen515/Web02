<?php
	include 'connMysql.php';
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    $result = mysqli_query($db_link, "SELECT * FROM restaurant WHERE rId=" . $_GET['rId'] . "");
    $row= mysqli_fetch_array($result);	

	session_start();
	if (isset($_SESSION['aNo'])) {
		$sql_query = "SELECT * FROM `account` WHERE `aNo`= '".$_SESSION["aNo"]."'";
		$loginResult = mysqli_query($db_link, $sql_query);
		$user = mysqli_fetch_array($loginResult, MYSQLI_ASSOC);
		$sql_query = "SELECT rId FROM `account` WHERE `aNo`= '".$_SESSION["aNo"]."'";

		// 用aNo取出rId
		$result = mysqli_query($db_link, $sql_query);
		$row = mysqli_fetch_array($result);
		$rId = $row['rId'];
	} else {
		$user = NULL;
	}
	//取得當前頁面的rId，並以SESSION儲存->進行餐廳預約時會需要使用
    $result = mysqli_query($db_link, "SELECT * FROM restaurant WHERE rId=" . $_GET['rId'] . "");
    $row = mysqli_fetch_array($result);
	$_SESSION['rId'] = $_GET['rId']
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
<head>
    <meta charset ="UTF-8">
	<title>餐廳介紹</title>
	<link rel="stylesheet" href="css/restaurantStyle.css">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<body>
    <header class="header">
	  <h1 class="logo">
	    <a href="homepage.php">What to EAT</a>
	  </h1>
	  <nav class="global-nav">
	    <ul>
		  <!--會員登入時介面-->
		<?php if($user){
				if($user['category'] == 'M'){ ?>	  
		  <li class="nav-item"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item"><a href="calendar.php">CALENDAR</a></li>
		  <li class="nav-item"><a href="homepage.php#jumpToRestaurant">RESTAURANTS</a></li>
		  <li class="nav-item"><a href="#">FAVORITES</a></li>
		  <li class="nav-item"><a href="memberinfo.php">MEMBER INFO</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>

		  <!--餐廳管理員登入時介面-->		
		  <?php }
		  else if($user['category'] == 'R') { ?>
		  <li class="nav-item"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item"><a href="homepage.php#jumpToUpdate">UPDATE</a></li>
		  <li class="nav-item"><a href="reservation_information.php">RESERVATION</a></li>
		  <li class="nav-item"><a href="reservation_meal.php">RESERVATION MEAL</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>

		  <!-- 系統管理員登入時介面 -->
		  <?php }
		  else if($user['category'] == 'S') { ?>
		  <li class="nav-item"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item"><a href="homepage.php#jumpToManage">MANAGE</a></li>
		  <li class="nav-item"><a href="創建餐廳帳號.php">CREAT ACCT</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>

		  <!--訪客介面-->
		  <?php }
		}
		if($user == NULL) {?> 
		  <li class="nav-item"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item"><a href="homepage.php#jumpToRestaurant">RESTAURANTS</a></li>
		  <li class="nav-item"><a href="#">FAVORITES</a></li>
		  <li class="nav-item"><a href="會員登入.php">LOG IN</a></li> 
		<?php 
		} ?>
		</ul>
	  </nav>
	</header>

	<?php 
	// 餐廳管理員更新資訊
	if($user != NULL && $user['category'] == 'R' && $_GET['rId'] == $rId) {?>
	<form action="update.php" method="post" enctype="multipart/form-data">
        
    <section class="content">
		<div class="contentImg">
			<?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $row['photo'] ).'"/ class="image">'; ?><br><br>
			<input type="file" name="photo">
		</div>
		<div class="info">
			<h1>餐廳名稱: </h1>
			<!-- this does not show on the screen -->
			<input type="hidden" name="rId" value="<?php echo $row['rId']; ?>">
        	<input type="text" name="rName" class="txtField" value="<?php echo $row['rName']; ?>">
			<h4>電話: </h4>
        	<input type="text" name="rPhone" class="txtField" value="<?php echo $row['rPhone']; ?>">
			<h4>地址: </h4>
			<input type="text" name="rAddress" class="txtField" value="<?php echo $row['rAddress']; ?>">
			<h4>均消: </h4>
			<input type="text" name="bottomPrice" class="txtField" value="<?php echo $row['bottomPrice']; ?>">
			<p>餐廳介紹: </p>
			<textarea name="rDesc" rows="10" cols="50"><?php echo $row['rDesc']; ?></textarea><br>

			<input type="submit" name="submit" value="更新" class="buttom">
		</div>
	</section>
	</form>

	<!-- 會員瀏覽餐廳 -->
	<?php }else {?>
		<section class="content">
		<?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $row['photo'] ).'"/ class="image">'; ?>
		<div class="info">
			<h2 style="margin-top: 30px;"><strong><?php echo $row["rName"]; ?></strong></h1>
			<h3 style="margin-top: 5px;">評分: <?php echo $row["rate"]; ?></h3>
			<!-- <div class="container">
			<div class="row"> -->
			<!-- 評分的星星 -->
			<?php if(isset($_SESSION["category"]) && $_SESSION["category"] == 'M') {?>
				<form action="rate.php" method="post">
				<div class="rateyo" id= "rating" data-rateyo-rating="
				<?php 
					$sql = "SELECT rate FROM `rating` WHERE rId = '" . $_SESSION["rId"] . "' AND aNo = '" . $_SESSION["aNo"] . "'";
					$rateResult = mysqli_query($db_link, $sql);
					$rateRow = mysqli_fetch_array($rateResult);
        			if(mysqli_num_rows($rateResult) > 0) { // 評過了
						echo $rateRow['rate'];
        			}else { // 沒評過
						?>5<?php ;
					} 
				?>"
					data-rateyo-num-stars="5"
					data-rateyo-score="3"
					style="weight: 160px; height:40px;">		
				</div>

				<h4 class='result' style="margin-top:0px;">rating: <?php 
				if(mysqli_num_rows($rateResult) > 0) { // 評過了
					echo $rateRow['rate'];
				}else { // 沒評過
					echo '5';
				}?></h4>
				<input type="hidden" name="rating">
				<input type="submit" name="add" class="button1" value="評分" style="weight:20px;">

				</form><?php }?>
				
<!-- </div>
</div> -->
			<h4 style="margin-top: 25px;">電話: <?php echo $row["rPhone"]; ?></h4>
			<h4 style="margin-top: 0;">地址: <?php echo $row["rAddress"]; ?></h4>
			<h4 style="margin-top: 0;">均消: <?php echo $row["bottomPrice"]; ?></h4>
			<p>餐廳介紹: <br><?php echo $row["rDesc"]; ?></p>
			<!--匯入googlemap的部分-->
			<a class="button a globalLoginBtn1">GOOGLE MAP</a>
			<div class="modal fade" id="loginModal1" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
				<div class="modal-dialog modal-sm" style="width:540px;">
					<!--跳出表單的叉叉-->
					<button type="button" class="close" data-dismiss="modal"><span style="color: #fff;"aria-hidden="true">×</span><span class="sr-only">Close</span></button>                                             
					<iframe src="<?php echo $row["map"]; ?>" width="540" height="480">
					</iframe>
					<script type="text/javascript" src="js/jquery2.2.2.min.js"></script>
					<script type="text/javascript" src="js/bootstrap.min.js"></script>
					<script type="text/javascript" src="js/common1.js"></script>
				</div>			
			</div>

			<!--餐廳預約的部分-->
			<?php
				//如果有登入，且帳號種類為一般會員，可以看到立即預約的按鈕
				if(isset($_SESSION["category"]) && $_SESSION["category"] == 'M') {
					$sql = "SELECT * 
						FROM `account` 
						NATURAL JOIN `memberinfo` 
						WHERE `aNo`= '".$_SESSION["aNo"]."'";
					$result = mysqli_query($db_link, $sql); 
					$row_result=mysqli_fetch_assoc($result);
					$sql = "SELECT * 
						FROM `restaurant` 
						WHERE `rId`= '".$_GET["rId"]."'";
					$result = mysqli_query($db_link, $sql); 
					$r_result=mysqli_fetch_assoc($result);

					//如果會員信用分數小於等於50，會看不到立即預約->會員信用分數太低，所以不給預約
					if($row_result["creditScore"] > $r_result["reservation_creditScore"]) {
					  ?><a class="button a globalLoginBtn">立即預約</a>
						<div class="modal fade" id="loginModal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" style="display: none;">
							<div class="modal-dialog modal-sm" style="width:540px;">
								<!--跳出表單的叉叉-->
								<button type="button" id="login_close" class="close" data-dismiss="modal"><span style="color: #fff;" aria-hidden="true">×</span><span class="sr-only">Close</span></button>                                             
								<iframe id="iFrame" name="iFrame" src="reservation.php" style="width:540px; height:600px;">
								</iframe>
								<script type="text/javascript" src="js/jquery2.2.2.min.js"></script>
								<script type="text/javascript" src="js/bootstrap.min.js"></script>
								<script type="text/javascript" src="js/common.js"></script>
							</div>			
						</div><?php
					}
					
				}
				//如果為訪客瀏覽網頁，立即預約的按鈕會提醒其需先登入才能預約，並將頁面轉至會員登入
				else if(isset($_SESSION["category"]) == FALSE) {?>
					<a onclick="click1()" href="會員登入.php" class="button a">立即預約</a><?php
				}
			?>
			<script>
				function click1() {
					alert("請先登入會員!");
				}
			</script>
		</div>
		</section>
	<?php } ?>

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

	<!-- 評分星星的jquery -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/rateYo/2.3.2/jquery.rateyo.min.js"></script>

	<script>
	$(function () {
        $(".rateyo").rateYo().on("rateyo.change", function (e, data) {
            var rating = data.rating;
            $(this).parent().find('.score').text('score :'+ $(this).attr('data-rateyo-score'));
            $(this).parent().find('.result').text('rating :'+ rating);
            $(this).parent().find('input[name=rating]').val(rating); //add rating value to input field
        });
    });
	</script>
</html>