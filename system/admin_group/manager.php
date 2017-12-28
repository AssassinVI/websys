<?php include("../../core/page/header01.php");//載入頁面heaer01?>
 <link rel="stylesheet" type="text/css" href="../../css/jquery.treetable.css">
 <style type="text/css">
   #group_table tr:hover{ background-color: #c1eae2; }
   .checkbox{ display: inline-block; }
   .unit_group{  }
   .folder_group{ background: #f3f3f4; }
 </style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php
 if ($_POST) {
   $Tb_index=empty($_POST['Tb_index'])? '':$_POST['Tb_index'];
  //------------ 新增 ------------
  if (empty($Tb_index)) {
     $Tb_index='group'.date('YmdHis').rand(0,99);
     $Permissions='';
     for ($i=0; $i <count($_POST['group_check']) ; $i++) { 
       $Permissions.=$_POST['group_check'][$i].',';
     }

   $param=array(
       'Tb_index'=>$Tb_index,
       'Group_name'=>$_POST['Group_name'],
       'StartDate'=>date('Y-m-d'),
       'Permissions'=>$Permissions,
       'is_use'=>$_POST['is_use']
    );
   pdo_insert('sysAdminGroup', $param);
   location_up('admin.php','成功新增');
     
  }
  //------------ 更新 -------------
  else{
    $Permissions='';
     for ($i=0; $i <count($_POST['group_check']) ; $i++) { 
       $Permissions.=$_POST['group_check'][$i].',';
     }

    $param=array(
       'Group_name'=>$_POST['Group_name'],
       'Permissions'=>$Permissions,
       'is_use'=>$_POST['is_use']
    ); 
    $where=array( 'Tb_index'=>$Tb_index );
    pdo_update('sysAdminGroup', $param, $where);
    location_up('admin.php','成功更新');
  }
 }

 if ($_GET) {
    $Tb_index=empty($_GET['Tb_index'])? '':$_GET['Tb_index'];
    $where=array('Tb_index'=>$Tb_index);
    $row_group=pdo_select('SELECT * FROM sysAdminGroup WHERE Tb_index=:Tb_index', $where);

    $Permissions=explode(',', $row_group['Permissions']);
 }
?>



<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary">群組權限 列表</h2>
		
	</div>
	<div class="row">
		<div class="col-lg-9">
			<div class="panel panel-default">
			<div class="panel-body">

       <form id="put_form" action="manager.php" method="POST"  class="form-horizontal">

        <div class="form-group">
              <label class="col-md-1 control-label" for="Group_name">群組名稱</label>
              <div class="col-md-5">
                <input type="text" class="form-control" id="Group_name" name="Group_name" value="<?php echo $row_group['Group_name'];?>">
              </div>

              <label class="col-md-1 control-label" for="Group_name">是否啟用</label>
                <div class="col-md-5">
                <input style="width: 20px; height: 20px;" id="is_use" name="is_use" type="checkbox" value="1" <?php echo $check=!isset($row_group['is_use']) || $row_group['is_use']==1 ? 'checked' : ''; ?>  />
              </div>
        </div>


        <input type="hidden" name="Tb_index" value="<?php echo $Tb_index;?>">


<div class="table-responsive"> 
<table id="product-table"  class="table table-hover ">
  <caption style=" text-align:right">

  <a href="#" onclick="jQuery('#product-table').treetable('expandAll'); return false;">全部展開</a> | <a href="#" onclick="jQuery('#product-table').treetable('collapseAll'); return false;">全部收合</a>
  </caption>
  <thead>
    <tr>
      <th>名稱</th>
      <th>權限勾選</th>
      <!--<th>管理</th>-->
    </tr>
  </thead>
  <tbody id="group_table">

  <?php 
  $pdo=pdo_conn();
  $sql=$pdo->prepare("SELECT * FROM maintable WHERE weblang=:weblang ORDER BY OrderBy DESC,Tb_index ASC");
  $sql->execute(array(':weblang'=>$weblang));

  while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
    
    //------------------ 判斷目前單元是否存在權限陣列裡 -------------------
    $in_Permissions=in_array($row['Tb_index'], $Permissions);
   
   /* -------------- 第一層 --------------- */
    if (empty($row['parent_id'])) {
       
       // -- 單元 --
  	   if ($row['is_data']=='1') { 
          $site_txt=unit_txt($row['Tb_index'], 'no', $row['MT_Name'], $row['OrderBy'], $weblang, $in_Permissions);

       // -- 資料夾 --   
        }else{  
          $site_txt=folder_txt($row['Tb_index'], 'no', $row['MT_Name'], $row['OrderBy'], $weblang, $in_Permissions);

          // -- 子樹狀 --
           $sql_tree=$pdo->prepare("SELECT * FROM maintable WHERE parent_id=:parent_id ORDER BY OrderBy DESC,Tb_index ASC");
           $sql_tree->execute(array(':parent_id'=>$row['Tb_index']));
           while ($row_tree=$sql_tree->fetch(PDO::FETCH_ASSOC)) {

             $site_txt.=tree_tb($row_tree['Tb_index'], $row_tree['parent_id'], $row_tree['MT_Name'], $row_tree['OrderBy'], $row_tree['is_data'], $weblang, $Permissions);
           }
        }
        echo $site_txt;
    }
 }
  $pdo=NUll;

