<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02
?>
<?php

 $pdo=pdo_conn();
  if ($_POST) { //新增、修改
    
     if (empty($_POST['Tb_index'])) {

       $param=array("Tb_index"=>'admin'.date('YmdHis').rand(0,99), 
                   "admin_per"=>$_POST['admin_per'], 
                    "admin_id"=>$_POST['admin_id'], 
                   "admin_pwd"=>aes_encrypt($aes_key, $_POST['admin_pwd']), 
                  "build_time"=>date('Y-m-d H:i:s'), 
                        "name"=>$_POST['name'], 
                       "phone"=>$_POST['phone'], 
                        "adds"=>$_POST['adds'],
                      "is_use"=>$_POST['is_use']=='1' ? '1':'0' );

       pdo_insert('sysAdmin', $param);//新增方法

     }
     else{

      if (empty($_POST['admin_pwd'])) {
       $param=array( "admin_per"=>$_POST['admin_per'], 
                    "admin_id"=>$_POST['admin_id'], 
                  "build_time"=>date('Y-m-d H:i:s'), 
                        "name"=>$_POST['name'], 
                       "phone"=>$_POST['phone'], 
                        "adds"=>$_POST['adds'], 
                      "is_use"=>$_POST['is_use'] 
                  );
        $where=array('Tb_index'=>$_POST['Tb_index']);

        pdo_update('sysAdmin', $param, $where);//更新方法
      }
      else{
        $param=array( "admin_per"=>$_POST['admin_per'], 
                    "admin_id"=>$_POST['admin_id'], 
                   "admin_pwd"=>aes_encrypt($aes_key, $_POST['admin_pwd']), 
                  "build_time"=>date('Y-m-d H:i:s'), 
                        "name"=>$_POST['name'], 
                       "phone"=>$_POST['phone'], 
                        "adds"=>$_POST['adds'], 
                      "is_use"=>$_POST['is_use'] 
                  );
       $where=array('Tb_index'=>$_POST['Tb_index']);

       pdo_update('sysAdmin', $param, $where);//更新方法
      }

     

     }
  }
  elseif ($_GET) {
    
    $Tb_index=$_GET['Tb_index'];
    
    $sql=$pdo->prepare("SELECT * FROM sysAdmin WHERE Tb_index=:Tb_index");
    $sql->execute(array(":Tb_index"=>$Tb_index));
    $row=$sql->fetch(PDO::FETCH_ASSOC);
    $zipcode=substr($row['adds'], 0,3);
    $adds=explode(',', $row['adds']);
  }
?>


<div class="wrapper wrapper-content animated fadeInRight">
  <div class="col-lg-12">
    <h2 class="text-primary">管理者 編輯</h2>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
        <div class="panel-body">
          <form class="form-horizontal">
            <div class="form-group">
              <label class="col-md-1 control-label" for="admin_per">權限名稱</label>
              <div class="col-md-2">
                <select id="admin_per" class="form-control">
                  <option value="admin" <?php echo $admin=$row['admin_per']=='admin' ? 'selected':'';?>>管理員</option>
                  <!--<option value="user" <?php //echo $admin=$row['admin_per']=='user' ? 'selected':'';?>>使用者</option>-->
                  <?php 
                    $sql_group=$pdo->prepare("SELECT Tb_index, Group_name FROM sysAdminGroup");
                    $sql_group->execute();
                    while ($row_group=$sql_group->fetch(PDO::FETCH_ASSOC)) {
                      $pre_group=$row['admin_per']==$row_group['Tb_index'] ? 'selected' : '';
                      echo "<option value='".$row_group['Tb_index']."' ".$pre_group.">".$row_group['Group_name']."</option>";
                    }
                  ?>
                </select>
              </div>
              <label class="col-md-1 control-label" for="admin_id">帳號</label>
              <div class="col-md-2">
                <input type="text" class="form-control" id="admin_id" value="<?php echo $row['admin_id'];?>">
              </div>
              <label class="col-md-1 control-label" for="admin_pwd">更新密碼</label>
              <div class="col-md-2">
                <input type="password" class="form-control" id="admin_pwd" value="">
              </div>
            </div>
            <div class="form-group">
                <label class="col-md-1 control-label" for="name">姓名</label>
                 <div class="col-md-2">
                  <input type="text" class="form-control" id="name" value="<?php echo $row['name'];?>">
                 </div>
                <label class="col-md-1 control-label" for="phone">電話</label>
                 <div class="col-md-2">
                  <input type="text" class="form-control" id="phone" value="<?php echo $row['phone'];?>">
                 </div>
                <label class="col-md-1 control-label" for="is_use">狀態</label>
                 <div class="col-md-2">
                  <input type="checkbox" class="checkbox" id="is_use" 
                  <?php echo $check=!isset($row['is_use']) || $row['is_use']==1 ? 'checked' : ''; ?> value="1">
                 </div>
            </div>
            <div class="form-group">
               <label class="col-md-1 control-label" for="adds">地址</label>
                 <div class="col-md-6">
                  <div class="twzipcode"></div>
                  <input type="text"  id="adds" class="adds" value="<?php echo $adds[1];?>">
                 </div>
               <div class="col-md-2">
              <?php if (empty($_GET['Tb_index'])) { ?>
                    <button type="button" id="admin_btn" class="btn btn-info btn-block btn-raised">儲存</button>
              <?php  }else{?>
                    <button type="button" id="admin_btn_up" class="btn btn-info btn-block btn-raised">更新</button>
                    <input type="hidden" id="Tb_index" value="<?php echo $row['Tb_index'];?>">
              <?php  }?>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  
</div>
<!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
  $(document).ready(function() {

    $('.twzipcode').twzipcode({
    'zipcodeSel'  : '<?php echo $zipcode;?>' // 此參數會優先於 countySel, districtSel
    });

    /* -- 新增 -- */
    $("#admin_btn").click(function(event) {
      var data={
                 admin_per: $("#admin_per").val(),
                    admin_id: $("#admin_id").val(),
                   admin_pwd: $("#admin_pwd").val(),
                        name: $("#name").val(),
                       phone: $("#phone").val(),
                        adds: $('[name="zipcode"]').val()+$('[name="county"]').val()+$('[name="district"]').val()+","+$("#adds").val(),
                      is_use: $(":checked#is_use").val()
               };
      ajax_in('manager.php', data, '新增管理者', 'admin.php');
    });

    /* -- 修改 -- */
    $("#admin_btn_up").click(function(event) {
      var data={
                    Tb_index: $("#Tb_index").val(),
                   admin_per: $("#admin_per").val(),
                    admin_id: $("#admin_id").val(),
                   admin_pwd: $("#admin_pwd").val(),
                        name: $("#name").val(),
                       phone: $("#phone").val(),
                        adds: $('[name="zipcode"]').val()+$('[name="county"]').val()+$('[name="district"]').val()+","+$("#adds").val(),
                      is_use: $(":checked#is_use").val()
               };

      ajax_in('manager.php', data, '更新管理者', 'admin.php');
    });

  }); //JQUERY END
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
