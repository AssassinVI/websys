<?php 
include("../../core/page/header01.php");//載入頁面heaer01
include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 

if ($_GET) {

   if (!empty($_GET['Tb_index'])) {//刪除

    $where=array('Tb_index'=>$_GET['Tb_index']);
   	 pdo_delete('appWebPush', $where);
   }
   
   $pdo=pdo_conn();
   $sql=$pdo->prepare("SELECT Tb_index, title, StartDate FROM appWebPush WHERE webLang=:webLang ORDER BY StartDate DESC");
   $sql->execute(array( "webLang"=>$weblang));
}
?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name']?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">
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
								<th>名稱</th>
								<th>更新日期</th>
								<th></th>
								<th class="text-right">管理</th>

							</tr>
						</thead>
						<tbody>

						<?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
							<tr>
								<td><?php echo $i?></td>
								<td><?php echo $row['title'] ?></td>
								<td><?php echo $row['StartDate']?></td>
								<td>
									<!-- Loading 圖示 -->
								<div id="loading" style="display: none;" class="sk-spinner sk-spinner-fading-circle">
                                    <div class="sk-circle1 sk-circle"></div>
                                    <div class="sk-circle2 sk-circle"></div>
                                    <div class="sk-circle3 sk-circle"></div>
                                    <div class="sk-circle4 sk-circle"></div>
                                    <div class="sk-circle5 sk-circle"></div>
                                    <div class="sk-circle6 sk-circle"></div>
                                    <div class="sk-circle7 sk-circle"></div>
                                    <div class="sk-circle8 sk-circle"></div>
                                    <div class="sk-circle9 sk-circle"></div>
                                    <div class="sk-circle10 sk-circle"></div>
                                    <div class="sk-circle11 sk-circle"></div>
                                    <div class="sk-circle12 sk-circle"></div>
                                </div>
								</td>

								<td class="text-right">

								<button type="button" title="<?php echo $row['title'];?>" Tb_index="<?php echo $row['Tb_index'];?>" class="btn btn-rounded btn-success btn-sm webPush">
								  <i class="fa fa-newspaper-o" aria-hidden="true"></i> 推播</button>

								<a href="manager.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?MT_id=<?php echo $_GET['MT_id']?>&Tb_index=<?php echo $row['Tb_index'];?>" 
								   onclick="if (!confirm('確定要刪除 [<?php echo $row['title']?>] ?')) {return false;}">
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
     //--------------------------------- 發送推播 -----------------------------------
      $('.webPush').click(function(event) {
      	if (confirm('確定要發送［'+$(this).attr('title')+'］此則推播??')) {
      		$.ajax({
      	 	url: 'webPush_ajax.php',
      	 	type: 'POST',
      	 	data: {Tb_index: $(this).attr('Tb_index')},
      	 	beforeSend:function () {//請求之前
			    	$("#loading").show();
		    },
      	 	success:function () {
      	 		alert('發送成功!');
      	 	},
      	 	 complete:function () { //請求結束
			    	$("#loading").hide();
			}
      	  });
      	}
      });
	});
</script>
<?php  include("../../core/page/footer02.php");//載入頁面footer02.php?>