/* ====================== 子樹狀階層 ====================== */
 function tree_tb($Tb_index, $parent_id, $MT_Name, $OrderBy, $is_data, $weblang, $Permissions)
 {
      //------------------ 判斷目前單元是否存在權限陣列裡 -------------------
      $in_Permissions=in_array($Tb_index, $Permissions);

  	   if ($is_data=='1') { // -- 子單元 --
          $txt=unit_txt($Tb_index, $parent_id, $MT_Name, $OrderBy, $weblang, $in_Permissions);
        }else{  // -- 子資料夾 --
           $txt=folder_txt($Tb_index, $parent_id, $MT_Name, $OrderBy, $weblang, $in_Permissions);
            $pdo=pdo_conn();
            $sql=$pdo->prepare("SELECT * FROM maintable WHERE parent_id=:parent_id ORDER BY OrderBy DESC,Tb_index ASC");
            $sql->execute(array(':parent_id'=>$Tb_index));
           while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {

           	 $txt.=tree_tb($row['Tb_index'], $row['parent_id'], $row['MT_Name'], $row['OrderBy'], $row['is_data'], $weblang, $Permissions);
           }
           $pdo=NULL;
        }
    return $txt;
 }

/* ====================== 單元HTML ====================== */
  function unit_txt($Tb_index, $parent_id, $MT_Name, $OrderBy, $weblang, $in_Permissions)
  {
  	if ($parent_id!='no') {
       $txt='<tr class="unit_group" data-tt-id="'.$Tb_index.'" data-tt-parent-id="'.$parent_id.'" >';
       $txt.='<td class="parent_td">
                     <span style="color:#949494"><strong><i class="fa fa-file-text "></i> '.$MT_Name.' </strong></span>
                  </td>';
  	}
  	else{
       $txt='<tr class="unit_group" data-tt-id="'.$Tb_index.'" >';
       $txt.='<td class="parent_td">
                     <span style="color:#179c81;"><strong><i class="fa fa-file-text "></i> '.$MT_Name.' </strong></span>
                  </td>';
  	}

          $checked=$in_Permissions ? 'checked' : '';
           $txt.= '<td><input name="group_check[]" type="checkbox" class="checkbox" '.$checked.' value="'.$Tb_index.'"> </td>';
            $txt.=' </tr>';
     return $txt;
  }

/* ====================== 資料夾HTML ====================== */
  function folder_txt($Tb_index, $parent_id, $MT_Name, $OrderBy, $weblang, $in_Permissions)
  {
  	if ($parent_id!='no') {
       $txt='<tr class="folder_group" data-tt-id="'.$Tb_index.'" data-tt-parent-id="'.$parent_id.'" >';
       $txt.='<td class="parent_td">
                     <span style="color:#808080"><strong><i class="fa fa-folder-open "></i> '.$MT_Name.' </strong></span>
                  </td>';
  	}
  	else{
       $txt='<tr class="folder_group" style="background: #dff6f2;" data-tt-id="'.$Tb_index.'" >';
       $txt.='<td class="parent_td">
                     <span style="color:#179c81"><strong><i class="fa fa-folder-open "></i> '.$MT_Name.' </strong></span>
                  </td>';
  	}     
          $checked=$in_Permissions ? 'checked' : '';
           $txt.= '<td><input name="group_check[]" type="checkbox" class="checkbox" '.$checked.' value="'.$Tb_index.'"> </td>';

          $txt.='</tr>';
     return $txt;
  }
  ?>


