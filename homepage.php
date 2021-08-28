<?php
	include("connMysql.php");
	
	if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();
	
	if (isset($_SESSION['aNo'])) {
		$sql_query = "SELECT * FROM `account` WHERE `aNo`= '".$_SESSION["aNo"]."'";
		$result = mysqli_query($db_link, $sql_query);
		$user = mysqli_fetch_array($result, MYSQLI_ASSOC);
	} else {
		$user = NULL;
	}
?>

<!DOCTYPE html>
<html lang="zh-Hant-TW">
  <head>
    <meta charset ="UTF-8">
	<title>What to EAT</title>
	<link rel="stylesheet" href="css/reset.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="css/system.css">
	<script>
		// 愛心按鈕
		// $(document).ready(function(){
		// 	$('.heartContent').click(function(){
		// 		$('.heartContent').toggleClass("heart-active")
		// 		// $('.heartText').toggleClass("heart-active")
		// 		// $('.heartNumb').toggleClass("heart-active")
		// 		$('.heart').toggleClass("heart-active")
		// 	});
		// });
		// $(document).ready(function(){
		// 	$(".clearfix a").each(function(){
		// 		if($(this).hasClass("article-box")){
		// 			$(this).removeAttr("href");
		// 		}
		// 	});
		// });
	</script>

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
		  <li class="nav-item active"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item"><a href="calendar.php">CALENDAR</a></li>
		  <li class="nav-item"><a href="#jumpToRestaurant">RESTAURANTS</a></li>
		  <li class="nav-item"><a href="#">FAVORITES</a></li>
		  <li class="nav-item"><a href="memberinfo.php">MEMBER INFO</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>

		  <!--餐廳管理員登入時介面-->		
		  <?php }
		  else if($user['category'] == 'R') { ?>
		  <li class="nav-item active"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item"><a href="#jumpToUpdate">UPDATE</a></li>
		  <li class="nav-item"><a href="reservation_information.php">RESERVATION</a></li>
		  <li class="nav-item"><a href="reservation_meal.php">RESERVATION MEAL</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>
		  

		  <!--系統管理員登入時介面-->		
		  <?php }
		  else if($user['category'] == 'S') { ?>
		  <li class="nav-item active"><a href="homepage.php">HOME</a></li>
		  <li class="nav-item"><a href="#jumpToManage">MANAGE</a></li>
		  <li class="nav-item"><a href="創建餐廳帳號.php">CREAT ACCT</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>

		  <!--訪客介面-->
		  <?php }}
		  if($user == NULL) {
		  ?> 
		  <li class="nav-item active"><a href="#">HOME</a></li>
		  <li class="nav-item"><a href="#jumpToRestaurant">RESTAURANTS</a></li>
		  <li class="nav-item"><a href="會員登入.php">LOG IN</a></li> 
		  <?php 
		  } 
		  ?>

		</ul>
	  </nav>
	</header>
	
	<div class="wrapper clearfix">
	<main class="main">
	    <section>
	      <h2 class="hidden">HOT TOPIC</h2>
		  <a href="#" class="hot-topic clearfix">
		    <img class="image" src="./images/butter.jpeg" alt="程式撰寫畫面">
			<div class="content">
			  <h3 class="title">吃甚麼?</h3>
			  <p class="desc">希望透過餐廳預約系統的整合，讓會員可以從網站尋找感興趣的美食，並進行預約餐廳，省去另外尋找預約網站或是撥打電話的困擾!</p>
			  <time class="date" datetime="2021-04-01">2021.05.01 SAT</time>
			</div>
		  </a>
		  <!-- 置入HOT TOPIC內容 -->
		</section>

		<h2 class="heading">NEWS</h2>
		<ul class="scroll-list">
		  <li class="scroll-item">
		    <a href="#">
			  <time class="date" datetime="2021-04-01">2021.04.01 FRI</time>
			  <span class="category news">NEWS</span>
			  <span class="title">加入會員即可獲得優惠券！</span>
			</a>
		  </li>
		  <li class="scroll-item">
		    <a href="#">
			  <time class="date" datetime="2021-04-22">2021.04.22 SAT</time>
			  <span class="category">TOPIC</span>
			  <span class="title">會員註冊好康報你知！</span>
			</a>
		  </li>
		</ul>


	<?php 
	// 系統管理員頁面
	if($user != NULL && $user['category'] == 'S') {
		$result1 = mysqli_query($db_link, "SELECT * FROM account NATURAL JOIN restaurant WHERE category = 'R'");
		if(mysqli_num_rows($result1) > 0) {
			$per = 10; //每頁有幾筆資料 
    		$current_page = 1; //預設頁數
	
    		//若已經有翻頁，將頁數更新
    		if (isset($_GET['page'])) {
    	  	$current_page = $_GET['page'];
    		}
    		//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
    		$startRecord = ($current_page - 1) * $per;
    		//未加限制顯示筆數的SQL敘述句
    		$sql = "SELECT * FROM account NATURAL JOIN restaurant WHERE category = 'R'";
    		//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
    		$sql_limit = $sql." LIMIT ".$startRecord.", ".$per;
    		//以加上限制顯示筆數的SQL敘述句查詢資料到 $result 中
    		$result1 = mysqli_query($db_link, $sql_limit);
    		//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_result 中
    		$all_result = mysqli_query($db_link, $sql);
    		//計算總筆數
    		$total_records = mysqli_num_rows($all_result);
    		//計算總頁數=(總筆數/每頁筆數)後無條件進位。
    		$total_pages = ceil($total_records/$per);
	?>

	<div class="restAccount" id="jumpToManage">
    <table>
        <tr>
            <th>餐聽帳號編碼</th>
            <th>密碼</th>
            <th>帳號種類</th>
            <th>經營餐廳名稱</th>
            <th>執行動作</th>
        </tr>
        <?php
            $i = 0;
            while($row1 = mysqli_fetch_array($result1)) { ?>
        <tr>
            <td><?php echo $row1["aNo"]; ?></td>
            <td><?php echo $row1["password"]; ?></td>
            <td><?php echo $row1["category"]; ?></td>
            <td><?php echo $row1["rName"]; ?></td>
            <td><a href="./deleteRest.php?rId=<?php echo $row1['rId']; ?>&aNo=<?php echo $row1['aNo']; ?>&rName=<?php echo $row1['rName']; ?>">刪除</a></td>
        </tr>

        <?php $i++; }?>
    </table>
	</div><br>
	
	<?php
	if ($current_page > 1) { // 若不是第一頁則顯示 ?>
		<td><a href="homepage.php?page=1#jumpToManage">第一頁</a></td>
    	<td><a href="homepage.php?page=<?php echo $current_page-1;?>#jumpToManage">上一頁</a></td>
	<?php } 

		for($j = 1; $j <= $total_pages; $j++){
        	if($j == $current_page) {
            	echo $j." ";
        	}else {
            	echo "<a href=\"homepage.php?page=$j#jumpToManage\">$j</a> ";
        	}
    	}
    	if ($current_page < $total_pages) { // 若不是最後一頁則顯示 ?>
    		<td><a href="homepage.php?page=<?php echo $current_page+1;?>#jumpToManage">下一頁</a></td>
    		<td><a href="homepage.php?page=<?php echo $total_pages;?>#jumpToManage">最後頁</a></td>
    <?php }
	}else {
        echo 'No result found';
	}

	// 餐廳管理員頁面
	}else if($user != NULL && $user['category'] == 'R') { 
		$sql = "SELECT rId FROM account WHERE aNo='".$_SESSION["aNo"]."'";
		$result = mysqli_query($db_link, $sql);
		$row = mysqli_fetch_array($result);
		$rId = $row['rId'];

		$sql = "SELECT * FROM restaurant WHERE rId = $rId";
    	$result = mysqli_query($db_link, $sql);
		if(mysqli_num_rows($result) > 0) {
		//$i = 0;
    	while($row = mysqli_fetch_array($result)) { ?>

		<section class="articles">
		  	<h2 class="hidden">ARTICLES</h2>
		  	<div class="clearfix" id="jumpToUpdate">
			  	<a href="restaurant.php?rId=<?php echo $row["rId"]; ?>" class="article-box">
					<?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $row['photo'] ).'"/ class="image">'; ?>
			  		<h3 class="title"><?php echo $row["rName"]; ?></h3>
			  		<p class="desc"><?php echo $row["rPhone"]; ?></p>
			  		<p class="title">更新餐廳資訊</p>
					<time class="date" datetime="2021-04-23">均消: <?php echo $row["bottomPrice"]; ?></time>
				</a>
			</div>
		</section> 
		<?php }//$i++;
	}
	
	}else {?>

	<?php
		$per = 8; //每頁有幾筆資料 
    	$current_page = 1; //預設頁數
	
    	//若已經有翻頁，將頁數更新
    	if (isset($_GET['page'])) {
    	  $current_page = $_GET['page'];
    	}
    	//本頁開始記錄筆數 = (頁數-1)*每頁記錄筆數
    	$startRecord = ($current_page - 1) * $per;
    	//未加限制顯示筆數的SQL敘述句
    	$sql = "SELECT * FROM restaurant";
    	//加上限制顯示筆數的SQL敘述句，由本頁開始記錄筆數開始，每頁顯示預設筆數
    	$sql_limit = $sql." LIMIT ".$startRecord.", ".$per;
    	//以加上限制顯示筆數的SQL敘述句查詢資料到 $result 中
    	$result = mysqli_query($db_link, $sql_limit);
    	//以未加上限制顯示筆數的SQL敘述句查詢資料到 $all_result 中
    	$all_result = mysqli_query($db_link, $sql);
    	//計算總筆數
    	$total_records = mysqli_num_rows($all_result);
    	//計算總頁數=(總筆數/每頁筆數)後無條件進位。
    	$total_pages = ceil($total_records/$per);
	?>

	<h2 class="heading" id="jumpToRestaurant">RESTAURANTS</h2><br>
	<div class="article-frame">
	<?php 
	if(mysqli_num_rows($result) > 0) {
	$i = 0;
    while($row = mysqli_fetch_array($result)) { 

		// 會員或訪客不會看到未籌備好的餐廳
		if($row["rName"]) {?>
		<section class="articles">
		  	<h2 class="hidden">ARTICLES</h2>
		  	<div class="clearfix">
				<a href="restaurant.php?rId=<?php echo $row["rId"]; ?>" class="article-box" target="_parent">
				
					<?php echo '<img src="data:image/jpeg;base64,'.base64_encode( $row['photo'] ).'"/ class="image">'; ?>
			  		<h3 class="title"><?php echo $row["rName"]; ?></h3>
			  		<p class="desc"><?php echo $row["rPhone"]; ?></p>
			  		<p class="desc"><?php echo $row["rAddress"]; ?></p>
					
					<!-- heart button -->
					<!-- <p class="title">
					<div class="heart-btn">
						<div class="heartContent">
							<span class="heart"></span>
						</div>
					</div>
					</p> -->
					
					<time class="date" datetime="2021-04-23">均消: <?php echo $row["bottomPrice"]; ?></time>
				</a>
			</div>
		</section> 
		<?php }
	}  $i++; }?>
	</div>

	<?php
	if ($current_page > 1) { // 若不是第一頁則顯示 ?>
		<td><a href="homepage.php?page=1#jumpToRestaurant">第一頁</a></td>
    	<td><a href="homepage.php?page=<?php echo $current_page-1;?>#jumpToRestaurant">上一頁</a></td> 
	<?php } 

	for($j = 1; $j <= $total_pages; $j++){
        if($j == $current_page){
            echo $j." ";
        }else{
            echo "<a href=\"homepage.php?page=$j#jumpToRestaurant\">$j</a> ";
        }
    }
    if ($current_page < $total_pages) { // 若不是最後一頁則顯示 ?>
    	<td><a href="homepage.php?page=<?php echo $current_page+1;?>#jumpToRestaurant">下一頁</a></td>
    	<td><a href="homepage.php?page=<?php echo $total_pages;?>#jumpToRestaurant">最後頁</a></td>
    <?php } }?>
	</main>

	<div class="sidemenu">
	    <h2 class="heading">RANKING</h2>
		<ol class="ranking">
		  <li class="ranking-item">
		    <a href="restaurant.php?rId=1" target="_parent">
			  <!--<img class="image" src="" alt="">-->
			  <span class="order"></span>
			  <p class="text">問鼎 ‧ 皇上吉祥 宮廷火鍋</p>
			</a>
			<a href="restaurant.php?rId=36" target="_parent">
			  <span class="order"></span>
			  <p class="text">八千万石定食專門店</p>
			</a>
			<a href="restaurant.php?rId=20" target="_parent">
			  <span class="order"></span>
			  <p class="text">KATZ Fusion Restaurant</p>
			</a>
			<a href="restaurant.php?rId=41" target="_parent">
			  <span class="order"></span>
			  <p class="text">PUTIEN莆田</p>
			</a>
			<a href="restaurant.php?rId=40" target="_parent">
			  <span class="order"></span>
			  <p class="text">Pizza Rock</p>
			</a>
			<a href="restaurant.php?rId=27" target="_parent">
			  <span class="order"></span>
			  <p class="text">暖呼呼食堂</p>
			</a>
			<a href="restaurant.php?rId=43" target="_parent">
			  <span class="order"></span>
			  <p class="text">1317Bistro</p>
			</a>
			<a href="restaurant.php?rId=39" target="_parent">
			  <span class="order"></span>
			  <p class="text">Ripple 義法西餐廳</p>
			</a>
			<a href="restaurant.php?rId=34" target="_parent">
			  <span class="order"></span>
			  <p class="text">ChuJu waffle 雛菊鬆餅</p>
			</a>
			<a href="restaurant.php?rId=3" target="_parent">
			  <span class="order"></span>
			  <p class="text">酒灑職人串燒</p>
			</a>
		</li>
	</ol>
	
	
	<?php
	if(!$user) { ?>
		<h2 class="hidden">SEARCH</h2>
		<form class="search-box" action="search.php" method="get">
		  <input class="search-input" type="text" name="keyword" placeholder="SEARCH">
		  <input name="search" type="hidden" value="search">
		  <input class="search-button" type="submit" value="搜尋">
		  <p class="text">搜尋餐廳名稱</p>
		</form>
	<?php
	}
	else {
		if($user['category'] != 'R') {?>
			<h2 class="hidden">SEARCH</h2>
			<form class="search-box" action="search.php" method="get">
				<input class="search-input" type="text" name="keyword" placeholder="SEARCH">
				<input name="search" type="hidden" value="search">
				<input class="search-button" type="submit" value="搜尋">
				<p class="text">搜尋餐廳名稱</p>
			</form>
	<?php
		}
	}		
	?>
	<!--修改餐廳地圖連結-->
	<?php
	if($user) {			
		if($user['category'] == 'R') {?>
			<form class="search-box" action="updatemap.php" method="post">
				<input class="search-input" type="text" name="updatemap" placeholder="UPDATE GOOGLE MAP">
				<input class="search-button" type="submit" value="更新">
				<p class="text">更新餐廳地圖連結</p>
			</form>
			<form class="search-box" action="updatecreditScore.php" method="post">
				<input class="search-input" type="text" name="updatecreditScore" placeholder="UPDATE RESERVATION CREDIT SCORE">
				<input class="search-button" type="submit" value="更新">
				<p class="text">更新預約餐廳的信用分數下限</p>
			</form>
			<form class="search-box" action="updatecancelDay.php" method="post">
				<input class="search-input" type="text" name="cancelDay" placeholder="UPDATE CANCELLATION DEADLINE">
				<input class="search-button" type="submit" value="更新">
				<p class="text">更新取消預約的天數</p>
			</form>
	<?php
		}
	}
	?>
	</div>
	</div>
	
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