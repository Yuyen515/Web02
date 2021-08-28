<?php
    include("connMysql.php");
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
    session_start();

    if (isset($_SESSION['aNo'])) {
        // 檢查會員是否有登錄
		$sql_query = "SELECT * FROM `account` WHERE `aNo`= '".$_SESSION["aNo"]."'";
		$loginResult = mysqli_query($db_link, $sql_query);
		$user = mysqli_fetch_array($loginResult, MYSQLI_ASSOC);
        
        if(isset($_SESSION['rId'])) {
            $rating = $_POST["rating"];

            
            // 檢查該會員有沒有評過分
            $sql = "SELECT rate FROM `rating` WHERE rId = '" . $_SESSION['rId'] . "' AND aNo = '" . $_SESSION['aNo'] . "'";
            $result = mysqli_query($db_link, $sql);
            if(mysqli_num_rows($result) > 0) { // 評過了
                $sql = "UPDATE `rating` SET rate = $rating WHERE rId = " . $_SESSION['rId'] . " AND aNo = '" . $_SESSION['aNo'] . "'";
                mysqli_query($db_link, $sql);
            }else { // 沒評過
                $sql = "INSERT INTO `rating` (aNo, rate, rId) VALUES ('" . $_SESSION['aNo'] . "', '$rating', " . $_SESSION['rId'] . ")";
                mysqli_query($db_link, $sql);
            }

        // 修改restaurant中的rate
        $sql = "SELECT rate FROM `restaurant` WHERE rId = '" . $_SESSION['rId'] . "'";
        $result = mysqli_query($db_link, $sql);
        $row = mysqli_fetch_array($result);
        if(floatval($row['rate']) == 0.0) {
            $new_rating = floatval($rating);
        }else {
            $new_rating = (floatval($rating) + floatval($row['rate'])) / 2;
        }
        $sql = "UPDATE `restaurant` SET rate = $new_rating WHERE rId = '" . $_SESSION['rId'] . "'";

        if (mysqli_query($db_link, $sql)) {
            echo "<script>alert('評分成功!')</script>";
            echo '<a href="homepage.php#jumpToUpdate"></a>';
            echo '<meta http-equiv="refresh" content="0; url=restaurant.php?rId=';
            echo $_SESSION['rId'];
            echo  '">';
        }else {
            echo "Error: " . $sql . "<br>" . mysqli_error($db_link);
        }
    mysqli_close($db_link);
    }
	} else {
		$user = NULL;
	}

?>