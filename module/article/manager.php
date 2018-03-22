<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
  // ======================== 刪除 ===========================
  	//----------------------- 代表圖刪除 -------------------------------
    if (!empty($_POST['type']) && $_POST['type']=='delete') { 
    	if (!empty($_POST['aPic'])) {
    		$param=['aPic'=>''];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('appArticle', $param, $where);
            unlink('../../img/'.$_POST['aPic']);
    	}else{
        //----------------------- 多檔刪除 -------------------------------
    		$sel_where=['Tb_index'=>$_POST['Tb_index']];
    		$otr_file=pdo_select('SELECT OtherFile FROM appArticle WHERE Tb_index=:Tb_index', $sel_where);
    		$otr_file=explode(',', $otr_file['OtherFile']);
    		for ($i=0; $i <count($otr_file)-1 ; $i++) { //比對 
    			 if ($otr_file[$i]!=$_POST['OtherFile']) {
    			 	$new_file.=$otr_file[$i].',';
    			 }else{
    			 	 unlink('../../other_file/'.$_POST['OtherFile']);
    			 }
    		}
    		$param=['OtherFile'=>$new_file];
            $where=['Tb_index'=>$_POST['Tb_index']];
            pdo_update('appArticle', $param, $where);
    	}
       exit();
  	}
	if (empty($_POST['Tb_index'])) {//新增
		$Tb_index='article'.date('YmdHis').rand(0,99);
     
     //===================== 代表圖 ========================
      if (!empty($_FILES['aPic']['name'])){

      	 $type=explode('.', $_FILES['aPic']['name']);
      	 $aPic=$Tb_index.'.'.$type[count($type)-1];
         fire_upload('aPic', $aPic); 
      }
     //===================== 多圖檔 ========================
      if (!empty($_FILES['OtherFile']['name'][0])){

      	
        for ($i=0; $i <count($_FILES['OtherFile']['name']) ; $i++) { 

             $type=explode('.', $_FILES['OtherFile']['name'][$i]);
      	     $OtherFile.=$Tb_index.'_other_'.$i.'.'.$type[count($type)-1].',';
             more_other_upload('OtherFile', $i, $Tb_index.'_other_'.$i.'.'.$type[count($type)-1]);
        }
      }
	$param=  ['Tb_index'=>$Tb_index,
			              'mt_id'=>$_POST['mt_id'],
			             'aTitle'=>$_POST['aTitle'],
			          'aAbstract'=>$_POST['aAbstract'],
			               'aPic'=>$aPic,
			          'OtherFile'=>$OtherFile,
			               'aTXT'=>$_POST['aTXT'],
			               'aUrl'=>$_POST['aUrl'],
			         'YoutubeUrl'=>$_POST['YoutubeUrl'],
			          'StartDate'=>date('Y-m-d'),
			         'UpdateDate'=>date('Y-m-d'),
			        'OnLineOrNot'=>$_POST['OnLineOrNot'],
			            'webLang'=>$weblang
			         ];
	pdo_insert('appArticle', $param);
	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功新增');
   }
   else{  //修改
   	$Tb_index =$_POST['Tb_index'];

   	 //===================== 代表圖 ========================
      if (!empty($_FILES['aPic']['name'])) {

      	 $type=explode('.', $_FILES['aPic']['name']);
      	 $aPic=$Tb_index.'.'.$type[count($type)-1];
         fire_upload('aPic', $aPic);
        $aPic_param=['aPic'=>$aPic];
        $aPic_where=['Tb_index'=>$Tb_index];
        pdo_update('appArticle', $aPic_param, $aPic_where);

      }
      //-------------------- 多檔上傳 ------------------------------
      if (!empty($_FILES['OtherFile']['name'][0])) {
      	$sel_where=['Tb_index'=>$Tb_index];
      	$now_file =pdo_select("SELECT OtherFile FROM appArticle WHERE Tb_index=:Tb_index", $sel_where);
      	if (!empty($now_file['OtherFile'])) {
      	   $sel_file=explode(',', $now_file['OtherFile']);
           $file_num=explode('_', $sel_file[count($sel_file)-2]);
           $file_num=explode('.', $file_num[2]);
           $file_num=(int)$file_num[0]+1;
      	}else{
      	   $file_num=0;
      	}
      	for ($i=0; $i <count($_FILES['OtherFile']['name']) ; $i++) { 

      		 	   $type=explode('.', $_FILES['OtherFile']['name'][$i]);
      		 	   $OtherFile.=$Tb_index.'_other_'.($file_num+$i).'.'.$type[count($type)-1].',';
      		 	   more_other_upload('OtherFile', $i, $Tb_index.'_other_'.($file_num+$i).'.'.$type[count($type)-1]);
      	}

      	$OtherFile=$now_file['OtherFile'].$OtherFile;
      	 
        $OtherFile_param=['OtherFile'=>$OtherFile];
        $OtherFile_where=['Tb_index'=>$Tb_index];
        pdo_update('appArticle', $OtherFile_param, $OtherFile_where);
      }
      	//--------------------------- END -----------------------------------
    
    
    $param=[  
		              'mt_id'=>$_POST['mt_id'],
    	             'aTitle'=>$_POST['aTitle'],
		          'aAbstract'=>$_POST['aAbstract'],
		               'aTXT'=>$_POST['aTXT'],
		               'aUrl'=>$_POST['aUrl'],
		         'YoutubeUrl'=>$_POST['YoutubeUrl'],
		         'UpdateDate'=>date('Y-m-d'),
		        'OnLineOrNot'=>$_POST['OnLineOrNot']
		          ];
    $where= ['Tb_index'=>$Tb_index] ;
	pdo_update('appArticle', $param, $where);
	
	location_up('admin.php?MT_id='.$_POST['mt_id'],'成功更新');
   }
}
if ($_GET) {
 	$where=['Tb_index'=>$_GET['Tb_index']];
 	$row=pdo_select('SELECT * FROM appArticle WHERE Tb_index=:Tb_index', $where);
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
							<label class="col-md-2 control-label" for="aTitle">標題名稱</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="aTitle" name="aTitle" value="<?php echo $row['aTitle'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="aPic">代表圖檔</label>
							<div class="col-md-10">
								<input type="file" name="aPic" class="form-control" accept="image/*" id="aPic" onchange="file_viewer_load_new(this, '#img_box')">
							</div>
						</div>

						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="img_box" class="col-md-4">
								
							</div>
						<?php if(!empty($row['aPic'])){?>
							<div  class="col-md-4">
							   <div id="img_div" >
							    <p>目前圖檔</p>
								 <button type="button" id="one_del_img"> X </button>
								  <span class="img_check"><i class="fa fa-check"></i></span>
								  <img id="one_img" src="../../img/<?php echo $row['aPic'];?>" alt="請上傳代表圖檔">
								</div>
							</div>
						<?php }?>		
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="aAbstract">摘要內容</label>
							<div class="col-md-10">
								<textarea class="form-control" id="aAbstract" name="aAbstract" placeholder="摘要內容"><?php echo $row['aAbstract'];?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="ckeditor">詳細內容</label>
							<div class="col-md-10">
								<textarea id="ckeditor" name="aTXT" placeholder="詳細內容"><?php echo $row['aTXT'];?></textarea>
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="aUrl">相關連結</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="aUrl" name="aUrl" value="<?php echo $row['aUrl'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="OtherFile">相關附件上傳</label>
							<div class="col-md-10">
								<input type="file" multiple name="OtherFile[]" class="form-control" id="OtherFile" onchange="file_load_new(this, '#other_div', 'manager.php', 'OtherFile')">
								<span class="help-block m-b-none">可批次上傳多個檔</span>
							</div>
						</div>



						<div class="form-group">
						   <label class="col-md-2 control-label" ></label>
						   <div id="other_div" class="col-md-10">
								
							</div>

							<div class="col-md-10">
				<?php if(!empty($row['OtherFile'])){
                                  
                          $otherFile=explode(',', $row['OtherFile']);
                          for ($i=0; $i <count($otherFile)-1 ; $i++) { 
                          	 $other_txt='<div class="file_div" >
                          	              <p>目前檔案</p>
                          	               <button type="button" class="one_del_file"> X </button>
                          	               <img id="one_img" src="../../other_file/'.$otherFile[$i].'" alt="">
                          	               <input type="hidden" value="'.$otherFile[$i].'">
                          	             </div>';
                          	 echo $other_txt;
                          }
                        }
			    ?>
			            </div>

						</div>

						<div class="form-group">
							<label class="col-md-2 control-label" for="YoutubeUrl">嵌入youtube連結</label>
							<div class="col-md-10">
								<input type="text" class="form-control" id="YoutubeUrl" name="YoutubeUrl" value="<?php echo $row['YoutubeUrl'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-md-2 control-label" for="OnLineOrNot">是否上線</label>
							<div class="col-md-10">
								<input style="width: 20px; height: 20px;" id="OnLineOrNot" name="OnLineOrNot" type="checkbox" value="1" <?php echo $check=!isset($row['OnLineOrNot']) || $row['OnLineOrNot']==1 ? 'checked' : ''; ?>  />
							</div>
						</div>

						<input type="hidden" id="Tb_index" name="Tb_index" value="<?php echo $_GET['Tb_index'];?>">
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
						<div class="col-lg-6">
							<button type="button" class="btn btn-danger btn-block btn-flat" data-toggle="modal" data-target="#settingsModal1" onclick="clean_all()">重設表單</button>
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
			 	        Tb_index: $("#Tb_index").val(),
                            aPic: '<?php echo $row["aPic"]?>',
                            type: 'delete'
			          };	
               ajax_in('manager.php', data, '成功刪除', 'no');
               $("#img_div").html('');
			}
		});
      //------------------------------ 刪檔 ---------------------------------
          $(".one_del_file").click(function(event) { 
			if (confirm('是否要刪除檔案?')) {
			 var data={
			 	        Tb_index: $("#Tb_index").val(),
                       OtherFile: $(this).next().next().val(),
                            type: 'delete'
			          };	
               ajax_in('manager.php', data, '成功刪除', 'no');
               $(this).parent().html('');
			}
		});
      });
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>

