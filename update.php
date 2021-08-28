<?php
    ini_set('mysql.connect_timeout', 300);
    ini_set('default_socket_timeout', 300);
    include 'connMysql.php';
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");

    // update information
    // count() is a function in php, counting the length of array.
    if(count($_POST) > 0) { 
        $rId = $_POST['rId'];
        $sql = "UPDATE `restaurant` SET rName = '" . $_POST['rName'] . "', rPhone = '" . $_POST['rPhone'] . "', rAddress = '" . $_POST['rAddress'] . "', bottomPrice = '" . $_POST['bottomPrice'] . "', rDesc = '" . $_POST['rDesc'] . "' WHERE rId = '" . $_POST['rId'] . "'";
        mysqli_query($db_link, $sql);

        // update photo
        if (is_uploaded_file($_FILES['photo']['tmp_name'])) {
            // copy the information of restaurant
            $sql = "SELECT * FROM restaurant WHERE rId = '" . $_POST['rId'] . "'";
            $result = mysqli_query($db_link, $sql);
            $row= mysqli_fetch_array($result);

            // delete hole row
            $sql = "DELETE FROM restaurant WHERE rId = '" . $_POST['rId'] . "'";
            $result = mysqli_query($db_link, $sql);

            // insert the restaurant again
            $imgData = addslashes(file_get_contents($_FILES['photo']['tmp_name']));
            $sql = "INSERT INTO `restaurant` VALUES ('" . $row['rId'] . "', '" . $row['rName'] . "', '" . $row['rPhone'] . "', '" . $row['rAddress'] . "', '" . $row['rate'] . "', '" . $row['rDesc'] . "', '" . $row['bottomPrice'] . "', '$imgData')";
            $result = mysqli_query($db_link, $sql)or die("<b>Error:</b> Problem on Image Insert<br/>" . mysqli_error($db_link));
        }
        echo "<script>alert('餐廳資訊更新成功!')</script>";
        echo '<a href="homepage.php#jumpToUpdate"></a>';
		echo '<meta http-equiv="refresh" content="0; url=homepage.php#jumpToUpdate">';
    

    }
?>