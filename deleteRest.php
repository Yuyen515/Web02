<?php
    include_once 'connMysql.php';
    $aNo = $_GET['aNo'];
    $rName = $_GET['rName'];
    $sql1 = "DELETE FROM account WHERE rId = '" . $_GET["rId"] . "'";
    $sql2 = "DELETE FROM restaurant WHERE rId =  '" . $_GET["rId"] . "'";

    if(mysqli_query($db_link, $sql1) && mysqli_query($db_link, $sql2)) {
        echo '<script>alert("成功刪除餐廳帳號';
        echo $aNo . ' ' . $rName . '")</script>'; 
		echo '<meta http-equiv="refresh" content="0; url=homepage.php">';
    }else {
        echo "Error deleting record: " . mysqli_error($db_link);
    }
    mysqli_close($db_link);
?>