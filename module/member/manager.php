<?php include "../../core/page/header01.php"; //載入頁面heaer01 ?>
<style type="text/css">
	.footer{ position: relative; }
</style>
<?php include "../../core/page/header02.php"; //載入頁面heaer02?>
<?php

if ($_POST) {

	if (empty($_POST['Tb_index'])) {
		//新增
	    if ($_POST['mem_pwd']!=$_POST['mem_pwd_check'] || empty($_POST['mem_pwd'])) {
	    	location_up('admin.php?MT_id=' . $_GET['mt_id'], '密碼不一致或未輸入密碼');
	    	exit();
	    }

		for ($i = 0; $i < 1000; $i++) {
			$param = array(
				'Tb_index' => 'us' . date('YmdHis') . rand(0, 99),
				'mem_email' => $_POST['mem_email'],
				'mem_pwd' => aes_encrypt($aes_key, $_POST['mem_pwd']),
				'name' => $_POST['name'],
				'sex' => $_POST['sex'],
				'tel' => $_POST['tel'],
				'phone' => $_POST['phone'],
				'adds' => $_POST['zipcode'] . $_POST['county'] . $_POST['district'] . "," . $_POST['adds'],
				'FbOrNot' => '0',
				'StartDate' => date('Y-m-d H:i:s'),
			);
			pdo_insert('appMember', $param);
		}

		location_up('admin.php?MT_id=' . $_GET['mt_id'], '成功新增');

	} else {
		//修改
		if (!empty($_POST['mem_pwd'])) {
			if ($_POST['mem_pwd']!=$_POST['mem_pwd_check']) {
				location_up('admin.php?MT_id=' . $_GET['mt_id'], '密碼不一致');
	    	    exit();
			}
			$param_pwd=array('mem_pwd'=>aes_encrypt($aes_key, $_POST['mem_pwd']));
			$where = array('Tb_index' => $_POST['Tb_index']);
			pdo_update('appMember', $param_pwd, $where);
		}

		$param = array(
			'name' => $_POST['name'],
			'sex' => $_POST['sex'],
		    'tel' => $_POST['tel'],
			'phone' => $_POST['phone'],
			'mem_email' => $_POST['mem_email'],
			'adds' => $_POST['zipcode'] . $_POST['county'] . $_POST['district'] . "," . $_POST['adds'],
			'is_use' => $_POST['is_use'],
		);
		$where = array('Tb_index' => $_POST['Tb_index']);
		pdo_update('appMember', $param, $where);
		location_up('admin.php?MT_id=' . $_GET['mt_id'], '成功更新');
	}

}

if (!empty($_GET['Tb_index'])) {
	//讀取資料

	$pdo = pdo_conn();
	$sql = $pdo->prepare("SELECT * FROM appMember WHERE Tb_index=:Tb_index");
	$sql->execute(array(":Tb_index" => $_GET['Tb_index']));
	$row = $sql->fetch(PDO::FETCH_ASSOC);
	$zipcode = substr($row['adds'], 0, 3);
	$adds = explode(',', $row['adds']);
	$male=$row['sex']=='male' ? 'checked' : '';
	$female=$row['sex']=='female' ? 'checked' : '';
    $FbOrNot=$row['FbOrNot']=='1' ? 'Facebook會員' : '一般會員';
    $maile_read=$row['FbOrNot']=='1' ? 'readonly' : ''; //FB會員信箱不可改
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>會員編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="member_form" class="form-horizontal" action="manager.php" method="POST">
						<div class="form-group">
							<label class="col-md-2 control-label" for="name">會員姓名</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="name" name="name" value="<?php echo $row['name']; ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label">性別</label>
							<div class="col-md-4">
								<input type="radio" id="male" name="sex" value="male" <?php echo $male?>><label for="male" class="control-label">男性</label>
								｜
								<input type="radio" id="female" name="sex" value="female" <?php echo $female?>><label for="female" class="control-label">女性</label>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="name">會員類型</label>
							<div class="col-md-4">
								<input type="text" readonly class="form-control" id="FbOrNot" name="FbOrNot" value="<?php echo $FbOrNot; ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="tel">電話</label>
							<div class="col-md-10">
								<input type="text"  class="form-control" id="tel" name="tel" value="<?php echo $row['tel']; ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="phone">手機</label>
							<div class="col-md-10">
								<input type="text"  class="form-control" id="phone" name="phone" value="<?php echo $row['phone']; ?>">
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="mem_email">e-mail(帳號)</label>
							<div class="col-md-10">
								<input type="text"<?php echo $maile_read;?> class="form-control" id="mem_email" name="mem_email" value="<?php echo $row['mem_email']; ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="mem_pwd">密碼</label>
							<div class="col-md-10">
								<input type="password" <?php echo $maile_read;?> class="form-control" id="mem_pwd" name="mem_pwd" placeholder="如不更新請勿輸入">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="mem_pwd_check">確認密碼</label>
							<div class="col-md-10">
								<input type="password" <?php echo $maile_read;?> class="form-control" id="mem_pwd_check" name="mem_pwd_check" placeholder="請輸入跟上面一樣的密碼">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="adds">地址</label>
							<div class="col-md-10">
							    <div style="<?php echo $dispaly = $weblang != 'tw' ? 'display: none;' : ''; ?>" class="twzipcode"></div>
								<input type="text" class="form-control" id="adds" name="adds" value="<?php echo $adds[1]; ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="is_use">是否上線</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="is_use" name="is_use" type="checkbox" value="1" <?php echo $check = !isset($row['is_use']) || $row['is_use'] == 1 ? 'checked' : ''; ?>  />
							</div>
						</div>

                        <input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $row['Tb_index']; ?>">
                        <input type="hidden" id="mt_id" name="mt_id" value="<?php echo $_GET["MT_id"]; ?>">

					</form>
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->




		</div>

		<div class="col-lg-3">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>儲存您的資料</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<div class="row">
						<div class="col-lg-6">
							<button type="button" id="clean_all" class="btn btn-danger btn-block btn-flat" data-toggle="modal" data-target="#settingsModal1">重設表單</button>
						</div>
						<div class="col-lg-6">
							<button type="button" id="contact_btn" class="btn btn-info btn-block btn-raised">儲存</button>
						</div>
					</div>

				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div>
	</div>
	</div>
</div>
<?php include "../../core/page/footer01.php"; //載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {

     $('.twzipcode').twzipcode({
    'zipcodeSel'  : '<?php echo $zipcode; ?>' // 此參數會優先於 countySel, districtSel
    });


     $("#clean_all").click(function(event) {

     });

		$("#contact_btn").click(function(event) {
            $("#member_form").submit();
		});

	});
</script>
<?php include "../../core/page/footer02.php"; //載入頁面footer02.php?>

