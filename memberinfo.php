<!DOCTYPE html>
<?php 
    include("connMysql.php");
    session_start();
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
	
	if (isset($_SESSION['aNo'])) {
        $sql_query_user = "SELECT * FROM `memberinfo` WHERE `aNo`= '".$_SESSION["aNo"]."'";
        $result_user = mysqli_query($db_link, $sql_query_user);
        $user = mysqli_fetch_array($result_user, MYSQLI_ASSOC);
    } else {
        $user = NULL;
    }
?>
<html lang="zh-Hant-TW">
  <head>
    <meta charset ="UTF-8">
	<title>會員個人資料</title>
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
		  <li class="nav-item"><a href="calendar.php">CALENDAR</a></li>
		  <li class="nav-item"><a href="homepage.php#jumpToRestaurant">RESTAURANTS</a></li>
		  <li class="nav-item"><a href="#">FAVORITES</a></li>
		  <li class="nav-item active"><a href="memberinfo.php">MEMBER INFO</a></li>
		  <li class="nav-item"><a href="logout.php">LOG OUT</a></li>
		</ul>
	  </nav>
	</header>
	
    <center>
        <table border="1" align="center" cellpadding="10" class="menu" id="t11" style="margin-top:40px; color:black; border-radius: 10px; width:89.5%">
            <tr>
				<th colspan="3" style="font-size:30px; height: 55px;">個人資料</th>
            </tr>
            <tr border:2px black solid;>
                <td style="width: 120px;height: 70px; text-align: center; font-size: 22px">姓名</td>
                <td style="width: 370px;height: 70px; text-align: center; font-size: 22px"><?php echo $user["mName"];?></td>
                <td rowspan="7" valign="top">
                <img style="max-height: 700px; width: 767px" src="<?php echo $user["pic"];?>" />
				</td>
            </tr>
			<tr>
                <td style="width: 215px;height: 80px; text-align: center;center;font-size: 22px">信用分數</td>
                <td style="width: 370px;height: 70px; text-align: center;center;font-size: 22px"><?php echo $user["creditScore"];?></td>
            </tr>
            <tr>
                <td style="width: 215px;height: 70px; text-align: center;center;font-size: 22px">電話</td>
                <td style="width: 370px;height: 70px; text-align: center;center;font-size: 22px"><?php echo $user["mPhone"];?></td>
            </tr>
            <tr>
                <td style="width: 215px;height: 70px; text-align: center;center;font-size: 22px">性別</td>
                <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><?php echo $user["gender"];?></td>
			</tr>
            <tr>
                <td style="width: 215px;height: 70px; text-align: center; center;font-size: 22px">住址</td>
                <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><?php echo $user["mAddress"];?></td>
            </tr>
            <tr>
                <td style="width: 215px;height: 70px; text-align: center; center;font-size: 22px">信箱</td>
                <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><?php echo $user["email"];?></td>
            </tr>
            <tr>
                <td style="width: 215px;height: 70px; text-align: center; center;font-size: 22px">生日</td>
                <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><?php echo $user["birthdate"];?></td>
            </tr>
        </table>
    </center>
		<br>
        <a href='information_update.php' style="margin-left:705px; color:black; font-size:17px; text-decoration:none;">&ensp;個人資料更新</a>
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