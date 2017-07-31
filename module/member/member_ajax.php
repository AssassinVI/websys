<?php
require_once '../../core/inc/config.php';
require_once '../../core/inc/function.php';
require_once '../../core/inc/security.php';

$i = 1;
$data_array = array();
$pdo = pdo_conn();
$sql = $pdo->prepare("SELECT Tb_index, name, phone, mem_email FROM appMember ORDER BY StartDate DESC ");
$sql->execute();
while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

	$tool_btn = '<a href="manager.php?MT_id=' . $_GET['MT_id'] . '&Tb_index=' . $row['Tb_index'] . '" >
								<button type="button" class="btn btn-rounded btn-info btn-sm">
								<i class="fa fa-pencil-square" aria-hidden="true"></i>
								編輯</button>
								</a>

								<a href="admin.php?MT_id=' . $_GET['MT_id'] . '&Tb_index=' . $row['Tb_index'] . '"
								   onclick="if (!confirm(\'確定要刪除 [' . $row['name'] . '] ?\')) {return false;}">
								<button type="button" class="btn btn-rounded btn-warning btn-sm">
								<i class="fa fa-trash" aria-hidden="true"></i>
								刪除</button>
								</a>';
	array_push($data_array, array('number' => $i, 'Tb_index' => $tool_btn, 'name' => $row['name'], 'phone' => $row['phone'], 'mem_email' => $row['mem_email']));
	$i++;
}
echo json_encode(array('data' => $data_array));
?>