<?php if ($_SESSION['admin_per'] == 'admin') {?>
 <tr class="folder_group branch expanded" style="background: #dff6f2;" data-tt-id="systeam">
   <td class="parent_td">
     <span style="color:#179c81">
      <strong><i class="fa fa-folder-open "></i> 系統設定 </strong>
     </span>
    </td>
    <td>
      <input name="group_check[]" type="checkbox" class="checkbox" <?php echo $sys_Permissions=in_array('systeam',$Permissions)?'checked':'';?> value="systeam"> 
    </td>
  </tr>

  <tr class="unit_group leaf expanded" data-tt-id="sys1" data-tt-parent-id="systeam" style="display: table-row;">
    <td class="parent_td">
      <span style="color:#949494"><strong><i class="fa fa-file-text "></i> 語系設定 </strong></span>
    </td>
    <td>
      <input name="group_check[]" type="checkbox" class="checkbox" <?php echo $sys_Permissions=in_array('sys1',$Permissions)?'checked':'';?> value="sys1"> 
    </td> 
  </tr>

  <tr class="unit_group leaf expanded" data-tt-id="sys2" data-tt-parent-id="systeam" style="display: table-row;">
    <td class="parent_td">
      <span style="color:#949494"><strong><i class="fa fa-file-text "></i> 網頁架構 </strong></span>
    </td>
    <td>
      <input name="group_check[]" type="checkbox" class="checkbox" <?php echo $sys_Permissions=in_array('sys2',$Permissions)?'checked':'';?> value="sys2"> 
    </td> 
  </tr>

  <tr class="unit_group leaf expanded" data-tt-id="sys3" data-tt-parent-id="systeam" style="display: table-row;">
    <td class="parent_td">
      <span style="color:#949494"><strong><i class="fa fa-file-text "></i> 模組管理 </strong></span>
    </td>
    <td>
      <input name="group_check[]" type="checkbox" class="checkbox" <?php echo $sys_Permissions=in_array('sys3',$Permissions)?'checked':'';?> value="sys3"> 
    </td> 
  </tr>

  <tr class="unit_group leaf expanded" data-tt-id="sys4" data-tt-parent-id="systeam" style="display: table-row;">
    <td class="parent_td">
      <span style="color:#949494"><strong><i class="fa fa-file-text "></i> 管理者管理 </strong></span>
    </td>
    <td>
      <input name="group_check[]" type="checkbox" class="checkbox" <?php echo $sys_Permissions=in_array('sys4',$Permissions)?'checked':'';?> value="sys4"> 
    </td> 
  </tr>

  <tr class="unit_group leaf expanded" data-tt-id="sys5" data-tt-parent-id="systeam" style="display: table-row;">
    <td class="parent_td">
      <span style="color:#949494"><strong><i class="fa fa-file-text "></i> 群組權限管理 </strong></span>
    </td>
    <td>
      <input name="group_check[]" type="checkbox" class="checkbox" <?php echo $sys_Permissions=in_array('sys5',$Permissions)?'checked':'';?> value="sys5"> 
    </td> 
  </tr>
<?php }?>
    
  </tbody>
</table>



			  	</div>
       </form>
			</div>
		</div>
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
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>



