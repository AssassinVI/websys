<?php 
require_once '../../core/inc/config.php';
require_once '../../core/inc/function.php';
require_once '../../core/inc/security.php';


if ($_GET) {
	
   if ($_GET['nowState']=='all') {
   	$pdo=pdo_conn();
	$sql=$pdo->prepare("SELECT sl.*, mem.name, mem.phone, mem.tel, mem.adds, mem.mem_email FROM shop_List as sl INNER JOIN appMember as mem ON sl.member_id=mem.Tb_index ORDER BY sl.StartDate DESC");
    $sql->execute();
   }
   else{
   	$pdo=pdo_conn();
	$sql=$pdo->prepare("SELECT sl.*, mem.name, mem.phone, mem.tel, mem.adds, mem.mem_email FROM shop_List as sl INNER JOIN appMember as mem ON sl.member_id=mem.Tb_index WHERE nowState=:nowState ORDER BY sl.StartDate DESC");
    $sql->execute(array(':nowState'=>$_GET['nowState']));
   }

   if ($sql->rowcount()<1) {
   	 location_up('back','無任何資料');
   }
   else{
   	header("Content-type:application/vnd.ms-excel");
    header("Content-Disposition: attachment; filename=".date("Y-m-d")."顧客訂單.xls");
   }
	
}


?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title></title>
	
</head>
<body>
  <table border="1" >
  	<thead>
  		<th>訂單編號</th>
  		<th>訂購日期</th>
  		<th>會員姓名</th>
  		<th>訂單項目</th>
  		<th>訂單總額</th>
  		<th>收件者姓名</th>
  		<th>行動電話</th>
  		<th>市內電話</th>
  		<th>聯絡地址</th>
  		<th>電子信箱</th>
  		<th>發票</th>
  		<th>訂單狀態</th>
  	</thead>
  	<tbody>
  		<?php 
          while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
          	/*$array_name=explode(',', $row['pro_name_array']);
          	$array_num=explode(',', $row['pro_num_array']);
          	$pro_list='<ul>';
          	for ($i=0; $i <count($array_num)-1 ; $i++) { 
          		$pro_list.='<li>'.$array_name[$i].' X '.$array_num[$i].'</li>';
          	}
          	$pro_list.='</ul>';*/
            $sql_detial=$pdo->prepare("SELECT SD.*, Pro.aTitle FROM shopDetial as SD INNER JOIN appProduct as Pro ON SD.pro_id=Pro.Tb_index WHERE SD.SL_id=:SL_id");
            $sql_detial->execute(array(':SL_id'=>$row['Tb_index']));

            $pro_list='<ul>';
            while ($row_detial=$sql_detial->fetch(PDO::FETCH_ASSOC)) {
              $pro_list.='<li>'.$row_detial['aTitle'].' X '.$row_detial['pro_num'].'</li>';
            }
            $pro_list.='</ul>';

          	 echo '<tr>
          	        <td>'.$row['Tb_index'].'</td>
          	        <td>'.$row['StartDate'].'</td>
          	        <td>'.$row['name'].'</td>
          	        <td>'.$pro_list.'</td>
          	        <td>$'.$row['total'].'</td>
          	        <td>'.$row['name'].'</td>
          	        <td>'.$row['phone'].'</td>
          	        <td>'.$row['tel'].'</td>
          	        <td>'.$row['adds'].'</td>
          	        <td>'.$row['mem_email'].'</td>
          	        <td>二聯式發票</td>
          	        <td>'.$row['nowState'].'</td>
          	      </tr>';
          }
          $pdo=NULL;
  		?>
  	</tbody>
  </table>
</body>
</html>