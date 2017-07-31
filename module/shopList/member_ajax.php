<?php
require_once '../../core/inc/config.php';
require_once '../../core/inc/function.php';
require_once '../../core/inc/security.php';

$i = 1;
$data_array = array();
$pdo = pdo_conn();
$sql = $pdo->prepare("SELECT sl.*, mem.name FROM shop_List as sl INNER JOIN appMember as mem ON sl.member_id=mem.Tb_index ORDER BY sl.StartDate DESC");
$sql->execute();
while ($row = $sql->fetch(PDO::FETCH_ASSOC)) {

     	  /*$array_name=explode(',', $row['pro_name_array']);
          $array_num=explode(',', $row['pro_num_array']);
          $pro_txt='<ul>';
          for ($i=0; $i <count($array_name)-1 ; $i++) { 
             $pro_txt.='<li>'.$array_name[$i].' X '.$array_num[$i].'</li>';
          }
          $pro_txt.='</ul>';*/

          $sql_detial=$pdo->prepare("SELECT SD.*,Pro.aTitle FROM shopDetial as SD INNER JOIN appProduct as Pro ON SD.pro_id=Pro.Tb_index WHERE SD.SL_id=:SL_id");
          $sql_detial->execute(array(':SL_id'=>$row['Tb_index']));

          $pro_txt='<ul>';
          while ($row_detial=$sql_detial->fetch(PDO::FETCH_ASSOC)) {
              $pro_txt.='<li>'.$row_detial['aTitle'].' X '.$row_detial['pro_num'].'</li>';
          }
           $pro_txt.='</ul>';

          //----- 詳細資料 -----
          $detail_btn='<a class="btn btn-w-m btn-success fancybox" data-fancybox-type="iframe" href="detail.php?Tb_index='.$row['Tb_index'].'">詳細資料</a>';

          //------ 核取方塊 --------
          $checkbox='<input type="checkbox" id="check[]" name="check[]" value="'.$row['Tb_index'].'">';

	array_push($data_array, array('checkbox'=>$checkbox, 'Tb_index'=>$row['Tb_index'], 'StartDate'=>$row['StartDate'], 
		                       'member_name'=>$row['name'], 'product_txt'=>$pro_txt, 'total'=>'$'.$row['total'], 'nowState'=>$row['nowState'], 'detail'=>$detail_btn));
	$i++;
}
$pdo=NULL;
echo json_encode(array('data' => $data_array));
?>