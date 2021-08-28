<!DOCTYPE html>
<?php 
    include("connMysql.php");
    session_start();
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
?>
<html lang="zh-Hant-TW">
  <head>
    <meta charset ="UTF-8">
	<title>創建餐廳帳號</title>
	<link rel="stylesheet" href="css/reset.css">
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
		  <li class="nav-item"><a href="homepage.php#jumpToManage">MANAGE</a></li>
		  <li class="nav-item active"><a href="創建餐廳帳號.php">CREAT ACCT</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>
		</ul>
	  </nav>
	</header>
	<div>
	  <!--創建餐廳管理員帳號表單-->
	  <form name="register" method="post" action="register_r.php">
		<fieldset>
		  <legend>SIGN UP</legend>
		  <label>帳號：</label><br>
		  <input type="text" name="account" required/><br>
		  <label>密碼：</label><br>
		  <input type="password" name="passwd" required/><br>
		  <label>密碼確認：</label><br>
		  <input type="password" name="verifyPass" required /><br><br>
		  <input type="submit" name="Submit" value="創建餐廳帳號" /> 
		</fieldset> 
	  </form> 
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