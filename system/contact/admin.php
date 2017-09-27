<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.footer{ position: relative; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php

 $skype_num=4;

  if ($_POST) {

  	if (!empty($_POST['type']) && $_POST['type']=='delete') { //刪除圖片
  		$pth='../../img/'.trim($_POST['logo']);
       unlink($pth);
       $param=array('logo'=>'');
       $where=array('Tb_index'=>$_POST['Tb_index']);
       pdo_update('company_base', $param, $where);
       exit();
  	}
  	 
  	if (empty($_POST['Tb_index'])) { //新增
  	 	
  	 $param=array(
  	 	           'Tb_index'=>'com'.date('YmdHis').rand(0,99),
  	 	               'name'=>$_POST['name'],
  	 	             
  	 	             'remark'=>$_POST['remark'],
  	 	              'phone'=>$_POST['phone'],
  	 	                'fax'=>$_POST['fax'],
  	 	              'email'=>$_POST['email'],
  	 	               'adds'=>$_POST['adds'],
  	 	            'keywords'=>$_POST['keywords'],
  	 	          'description'=>$_POST['description'],
  	 	            'webLang'=>$weblang
  	 	           );
  	   pdo_insert('company_base', $param);


    } 
    else{  //修改

  	     $param=array( 'name'=>$_POST['name'],
  	     	         
  	 	             'remark'=>$_POST['remark'],
  	 	              'phone'=>$_POST['phone'],
  	 	                'fax'=>$_POST['fax'],
  	 	              'email'=>$_POST['email'],
  	 	               'adds'=>$_POST['adds'],
  	 	           'keywords'=>$_POST['keywords'],
  	 	        'description'=>$_POST['description']
  	 	           );
  	 $where=array( 'Tb_index'=>$_POST['Tb_index'] );
  	   pdo_update('company_base', $param, $where);
    }

  }
  else{ //讀取資料

     $pdo=pdo_conn();
     $sql=$pdo->prepare("SELECT * FROM company_base WHERE webLang=:webLang LIMIT 0,1");
     $sql->execute(array("webLang"=>$weblang));
     $row=$sql->fetch(PDO::FETCH_ASSOC);
     $location=explode(',', $row['location']);
     $lat=$location[0];
     $lon=$location[1];
     $zipcode=($row['webLang']=='tw')? substr($row['adds'], 0,3) : '';
     $adds=explode(',', $row['adds']);
  }

 if ($_FILES['logo']['name']) { //檔案上傳
  	
  	$type=explode('.', $_FILES['logo']['name']);
  	 move_uploaded_file($_FILES['logo']['tmp_name'], '../../img/company_logo.'.$type[1]);

  	 $param=array('logo'=>'company_logo.'.$type[1]);
  	 $where=array( 'Tb_index'=>$row['Tb_index'] );
  	   pdo_update('company_base', $param, $where);
   }
     

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>資料編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" for="name">公司名稱</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="name" value="<?php echo $row['name'];?>">
							</div>
							<!--<label class="col-md-2 control-label" for="name">英文名稱</label>
							<div class="col-md-4">
								<input type="text" class="form-control" id="e_name" value="<?php //echo $row['e_name'];?>">
							</div>-->
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="remark">版權宣告</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="remark" value="<?php echo $row['remark'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="logo">公司LOGO</label>
							<div class="col-md-10">
								<input type="file" name="logo"  class="form-control" id="logo" onchange="file_viewer_load_new(this, '#com_logo_div')">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="logo"></label>
							<div class="col-md-10">
								<div id="com_logo_div">
								 
								</div>
							<?php if(!empty($row['logo'])){?>
								<div class="old_img_div">
								    <p>舊圖檔</p>
									<button type="button" id="one_del_img"> X </button>
									<img style="width:150px;" src="../../img/<?php echo $row['logo'];?>" alt="">
								</div>
							<?php }?>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="phone">聯絡電話</label>
							<div class="col-md-10">
								<input type="text"  class="form-control" id="phone" value="<?php echo $row['phone'];?>">
							</div>
						</div>
                        
                
						<div class="form-group">
							<label class="col-md-2 control-label" for="fax">傳真</label>
							<div class="col-md-10">
								<input type="text"  class="form-control" id="fax" value="<?php echo $row['fax'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="email">e-mail</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="email" value="<?php echo $row['email'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="adds">地址</label>
							<div class="col-md-10">
							    <div style="<?php echo $dispaly=$weblang!='tw'? 'display: none;' : '';?>" class="twzipcode"></div>
								<input type="text" class="form-control" id="adds" value="<?php echo $adds[1];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="keywords">關鍵字</label>
							<div class="col-md-10">
								<textarea class="form-control" id="keywords" name="keywords"><?php echo $row['keywords'];?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="description">描述</label>
							<div class="col-md-10">
							   
								<textarea class="form-control" id="description" name="description"><?php echo $row['description'];?></textarea>
							</div>
						</div>
						

                     

                        <input type="hidden" id="Tb_index" value="<?php echo $row['Tb_index'];?>">
                        <input type="hidden" id="logo_val" value="<?php echo $row['logo'];?>">
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
<?php  include("../../core/page/footer01.php");//載入頁面footer02.php?>
<script type="text/javascript">
	$(document).ready(function() {

     $('.twzipcode').twzipcode({
    'zipcodeSel'  : '<?php echo $zipcode;?>' // 此參數會優先於 countySel, districtSel
    });


     $("#clean_all").click(function(event) {
     	
     });

		$("#contact_btn").click(function(event) {

			var data={
				      Tb_index: $('#Tb_index').val(),
                          name: $('#name').val(),
                        remark: $('#remark').val(),
                         phone: $('#phone').val(),
                           fax: $('#fax').val(),
                         email: $('#email').val(),
                          adds: $('[name="zipcode"]').val()+$('[name="county"]').val()+$('[name="district"]').val()+","+$("#adds").val(),
                          description: $('#description').val(),
                          keywords:$('#keywords').val()
                     
			         };
			ajax_in('admin.php', data, '資料儲存', 'no');
			
          if ($("#one_img").length>0) {
          	 ajax_file('admin.php', 'logo', '#one_img');
          }
		});

		$("#one_del_img").click(function(event) {
			if (confirm('是否要刪除公司LOGO?')) {
			 var data={
                        Tb_index: $('#Tb_index').val(),
                        logo: $('#logo_val').val(),
                        type: 'delete'
			          };	
               ajax_in('admin.php', data, '成功刪除', 'admin.php');
			}
		});
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

