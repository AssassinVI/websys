<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	#UserMsg, #backMsg{ height: 200px; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_GET) {
 	$where=array('Tb_index'=>$_GET['Tb_index']);
 	$row=pdo_select('SELECT * FROM appContacts WHERE Tb_index=:Tb_index', $where);
 	$backMsg_readyonly=$row['process']=='0' ? '' : 'readonly';
}

if ($_POST) {
	$process=empty($_POST['process']) ? '0' : '1';
	$param=array('process'=>$process, 'backMsg'=>$_POST['backMsg']);
    $where=array('Tb_index'=>$_POST['Tb_index']);
    pdo_update('appContacts', $param, $where);
    
    if ($process=='1') {
    	    $name_data=array($_POST['UserName']);
            $adds_data=array($_POST['UserMail']);
            send_Mail('福敏企業有限公司客服', 'server@fmtoto.com.tw', '福敏企業有限公司Q&A', $_POST['backMsg'], $name_data, $adds_data);
    }


    location_up('admin.php?MT_id='.$_POST['mt_id'],'成功更新');
}


?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>Q&A表單
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php" method="POST" enctype='multipart/form-data' class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" for="Tb_index">單號:</label>
							<div class="col-md-4">
								<input type="text" readonly class="form-control" id="Tb_index" name="Tb_index" value="<?php echo $row['Tb_index'];?>">
							</div>

							<label class="col-md-2 control-label" for="UserName">姓名:</label>
							<div class="col-md-4">
								<input type="text" readonly class="form-control" id="UserName" name="UserName" value="<?php echo $row['UserName'];?>">
							</div>
						</div>
						<div class="form-group">
						
							<label class="col-md-2 control-label" for="UserMail">E-mail:</label>
							<div class="col-md-4">
								<input type="text" readonly class="form-control" id="UserMail" name="UserMail" value="<?php echo $row['UserMail'];?>">
							</div>

							<label class="col-md-2 control-label" for="UserPhone">手機號碼:</label>
							<div class="col-md-4">
								<input type="text" disabled class="form-control" id="UserPhone" name="UserPhone" value="<?php echo $row['UserPhone'];?>">
							</div>
						</div>


					
						<div class="form-group">
							<label class="col-md-2 control-label" for="UserMsg">訊息:</label>
							<div class="col-md-10">
								<textarea class="form-control" disabled id="UserMsg" name="UserMsg"><?php echo $row['UserMsg'];?></textarea>
							</div>
						</div>


						<div class="form-group">
							<label class="col-md-2 control-label" for="ckeditor">回信:</label>
							<div class="col-md-10">
								<textarea class="form-control"  <?php echo $backMsg_readyonly;?> id="ckeditor" name="backMsg"><?php echo $row['backMsg']?></textarea>
								<span>如需重新回信，請更新處理狀態為"未處理"</span>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="process">是否處理</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="process" name="process" type="checkbox" value="1" <?php echo $check=!isset($row['process']) || $row['process']==1 ? 'checked' : ''; ?>  />
								<span>(打勾後更新，會隨即發送回信)</span>
							</div>
						</div>
                        
						<input type="hidden" id="mt_id" name="mt_id" value="<?php echo $_GET['MT_id'];?>">
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
						<div class="col-lg-12">
						<button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">更新</button>
						</div>
					</div>
					
				</div><!-- /.panel-body -->
			</div><!-- /.panel -->
		</div>
	</div>

</div><!-- /#page-content -->

<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {
          $("#submit_btn").click(function(event) {
          	 $('#put_form').submit();
          });


      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

