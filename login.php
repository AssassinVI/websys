<?php session_start();
require 'core/inc/config.php';
require 'core/inc/function.php';

?>
<?php
$company_txt = pdo_select("SELECT * FROM company_base WHERE webLang='tw'", 'no');
if ($_GET) {
	if ($_GET['login'] == 'out') {session_destroy();}
}

if ($_POST) {

	//----------------GOOGLE recaptcha 驗證程式 --------------------
	if (!empty($_POST['g-recaptcha-response'])) {

		$ReCaptchaResponse = filter_input(INPUT_POST, 'g-recaptcha-response');

		// 建立CURL連線
		$ch = curl_init();
		// 設定擷取的URL網址
		curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?secret=6Le-hSUTAAAAAKpUuKnGOoHpKhgq60V1irZPA_4E&response=' . trim($ReCaptchaResponse));
		curl_setopt($ch, CURLOPT_HEADER, false);
		//將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		// 執行
		$Response = curl_exec($ch);
		// 關閉CURL連線
		curl_close($ch);

		//$Response=file_get_contents();
		$re_code = json_decode($Response, true);

		if ($re_code['success'] != true) {

			location_up('login.php', '請確定您不是機器人');
			exit();
		}
	} else {

		location_up('login.php', '請確定您不是機器人');
		exit();
	}
	//----------------GOOGLE recaptcha 驗證程式 --------------------*

	$where = array("admin_id" => $_POST['admin_id'], "admin_pwd" => aes_encrypt($aes_key, $_POST['admin_pwd']));
	$admin = pdo_select("SELECT Tb_index, admin_per, name FROM sysAdmin WHERE admin_id=:admin_id AND admin_pwd=:admin_pwd AND is_use='1'", $where);

	if (empty($admin)) {
		location_up('login.php', '帳號或密碼錯誤!!');
	} else {
		if ($admin['admin_per'] == 'admin') {
			location_up('module/Dashboard/index.php', '歡迎管理者登入');
			//登入密鑰
			login_key($admin['Tb_index']);
			$_SESSION['admin_index'] = $admin['Tb_index'];
			$_SESSION['admin_per'] = $admin['admin_per'];
		} else {
           
            //-- 權限 --
			$group_where=array("Tb_index"=>$admin['admin_per']);
			$group=pdo_select("SELECT Permissions FROM sysAdminGroup WHERE Tb_index=:Tb_index", $group_where);
			$group_array=explode(',', $group['Permissions']);

			location_up('module/Dashboard/index.php', '歡迎' . $admin['name'] . '登入');
			//登入密鑰
			login_key($admin['Tb_index']);
			$_SESSION['admin_index'] = $admin['Tb_index'];
			$_SESSION['admin_per'] = $admin['admin_per'];
			$_SESSION['group']=$group_array;
		}
	}
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $company_txt['name'] ?> | Login</title>
    <link rel="shortcut icon" href="../favicon.ico" />
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
  <style type="text/css">
    body{ font-family: Microsoft JhengHei }
    .logo-name{ font-size: 75px; letter-spacing: -5px; text-shadow: 2px 4px 10px #acacac;color:#fff;}
  </style>

</head>

<body class="gray-bg">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <div>

                <h3 class="logo-name"><?php echo $e_name = empty($company_txt['e_name']) ? $company_txt['name'] : $company_txt['e_name'] ?></h3>

            </div>
            <h3>Welcome to <?php echo $e_name ?></h3>

            <form class="m-t" role="form" method="POST" action="login.php">
                <div class="form-group">
                    <input type="text" class="form-control" name="admin_id" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" name="admin_pwd" placeholder="Password" required="">
                </div>
                <!-- google 驗證碼 -->
                <div class="g-recaptcha" data-sitekey="6Le-hSUTAAAAABhfvrZeqewWS6hENhApDVtdAJfr"></div>
                <button type="submit" class="btn btn-primary block full-width m-b">登入系統</button>
            </form>
            <p class="m-t"> <small>Copyright ©<?php echo $company_txt['remark'] ?></small> </p>
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- GOOGLE recaptcha 驗證程式 -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

</body>

</html>
