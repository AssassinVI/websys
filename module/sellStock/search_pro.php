<?php require '../../core/inc/config.php';
      require '../../core/inc/function.php';
      require '../../core/inc/security.php';

   

  
?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title></title>
    <!-- DataTables -->
     <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">
</head>
<body style="height: 500px;">
	<table id="table_id_example" class="display">
						<thead>
							<tr>
								<th>ID</th>
								<th>品名</th>
								<th>庫存</th>

							</tr>
						</thead>
    </table>

<!-- Mainly scripts -->
<script src="../../js/jquery-2.1.1.js"></script>
<script src="../../js/bootstrap.min.js"></script>
<script src="../../js/plugins/metisMenu/jquery.metisMenu.js"></script>
<script src="../../js/plugins/slimscroll/jquery.slimscroll.min.js"></script>

<!-- dataTables -->
<script type="text/javascript" charset="utf8" src="../../js/jquery.dataTables.js"></script>
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
        "ajax": "member_ajax.php",
        "columns": [
            { "data": "id" },
            { "data": "name" },
            { "data": "stock" }
        ]
       // "processing": true,
       // "serverSide": true,
       // "deferLoading": 509
        });

	});
</script>
</body>
</html>