<script src="../../js/jquery.treetable.js"></script>
    <script>
      $("#product-table").treetable({ expandable: true });
	  $('#product-table').treetable('expandAll');

      // Highlight selected row
      $("#product-table tbody").on("mousedown", "tr", function() {
        $(".selected").not(this).removeClass("selected");
        $(this).toggleClass("selected");
      });

      // Drag & Drop Example Code
      /*$("#product-table .file, #product-table .folder").draggable({
        helper: "clone",
        opacity: .75,
        refreshPositions: true, // Performance?
        revert: "invalid",
        revertDuration: 300,
        scroll: true
      });*/

      $("#product-table .folder").each(function() {
        $(this).parents("#product-table tr").droppable({
          accept: ".file, .folder",
          drop: function(e, ui) {
            var droppedEl = ui.draggable.parents("tr");
            $("#product-table").treetable("move", droppedEl.data("ttId"), $(this).data("ttId"));
          },
          hoverClass: "accept",
          over: function(e, ui) {
            var droppedEl = ui.draggable.parents("tr");
            if(this != droppedEl[0] && !$(this).is(".expanded")) {
              $("#product-table").treetable("expandNode", $(this).data("ttId"));
            }
          }
        });
      });

      $("form#reveal").submit(function() {
        var nodeId = $("#revealNodeId").val()

        try {
          $("#product-table").treetable("reveal", nodeId);
        }
        catch(error) {
          alert(error.message);
        }

        return false;
      });


      $("#submit_btn").click(function(event) {
        $("#put_form").submit();
      });


      //------------------ 資料夾權限 ---------------------
      $(".folder_group").find('[name="group_check[]"]').change(function(event) {

        var folder_id=$(this).parents('.folder_group').attr('data-tt-id');

         if ($(this).prop('checked')) {
          folder_array_check(folder_id);
         }
         else{
           folder_array_uncheck(folder_id);
         }
      });


      //-------------------- 單元權限 ------------------------
      $(".unit_group").find('[name="group_check[]"]').change(function(event){
         var unit_parent_id=$(this).parents('.unit_group').attr('data-tt-parent-id');

         if ($(this).prop('checked')) {
           unit_array_check(unit_parent_id);
         }
         else{
          unit_array_uncheck( unit_parent_id);
         }
      });


    //---------------------------------------- 資料夾點擊遞迴 ---------------------------------------------
    function folder_array_check(parent_id) {
       unit_array_check( parent_id);
       if ($('[data-tt-parent-id="'+parent_id+'"]').length>0) {
          $('[data-tt-parent-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', true);
          $('[data-tt-parent-id="'+parent_id+'"]').each(function(index, el) {
                var new_parent_id=$(this).attr('data-tt-id');
                folder_array_check( new_parent_id);
            });
        }
        else{
          $('[data-tt-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', true);
        }
    }


    //---------------------------------------- 資料夾取消遞迴 ---------------------------------------------
    function folder_array_uncheck(parent_id) {
       unit_array_uncheck( parent_id);
       if ($('[data-tt-parent-id="'+parent_id+'"]').find('[name="group_check[]"]:checked').length>0) {
         $('[data-tt-parent-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', false);
          $('[data-tt-parent-id="'+parent_id+'"]').each(function(index, el) {
                var new_parent_id=$(this).attr('data-tt-id');
                folder_array_uncheck( new_parent_id);
            });
        }
        else{
          $('[data-tt-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', false);
        }
    }


    //---------------------------------------- 單元點擊遞迴 ---------------------------------------------
      function unit_array_check( parent_id) {
        if ($('[data-tt-id="'+parent_id+'"]').attr('data-tt-parent-id')!=undefined) {
          $('[data-tt-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', true);
          var new_parent_id=$('[data-tt-id="'+parent_id+'"]').attr('data-tt-parent-id');
          unit_array_check( new_parent_id);
        }
        else{
          $('[data-tt-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', true);
        }
      }

      //---------------------------------------- 單元取消遞迴 ---------------------------------------------
      function unit_array_uncheck( parent_id) {
        if ($('[data-tt-id="'+parent_id+'"]').attr('data-tt-parent-id')!=undefined) {
          if ($('[data-tt-parent-id="'+parent_id+'"]').find('[name="group_check[]"]:checked').length<1){
            $('[data-tt-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', false);
            var new_parent_id=$('[data-tt-id="'+parent_id+'"]').attr('data-tt-parent-id');
            unit_array_uncheck( new_parent_id);
          }
        }
        else{
          if ($('[data-tt-parent-id="'+parent_id+'"]').find('[name="group_check[]"]:checked').length<1) {
            $('[data-tt-id="'+parent_id+'"]').find('[name="group_check[]"]').prop('checked', false);
          }
        }
      }

    </script> 
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>



