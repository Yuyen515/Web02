<!DOCTYPE html>
<?php 
    include("connMysql.php");
    session_start();
    if (!@mysqli_select_db($db_link, "whattoeat")) die("資料庫選擇失敗！");
?>
<html lang="zh-Hant-TW">
  <head>
    <meta charset="UTF-8">
    <link type="text/css" rel="styleSheet" href="css/main.css">
	<link rel="stylesheet" href="css/style.css">
    <title>會員登入與註冊</title>
    <style>
	* {
	  margin: 0;
	  padding: 0;
	}
	html, body {
	  height: 100%;
	}
	@font-face {
	  font-family: 'neo';
	  src: url("font/NEOTERICc.ttf");
	}
	input:focus {
	  outline: none;
	}
	.form input {
	  width: 300px;
	  height: 30px;
	  font-size: 18px;
	  background: #EFEFEF;
	  border: none;
	  border-bottom: 1px solid #23303D;
	  color: #23303D;
	  margin-bottom: 20px;
	}
	.form input::placeholder {
	  color: #23303D;
	  font-size: 18px;
	  font-family: "neo";
	}
	.confirm1 {
	  height: 0; 
	  overflow: hidden;
	  transition: .25s;
	}
	confirm2 {
	  height: -1;
	}
	confirm3 {
	  height: -2;
	}
	.btn {
	  width:140px;
	  height: 40px;
	  border: 1px solid #fff;
	  background: #CBA688;
	  font-size:20px;
	  color: #fff;
	  cursor: pointer;
	  margin-top: 25px;
	  font-family: "neo";
	  transition: .25s;
	}
	.btn:hover {
	  background: rgba(255,255,255,.25);
	}
	#login_wrap {
	  width: 490px;
	  min-height: 600px;
	  border-radius: 10px;
	  font-family: "neo";
	  overflow: hidden;
	  position: fixed;
	  top: 50%;
	  right: 50%;
	  margin-top: -250px;
	  margin-right: -270px;
	}
	#login {
	  width: 100%;
	  height: 100%;
	  min-height: 600px;
	  background: linear-gradient(45deg, #EFEFEF, #EFEFEF);
	  position: relative;
	  float: right;
	  border-radius: 10px;
	}
	#login #status {
	  width: 90px;
	  height: 35px;
	  margin: 40px auto;
	  color: #fff;
	  font-size: 30px;
	  font-weight: 600;
	  position: relative;
	  overflow: hidden;
	}
	#login #status i {
	  font-style: normal;
	  position: absolute;
	  transition: .5s
	}
	#login span {
	  text-align: center;
	  position: absolute;
	  left: 50%;
	  margin-left: -150px;
	  top: 52%;
	  margin-top: -170px;
	}
	#login span a {
	  text-decoration: none;
	  color: #23303D;
	  display: block;
	  margin-top: 80px;
	  font-size: 18px;
	}
	#bg {
	  background: linear-gradient(45deg, #90A3A7, #90A3A7);
	  height: 100%;
	}
	/*提示*/
	#hint {
	  width: 100%;
	  line-height: 70px;
	  background: linear-gradient(-90deg, #9b494d, #bf5853);
	  text-align: center;
	  font-size: 25px;
	  color: #fff;
	  box-shadow: 0 0 20px #733544;
	  display: none;
	  opacity: 0;
	  transition: .5s;
	  position: absolute;
	  top: 0;
	  z-index: 999;
	}
	/* 響應式 */
	@media screen and (max-width:1000px ) {
	  #login_img {
	    display: none;
	  }
	  #login_wrap {
	    width: 490px;
	    margin-right: -245px;
	  }
	  #login {
		width: 100%;
	 }
	}
	@media screen and (max-width:560px ) {
	  #login_wrap {
		width: 330px;
		margin-right: -165px;
	  }
	  #login span {
		margin-left: -125px;
	  }
	  .form input {
		  width: 250px;
	  }
	  .btn {
		  width: 113px;
	  }
	}
	@media screen and (max-width:345px ){
	  #login_wrap {
		width: 290px;
		margin-right: -145px;
	  }
	}
    </style>
  </head>
  <body>
    <div id="bg">
      <div id="hint"><!-- 提示框 -->
          <p>登入失敗</p>
      </div>
      <div id="login_wrap">
        <div id="login"><!-- 登入註冊切換動畫 -->
          <div id="status" style="background: #EFEFEF; color: #23303D;">
            <i style="top: 0px;">Log</i>
            <i style="top: 35px;">Sign</i>
            <i style="right: 5px;">in</i>
          </div>
          <span>
            <form name="loginregister" method="post" action="" enctype="multipart/form-data">
              <p class="form"><input type="text" id="account" name="account" placeholder="  account" required></p>
              <p class="form"><input type="password" id="password" name="passwd" placeholder="  password" required></p>
              <p class="form confirm1"><input type="password" name="verifyPass" id="confirm-passwd" placeholder="  confirm password"></p>
			  <p class="form confirm1 confirm2"><input type="text" name="name" placeholder="  name"></p>
			  <p class="form confirm1 confirm3"><input type="text" name="mPhone" placeholder="  phonenumber"></p>
              <input type="button" value="Log in" class="btn" onclick="login()" style="margin-right: 20px;">
              <input type="button" value="Sign in" class="btn" onclick="signin()" id="btn">
            </form>
			<a href="homepage.php">Back to Homepage</a>
          </span>
        </div>
      </div>
    </div>
    <script>
		var onoff = true; //根據布爾值判斷是登入還是註冊狀態
		var confirm1 = document.getElementsByClassName("confirm1")[0];
		var confirm2 = document.getElementsByClassName("confirm2")[0];
		var confirm3 = document.getElementsByClassName("confirm3")[0];
    
		//自動居中title
		var name_c = document.getElementById("title");
		name = name_c.innerHTML.split("");
		name_c.innerHTML = "";
		for (i = 0; i < name.length; i++) {
			if (name[i] != ",")
				name_c.innerHTML += "<i>" + name[i] + "</i>";
		}
		//引用hint()在最上方彈出提示
		function hint() {
			let hit = document.getElementById("hint");
			hit.style.display = "block";
			setTimeout("hit.style.opacity = 1", 0);
			setTimeout("hit.style.opacity = 0", 2000);
			setTimeout('hit.style.display = "none"', 3000);
		}
		//註冊按鈕
		function signin() {
			let status = document.getElementById("status").getElementsByTagName("i");
			if (onoff) {
				confirm1.style.height = 51 + "px";
				confirm2.style.height = 51 + "px";
				confirm3.style.height = 51 + "px";
				status[0].style.top = 35 + "px";
				status[1].style.top = 0;
				onoff = !onoff;
			} else {
				document.loginregister.action="register.php";
				document.loginregister.submit();
			}
		}

		//登入按鈕
		function login() {
			if (onoff) {
				document.loginregister.action="login.php";
				document.loginregister.submit();
			} else {
				let status = document.getElementById("status").getElementsByTagName("i");
				confirm1.style.height = 0;
				confirm2.style.height = 0;
				confirm3.style.height = 0;
				status[0].style.top = 0;
				status[1].style.top = 35 + "px";
				onoff = !onoff;
			}
		}
    </script>
  </body>
</html>