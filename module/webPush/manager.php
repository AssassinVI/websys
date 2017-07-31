<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 


if ($_POST) {


  // ======================== 刪除 ===========================
  	//----------------------- 代表圖刪除 -------------------------------
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    	if (!empty($_POST['pic'])) {
    		$del_param=array('pic'=>'');
            $del_where=array('Tb_index'=>$_POST['Tb_index']);
            pdo_update('appWebPush', $del_param, $del_where);
            unlink('../../img/'.$_POST['pic']);
    	}
       exit();
  	}


	if (empty($_POST['Tb_index'])) {//新增

		$Tb_index='wp'.date('YmdHis').rand(0,99);
     
     //===================== 背景圖 ========================
      if (!empty($_FILES['pic']['name'])){
      	 $type=explode('.', $_FILES['pic']['name']);
      	 $pic=$Tb_index.'.'.$type[1];
         fire_upload('pic', $pic);
      }else{
      	 $pic='';
      }



	$param=array(  'Tb_index'=>$Tb_index,
		              'title'=>$_POST['title'],
		            'content'=>$_POST['content'],
		                'pic'=>$pic,
		                'url'=>$_POST['url'],
		          'StartDate'=>date('Y-m-d H:i:s'),
		            'webLang'=>$weblang
		          );
	pdo_insert('appWebPush', $param);
	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功新增');
   }
   else{  //修改

   	$Tb_index =$_POST['Tb_index'];

      if (!empty($_FILES['pic']['name'])) {
      	 $type=explode('.', $_FILES['pic']['name']);
      	 $pic=$Tb_index.'.'.$type[1];
         fire_upload('pic', $pic);

        $pic_param=array('pic'=>$pic);
        $pic_where=array('Tb_index'=>$Tb_index);
        pdo_update('appWebPush', $pic_param, $pic_where);
      }
    

    $param=array(  
    	             'title'=>$_POST['title'],
		           'content'=>$_POST['content'],
		               'url'=>$_POST['url'],
		         'StartDate'=>date('Y-m-d H:i:s'),
		           'webLang'=>$weblang
		          );
    $where=array( 'Tb_index'=>$Tb_index );
	pdo_update('appWebPush', $param, $where);
	
	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功更新');
   }
}

if ($_GET) {
 	$where=array('Tb_index'=>$_GET['Tb_index']);
 	$row=pdo_select('SELECT * FROM appWebPush WHERE Tb_index=:Tb_index', $where);
}


?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
				<div class="panel-heading">
					<header>網頁資料編輯
					</header>
				</div><!-- /.panel-heading -->
				<div class="panel-body">
					<form id="put_form" action="manager.php" method="POST" enctype='multipart/form-data' class="form-horizontal">
						<div class="form-group">
							<label class="col-md-2 control-label" for="title">標題名稱</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="title" name="title" value="<?php echo $row['title'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="pic">圖檔</label>
							<div class="col-md-10">
								<input type="file" name="pic" class="form-control" id="pic" onchange="file_viewer_load_new(this, '#img_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="img_box" class="col-md-4">
								
							</div>
						<?php if(!empty($row['pic'])){?>
							<div  class="col-md-4">
							   <div id="img_div" >
							    <p>目前圖檔</p>
								 <button type="button" id="one_del_img"> X </button>
								  <span class="img_check"><i class="fa fa-check"></i></span>
								  <img id="one_img" src="../../img/<?php echo $row['pic'];?>" alt="請上傳代表圖檔">
								</div>
							</div>
						<?php }?>		
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="content">內容</label>
							<div class="col-md-10">
								<textarea id="content" name="content" class="form-control" placeholder="內容"><?php echo $row['content'];?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="url">外連網址</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="url" name="url" value="<?php echo $row['url'];?>">
							</div>
						</div>

                        <input type="hidden" id="mt_id" name="mt_id" value="<?php echo $_GET['MT_id'];?>">
						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
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
							<button type="button"  class="btn btn-danger btn-block btn-flat" data-toggle="modal" data-target="#settingsModal1" onclick="clean_all()">重新輸入</button>
						</div>
						<div class="col-lg-6">
						<?php if(empty($_GET['Tb_index'])){?>
							<button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">儲存</button>
						<?php }else{?>
						    <button type="button" id="submit_btn" class="btn btn-info btn-block btn-raised">更新</button>
						<?php }?>
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


    //------------------------------ 刪圖 ---------------------------------
         $("#one_del_img").click(function(event) { 
			if (confirm('是否要刪除圖檔?')) {
			 var data={
			 	        Tb_index:'<?php echo $row["Tb_index"];?>',
                        pic:'<?php echo $row["pic"]?>',
                        type: 'delete'
			          };	
               ajax_in('manager.php', data, '成功刪除', 'no');
               $("#img_div").html('');
			}
		});

      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

