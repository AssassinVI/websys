<?php include "../../core/page/header01.php"; //載入頁面heaer01?>
  <style type="text/css">
      #state_div{ padding: 7px; border: 1px solid #bbb; display: inline-block; background: #e6e6e6; margin-right: 10px;}
      #state_div select{ padding:5px; }
  </style>
<?php include "../../core/page/header02.php"; //載入頁面heaer02?>
<?php

if ($_GET) {

	if (!empty($_GET['Tb_index'])) {
		//刪除
		$where = array('Tb_index' => $_GET['Tb_index']);
		pdo_delete('appMember', $where);
	}

}

if ($_POST) {
    if (count($_POST['check'])==0) {
        echo ("<script>");
            echo("alert('您尚未勾選');");
        echo("</script>");
    }
    else{
        for ($i=0; $i <count($_POST['check']) ; $i++) { 
            $data=array('nowState'=>$_POST['sel_state']);
            $where=array('Tb_index'=>$_POST['check'][$i]);
            pdo_update('shop_List', $data, $where);
        }
    }
}

?>


<div class="wrapper wrapper-content animated fadeInRight">
	<div class="col-lg-12">
		<h2 class="text-primary"><?php echo $page_name['MT_Name'] ?> 列表</h2>
		<p>本頁面條列出所有的文章清單，如需檢看或進行管理，請由每篇文章右側 管理區進行，感恩</p>
	   <div class="new_div">

	  </div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<div class="panel panel-default">
			<div class="panel-body">
				<div class="table-responsive">
                  <form action="admin.php" method="POST">
					<table id="table_id_example" class="display">
						<thead>
							<tr>
								<th>#</th>
								<th>訂單編號</th>
								<th>訂購日期</th>
								<th>會員姓名</th>
								<th >訂單項目</th>
                                <th >訂單總額</th>
                                <th >訂單狀態</th>
                                <th >詳細資料</th>
							</tr>
						</thead>
					</table>
                    <input type="checkbox" id="all_check"> <label for="all_check">全選/取消</label><br>
                <div id="state_div">
                    <label>訂單狀態: </label>
                    <select name="sel_state">
                        <option value="準備中">準備中</option>
                        <option value="已出貨">已出貨</option>
                        <option value="作廢">作廢</option>
                        <option value="結案">結案</option>
                    </select>
                    <button class="btn btn-sm btn-default" type="submit" name="submit_state">更新選取的訂單狀態</button>
                </div>

                <div id="state_div">
                   <label>訂單狀態: </label>
                    <select id="excel_state">
                        <option value="all">全部</option>
                        <option value="準備中">準備中</option>
                        <option value="已出貨">已出貨</option>
                        <option value="作廢">作廢</option>
                        <option value="結案">結案</option>
                    </select>
                   <a id="excel_btn" class="btn btn-sm btn-default" href="#">下載顧客訂單Excel檔</a>
                </div>
                 </form>
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
            { "data": "checkbox" },
            { "data": "Tb_index" },
            { "data": "StartDate" },
            { "data": "member_name" },
            { "data": "product_txt" },
            { "data": "total" },
            { "data": "nowState" },
            { "data": "detail" }
        ]
       // "processing": true,
       // "serverSide": true,
       // "deferLoading": 509
        });
    
    $("#all_check").click(function(event) {

            $("[name='check[]']").each(function() {
                if ($("#all_check").prop('checked')) {
                     $(this).prop('checked', true);
                }
                else{
                    $(this).prop('checked', false);
                }
               
            });
    });

    $("#excel_btn").click(function(event) {
        event.preventDefault();
        var state=$("#excel_state").val();
        location.href='detail_excel.php?nowState='+state;
    });


	});
</script>
<?php include "../../core/page/footer02.php"; //載入頁面footer02.php?>
