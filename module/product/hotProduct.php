<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_POST) {
   // -- 更新排序 --
    $data=array("OrderBy"=>$_POST['OrderBy']);
    $where=array("Tb_index"=>$_POST['Tb_index']);
    pdo_update('appProduct', $data, $where);
}

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//刪除

    $where=array('Tb_index'=>$_GET['Tb_index']);

   	$del_row=pdo_select('SELECT aPic, OtherFile, pro_video FROM appProduct WHERE Tb_index=:Tb_index', $where);
   	if (isset($del_row['pro_video'])) { unlink('../../video/'.$del_row['pro_video']); }
   	if (isset($del_row['aPic'])) { unlink('../../img/'.$del_row['aPic']); }
    if (isset($del_row['OtherFile'])) { 

      $OtherFile=explode(',', $del_row['OtherFile']);
      for ($i=0; $i <count($OtherFile)-1 ; $i++) { 
      	 unlink('../../img/'.$OtherFile[$i]); 
      }
     }

   	 pdo_delete('appProduct', $where);
   }
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT Tb_index, aTitle, StartDate, EndDate, OrderBy, OnLineOrNot, aPic FROM appProduct WHERE HotPro='1' AND webLang=:webLang  ORDER BY OrderBy DESC, StartDate DESC");
   $sql->execute(array( "mt_id"=>$_GET['MT_id'], "webLang"=>$weblang));
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

        <button id="sort_btn" type="button" class="btn btn-default">
        <i class="fa fa-sort-amount-desc"></i> 更新排序</button>

	    <a href="manager.php?MT_id=<?php echo $_GET['MT_id'];?>">
        <button type="button" class="btn btn-default">
        <i class="fa fa-plus" aria-hidden="true"></i> 新增</button>
        </a>
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
								<th>產品圖示</th>
								<th>產品名稱</th>
								<th>排序</th>
								<th>上線日期</th>
								<th>下線日期</th>
								<th>目前狀態</th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
							<tr>
								<td><?php echo $i?></td>
								<td><img src="../../img/<?php echo $row['aPic'];?>" style="width: 100px;"></td>
								<td><?php echo $row['aTitle'] ?></td>
								<td><input type="number" class="sort_in" name="OrderBy" Tb_index="<?php echo $row['Tb_index'];?>" value="<?php echo $row['OrderBy'] ?>"></td>
								<td><?php echo $row['StartDate']?></td>
								<td><?php echo $row['EndDate']?></td>
								<td><?php echo $online= $row['OnLineOrNot']==1 ? '上線中' : '已下線';?></td>
								

								<td class="text-right">

								<a href="manager.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['aTitle']?>] ?')) {return false;}">
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
			$(".sort_in").each(function(index, el) {
             var data={ 
                        OrderBy: $(this).val(),
                       Tb_index: $(this).attr('Tb_index') 
                      };
             ajax_in('admin.php', data, 'no', 'no');
          });
		 alert('更新排序');	
         location.replace('admin.php?MT_id=<?php echo $_GET['MT_id'];?>');
		});
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
