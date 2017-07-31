<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	#UserMsg{ height: 200px; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_GET) {
 	$where=array('Tb_index'=>$_GET['Tb_index']);
 	$row=pdo_select('SELECT SV.*, Mem.name, Mem.mem_email, Mem.phone, Mem.adds FROM appService as SV INNER JOIN appMember as Mem ON SV.member_id=Mem.Tb_index WHERE SV.Tb_index=:Tb_index', $where);
}

if ($_POST) {
	$is_deal=empty($_POST['is_deal']) ? '0' : '1';
	$param=array('is_deal'=>$is_deal);
    $where=array('Tb_index'=>$_POST['Tb_index']);
    pdo_update('appService', $param, $where);
    location_up('admin.php?MT_id='.$_POST['mt_id'],'成功更新');
}


?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>維修服務表單
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php" method="POST" enctype='multipart/form-data' class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" for="Tb_index">單號</label>
							<div class="col-md-10">
								<input type="text" readonly class="form-control" id="Tb_index" name="Tb_index" value="<?php echo $row['Tb_index'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="sellName">經銷商名稱</label>
							<div class="col-md-2">
								<input type="text" disabled class="form-control" id="sellName" name="sellName" value="<?php echo $row['sellName'];?>">
							</div>

							<label class="col-md-2 control-label" for="pro_id">產品型號</label>
							<div class="col-md-2">
								<input type="text" disabled class="form-control" id="pro_id" name="pro_id" value="<?php echo $row['pro_id'];?>">
							</div>

							<label class="col-md-2 control-label" for="useNum">使用年限</label>
							<div class="col-md-2">
								<input type="text" disabled class="form-control" id="useNum" name="useNum" value="<?php echo $row['useNum'];?>">
							</div>
						</div>


						

						<div class="form-group">
							<label class="col-md-2 control-label" for="UserMsg">維修原因</label>
							<div class="col-md-10">
								<textarea class="form-control" disabled id="UserMsg" name="UserMsg" placeholder="維修原因"><?php echo $row['UserMsg'];?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="is_deal">是否處理</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="is_deal" name="is_deal" type="checkbox" value="1" <?php echo $check=!isset($row['is_deal']) || $row['is_deal']==1 ? 'checked' : ''; ?>  />
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


		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>客戶資訊
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
				    <form  class="form-horizontal">
						<div class="form-group">
						    <label class="col-md-2 control-label" for="sellName">E-Mail:</label>
							<div class="col-md-10">
								<p class="form-control"><?php echo $row['mem_email'];?></p>
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="Tb_index">姓名:</label>
							<div class="col-md-4">
							     <p class="form-control"><?php echo $row['name'];?></p>
							</div>

							<label class="col-md-2 control-label" for="pro_id">電話:</label>
							<div class="col-md-4">
								<p class="form-control"><?php echo $row['phone'];?></p>
							</div>
						</div>
                        <div class="form-group">
							<label class="col-md-2 control-label" for="useNum">地址:</label>
							<div class="col-md-10">
								<p class="form-control"><?php echo $row['adds'];?></p>
							</div>
					    </div>
                   </form>
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

