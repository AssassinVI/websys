<?php  include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
	.is_deal{ color: green; }
	.no_deal{ color: red; }
</style>
<?php  include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
   // -- 更新排序 --
  for ($i=0; $i <count($_POST['OrderBy']) ; $i++) { 
    $data=array("OrderBy"=>$_POST['OrderBy'][$i]);
    $where=array("Tb_index"=>$_POST['Tb_index'][$i]);
    pdo_update('appArticle', $data, $where);
  }
}

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//刪除

    $where=array('Tb_index'=>$_GET['Tb_index']);

   	$del_row=pdo_select('SELECT aPic, OtherFile FROM appArticle WHERE Tb_index=:Tb_index', $where);
   	if (isset($del_row['aPic'])) { unlink('../../img/'.$del_row['aPic']); }
    if (isset($del_row['OtherFile'])) {

      $OtherFile=explode(',', $del_row['OtherFile']);
      for ($i=0; $i <count($OtherFile)-1 ; $i++) { 
      	 unlink('../../img/'.$OtherFile[$i]); 
      }
   }

   	 pdo_delete('appArticle', $where);
   }
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT * FROM appService ORDER BY StartDate DESC");
   $sql->execute();
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

       <!-- <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>-->

	    <!--<a href="manager.php?MT_id=<?php echo $_GET['MT_id'];?>">
        <button type="button" class="btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
        </a>-->
	  </div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
					<table class="table no-margin">
						<thead>
							<tr>
								<th>#</th>
								<th>單號</th>
								<th>經銷商名稱</th>
								<th>產品型號</th>
								<th>處理狀態</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['Tb_index'] ?></td>
								<td><?php echo $row['sellName'] ?></td>
								<td><?php echo $row['pro_id']?></td>
								<td>
								<?php 
                                  if ($row['is_deal']=='1') {
                                  	echo "<span class='is_deal'>已處理</span>";
                                  }else{
                                  	echo "<span class='no_deal'>未處理</span>";
                                  }
								?>
								</td>

								<td class="text-right">

								<a href="manager.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['Tb_index']?>] ?')) {return false;}">
								<button type="button" class="btn btn-rounded btn-warning btn-sm">
								<i class="fa fa-trash" aria-hidden="true"></i>
								刪除</button>
								</a>

					
								</td>
							</tr>
						<?php $i++; }?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php  include("../../core/page/footer01.php");//載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#sort_btn").click(function(event) {
		        
        var arr_OrderBy=new Array();
        var arr_Tb_index=new Array();

          $(".sort_in").each(function(index, el) {
             
             arr_OrderBy.push($(this).val());
             arr_Tb_index.push($(this).attr('Tb_index'));
          });

          var data={ 
                        OrderBy: arr_OrderBy,
                       Tb_index: arr_Tb_index 
                      };
             ajax_in('admin.php', data, 'no', 'no');

          alert('更新排序');
         location.replace('admin.php?MT_id=<?php echo $_GET['MT_id'];?>');
		});
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
