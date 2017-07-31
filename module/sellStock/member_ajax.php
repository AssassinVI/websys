<?php
require_once '../../core/inc/config.php';
require_once '../../core/inc/function.php';
require_once '../../core/inc/security.php';


if ($_POST['type']=='pro_id') {
	  $pdo = pdo_conn();
	  $sql=$pdo->prepare("SELECT aTitle FROM appProduct WHERE Tb_index=:Tb_index");
	  $sql->execute(array(":Tb_index"=>$_POST['pro_id']));
	  $row=$sql->fetch(PDO::FETCH_ASSOC);
	  if (!empty($row['aTitle'])) {
	  	echo $row['aTitle'];
	  }else{
	  	echo 'error';
	  }
	  exit();
}
else{
  $data_array = array();
  $pdo = pdo_conn();
  $sql = $pdo->prepare("SELECT Tb_index, aTitle, stock_num FROM appProduct ORDER BY StartDate DESC ");
  $sql->execute();
  while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

  	array_push($data_array, array( 'id' => $row['Tb_index'], 'name' => $row['aTitle'], 'stock' => $row['stock_num']));
  }
  echo json_encode(array('data' => $data_array));
  $pdo=NUll;
}

?>