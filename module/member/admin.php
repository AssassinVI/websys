<?php include "../../core/page/header01.php"; //載入頁面heaer01?>
 <style type="text/css">
     #table_id_example{  }
 </style>
<?php include "../../core/page/header02.php"; //載入頁面heaer02?>
<?php

if ($_GET) {

	if (!empty($_GET['Tb_index'])) {
		//刪除
		$where = array('Tb_index' => $_GET['Tb_index']);
		pdo_delete('appMember', $where);
	}

	$pdo = pdo_conn();
	$sql = $pdo->prepare("SELECT Tb_index, name, phone, email FROM appMember ORDER BY StartDate DESC");
	$sql->execute();
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name'] ?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

	    <a href="manager.php?MT_id=<?php echo $_GET['MT_id']; ?>">
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
					<table id="table_id_example" class="display">
						<thead>
							<tr>
								<th>#</th>
								<th>名稱</th>
								<th>電話</th>
								<th>E-Mail</th>
								<th >管理</th>

							</tr>
						</thead>
					</table>

				</div>
			</div>
		</div>
	</div>
</div>
</div><!-- /#page-content -->
<?php include "../../core/page/footer01.php"; //載入頁面footer01.php?>
<script type="text/javascript">
	$(document).ready(function() {
        $('#table_id_example').DataTable({
        	language:{
        "sProcessing": "處理中...",
        "sLengthMenu": "顯示 _MENU_ 項結果",
        "sZeroRecords": "没有匹配結果",
        "sInfo": "顯示第 _START_ 至 _END_ 項結果，共 _TOTAL_ 項",
        "sInfoEmpty": "顯示第 0 至 0 項結果，共 0 項",
        "sInfoFiltered": "(由 _MAX_ 項結果過濾)",
        "sInfoPostFix": "",
        "sSearch": "搜索:",
        "sUrl": "",
        "sEmptyTable": "表中數據為空",
        "sLoadingRecords": "載入中...",
        "sInfoThousands": ",",
        "oPaginate": {
            "sFirst": "首頁",
            "sPrevious": "上一頁",
            "sNext": "下一頁",
            "sLast": "末頁"
        },
        "oAria": {
            "sSortAscending": ": 以升序排列此列",
            "sSortDescending": ": 以降序排列此列"
        }
        	},
        "ajax": "member_ajax.php?MT_id=<?php echo $_GET['MT_id'];?>",
        "columns": [
            { "data": "number" },
            { "data": "name" },
            { "data": "phone" },
            { "data": "mem_email" },
            { "data": "Tb_index" }
        ]
       // "processing": true,
       // "serverSide": true,
       // "deferLoading": 509
        });

	});
</script>
<?php include "../../core/page/footer02.php"; //載入頁面footer02.php?>
