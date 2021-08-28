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

	 //更新個人資料
    if(isset($_POST["action"])&&($_POST["action"]=="modify")){
        // move file from tmp imto ./uploads folder
		if($_FILES["headPic"]['name'] == NULL) {
			$sql = "UPDATE `memberinfo` 
			SET `mAddress`='".$_POST["address"]."', `email`='".$_POST["email"]."', `birthdate`='".$_POST["birthday"]."', `gender`='".$_POST["gender"]."' WHERE `aNo`='".$user['aNo']."'";
			mysqli_query($db_link, $sql);
		}
		else {
			$target_file = './uploads/' . $_FILES['headPic']['name'];
			move_uploaded_file($_FILES["headPic"]["tmp_name"], $target_file);

			$sql = "UPDATE `memberinfo` SET `mAddress`='".$_POST["address"]."', `email`='".$_POST["email"]."', `birthdate`='".$_POST["birthday"]."', `gender`='".$_POST["gender"]."', `pic`='".$target_file."' WHERE `aNo`='".$user['aNo']."'";
			mysqli_query($db_link, $sql);
		}
       
    }

	echo '<script>alert("更新成功!")</script>'; 
    echo '<meta http-equiv="refresh" content="0; url=memberinfo.php">';
?>