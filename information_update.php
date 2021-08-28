<?php 
    include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
	session_start();
	
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
	<title>會員個人資料更新</title>
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
    <form action="succeed.php" method="post" name="information_update" enctype="multipart/form-data" style="padding-top:40px; padding-bottom:50px;">
		<table border="1" align="center" cellpadding="10" class="menu" id="t11" style="margin-top:40px; color:black; border-radius: 10px; width:89.5%">
		<tr>
			<th colspan="3" style="font-size:30px; height: 55px;">修改個人資料</th></label>
		</tr>
		<tr>
			<td style="width: 120px;height: 70px; text-align: center; font-size: 22px">姓名</td>
            <td style="width: 370px;height: 70px; text-align: center; font-size: 22px"><?php echo $user["mName"];?></td>
			<td rowspan="7" valign="top">
			<input type="file" name="headPic" id="progressbarTWInput" accept="image/gif, image/jpeg, image/png" value="<?php echo $user['pic'];?>"/ >
			<img style="max-height: 700px; width: 767px"; id="preview_progressbarTW_img" src="<?php echo $user["pic"];?>" />
			</td>
		</tr>
		<tr>	
			</td>
			<script>
			$("#progressbarTWInput").change(function(){
				readURL(this);
			});
			function readURL(input){
				if(input.files && input.files[0]){
					var reader = new FileReader();
					reader.onload = function (e) {
						$("#preview_progressbarTW_img").attr('src', e.target.result);
					}
					reader.readAsDataURL(input.files[0]);
				}
			}
			</script>
		</tr>
		<tr>
			<td style="width: 120px;height: 70px; text-align: center;center;font-size: 22px">電話</td>
            <td style="width: 370px;height: 70px; text-align: center;center;font-size: 22px"><?php echo $user["mPhone"];?></td>
		</tr>
		<tr>
			<td style="width: 120px;height: 70px; text-align: center;center;font-size: 22px">性別</td>
            <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><input type="varchar" name="gender" value="<?php echo $user['gender'];?>"></td>
		</tr>
		<tr>
			<td style="width: 120px;height: 70px; text-align: center; center;font-size: 22px">住址</td>
            <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><input type="varchar" name="address" value="<?php echo $user['mAddress'];?>"></td>						
		</tr>
		<tr>
			<td style="width: 120px;height: 70px; text-align: center; center;font-size: 22px">信箱</td>
            <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><input type="text" name="email" value="<?php echo $user['email'];?>"></td>
		</tr>
		<tr>
			<td style="width: 120px;height: 70px; text-align: center; center;font-size: 22px">生日</td>
            <td style="width: 370px;height: 70px; text-align: center; center;font-size: 22px"><input type="date" name="birthday" value="<?php echo $user['birthdate'];?>"></td>
		</tr>
		<tr><td colspan="3" style="text-align:center;"><input type="hidden" name="action" value="modify">
			<input style=" padding: 5px; margin: 15px auto; background-color: #395159; color:#fff; border-radius:10px; cursor:pointer;" type="submit" name="button" value="  更新  "></td></tr>
		</table>
    </form>
	</center>
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