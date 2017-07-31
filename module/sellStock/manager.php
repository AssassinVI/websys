<?php include "../../core/page/header01.php"; //載入頁面heaer01 ?>
<style type="text/css">
	.footer{ position: relative; }
	.del_pro{ padding: 6px ; background-color: red; color: #fff; border-radius: 5px; }
</style>
<?php include "../../core/page/header02.php"; //載入頁面heaer02?>
<?php




?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>進銷貨單
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="SorP_form" class="form-horizontal" action="manager.php" method="POST">
						<div class="form-group">
							<label class="col-md-2 control-label" for="SP_id">單號</label>
							<div class="col-md-4">
								<input type="text" class="form-control" readonly id="SP_id" name="SP_id" value="<?php echo 'SD'.date("YmdHis").rand(0,99); ?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label">單別</label>
							<div class="col-md-4">
								<input type="radio" id="sales" name="SorP" value="S" <?php echo $sales?>><label for="sales" class="control-label">進貨單</label>
								｜
								<input type="radio" id="purchase" name="SorP" value="P" <?php echo $purchase?>><label for="purchase" class="control-label">銷貨單</label>
							</div>
						</div>

						<div class="form-group">
						  <label class="col-md-2 control-label"></label>
						  <div class="col-md-3">
						  <button type="button" id="insert_pro" class="btn btn-success btn-block">新增產品</button>
						  </div>
						  <div class="col-md-3">
						  <a href="search_pro.php" data-fancybox-type="iframe" id="select_pro" class="btn btn-success btn-block fancybox">查詢產品</a>
						  </div>
						</div>
						
                       

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

		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
							    <th>#</th>
								<th>品號</th>
								<th>品名</th>
								<th>數量</th>
								<th>單價</th>
							</tr>
						</thead>
						<tbody id="SorP_tr">
                         
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	</div>
	</div>
</div>
<?php include "../../core/page/footer01.php"; //載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {

     $('.twzipcode').twzipcode({
    'zipcodeSel' : '<?php echo $zipcode; ?>' // 此參數會優先於 countySel, districtSel
    });


     $("#clean_all").click(function(event) {

     });

		$("#contact_btn").click(function(event) {
            $("#member_form").submit();
		});


//----------------------- 新增產品 --------------------------
	$('#insert_pro').click(function(event) {

		var insert_txt='<tr>';
   		   insert_txt+='<td><a href="#" class="del_pro">Ｘ</a></td>';
		   insert_txt+='<td><input type="text" class="form-control pro_id" name="pro_id"></td>';
		   insert_txt+='<td><input type="text" class="form-control pro_name" name="pro_name" disabled></td>';
           insert_txt+='<td><input type="text" class="form-control pro_num" name="pro_num"></td>';
           insert_txt+='<td><input type="text" class="form-control pro_price" name="pro_price"></td>';
		   insert_txt+='</tr>';
		$('#SorP_tr').append(insert_txt);
	});
 

 //----------------------- 刪除產品 --------------------------
	$('#SorP_tr').on('click', '.del_pro', function(event) {
		event.preventDefault();
		$(this).parent().parent().remove();
	});

	$('#SorP_tr').on('change', '.pro_id', function(event) {
		event.preventDefault();
		
		if ($(this).val()!='') {
           $.ajax({
           	url: 'member_ajax.php',
           	type: 'POST',
           	context: $(this), //---------- 让回调函数内 this 指向这个对象 ------------
           	data: {
           		type:'pro_id',
           		pro_id: $(this).val(),
           	},
           	success:function (result) {
           		 $(this).parent().next().find('.pro_name').val(result);
           		 if ($(this).parent().next().find('.pro_name').val()=='error') {
           		 	$(this).parent().next().find('.pro_name').val('查無產品!!');
           		 	$(this).parent().next().next().find('.pro_num').attr('disabled', true);
           		    $(this).parent().next().next().next().find('.pro_price').attr('disabled', true);
           		 }else{
                    $(this).parent().next().next().find('.pro_num').attr('disabled', false);
           		    $(this).parent().next().next().next().find('.pro_price').attr('disabled', false);
           		 }
           		 
           	}
          });
		}

		   if ($(this).parent().next().find('.pro_name').val()=='error') {
           	     $(this).parent().next().next().find('.pro_num').attr('disabled', true);
           		 $(this).parent().next().next().next().find('.pro_price').attr('disabled', true);
           }
           else{
           	     $(this).parent().next().next().find('.pro_num').attr('disabled', false);
           		 $(this).parent().next().next().next().find('.pro_price').attr('disabled', false);
           }

	});


	});
</script>
<?php include "../../core/page/footer02.php"; //載入頁面footer02.php?>

