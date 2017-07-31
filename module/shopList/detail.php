<?php 
require_once '../../core/inc/config.php';
require_once '../../core/inc/function.php';
require_once '../../core/inc/security.php';

if ($_GET) {
	$pdo=pdo_conn();
	$sql=$pdo->prepare("SELECT sl.*, mem.name, mem.phone, mem.tel, mem.adds, mem.mem_email 
		             FROM shop_List as sl INNER JOIN appMember as mem ON sl.member_id=mem.Tb_index WHERE sl.Tb_index=:Tb_index");
    $sql->execute(array(':Tb_index'=>$_GET['Tb_index']));
    $row=$sql->fetch(PDO::FETCH_ASSOC);
	//$pdo=NULL;
}

?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title></title>
	<style type="text/css">
		.mem_td_lab{ text-align: center; background-color: #eafffb;  }
	</style>
</head>
<body>
  <p>訂單編號: <?php echo $row['Tb_index']?></p>
    <!-- 訂單資訊 -->
	<table width="100%" border="1" cellpadding="15" cellspacing="0" style="border-color: #fff; margin-bottom: 20px;">
		<thead style="background: #eee">
			<th>訂購商品</th>
			<th>售價</th>
      <th>顧客出價</th>
			<th>數量</th>
			<th>小計</th>
		</thead>
		<tbody>
			<?php 
			  /* $array_id=explode(',', $row['pro_id_array']);
               $array_name=explode(',', $row['pro_name_array']);
               $array_num=explode(',', $row['pro_num_array']);
               for ($i=0; $i <count($array_name)-1 ; $i++) { 

               	 $where=array('Tb_index'=>$array_id[$i]);
               	 $product=pdo_select("SELECT price, mem_price FROM appProduct WHERE Tb_index=:Tb_index", $where);
               	 if (!empty($product['mem_price'])) {
               	 	echo "<tr>
               	         <td>".$array_name[$i]."</td>
               	         <td align='center'>$".$product['mem_price']."</td>
               	         <td align='center'>".$array_num[$i]."</td>
               	         <td align='center'>$".((int)$product['mem_price']*(int)$array_num[$i])."</td>
               	       </tr>";
               	 }
               	 else{
               	 	echo "<tr>
               	         <td>".$array_name[$i]."</td>
               	         <td align='center'>$".$product['price']."</td>
               	         <td align='center'>".$array_num[$i]."</td>
               	         <td align='center'>$".((int)$product['price']*(int)$array_num[$i])."</td>
               	       </tr>";
               	 }
               }*/

               
              $sql_detial=$pdo->prepare("SELECT SD.*, Pro.aTitle, Pro.price, Pro.mem_price FROM shopDetial as SD INNER JOIN appProduct as Pro ON SD.pro_id=Pro.Tb_index WHERE SD.SL_id=:SL_id");
              $sql_detial->execute(array(':SL_id'=>$row['Tb_index']));
              while ($row_detial=$sql_detial->fetch(PDO::FETCH_ASSOC)) {

                 $price=empty($row_detial['mem_price']) ? $row_detial['price']:$row_detial['mem_price'];

                 if($row_detial['pro_newPrice']>0){
                   echo "<tr>
                         <td>".$row_detial['aTitle']."</td>";
                   echo "<td align='center'>$".$price."</td>";
                   echo "<td align='center'>$".$row_detial['pro_newPrice']."</td>
                         <td align='center'>".$row_detial['pro_num']."</td>
                         <td align='center'>$".((int)$row_detial['pro_newPrice']*(int)$row_detial['pro_num'])."</td>
                       </tr>";
                 }else {
              	 	echo "<tr>
               	         <td>".$row_detial['aTitle']."</td>
                         <td align='center'>$".$price."</td>
               	         <td align='center'>無</td>
               	         <td align='center'>".$row_detial['pro_num']."</td>
               	         <td align='center'>$".((int)$row_detial['mem_price']*(int)$row_detial['pro_num'])."</td>
               	       </tr>";
              	 }
              }

			?>
			<tr>
				<td colspan="4" align="right">金額總計:</td>
				<td align="center" style="color: red">$<?php echo $row['total']?></td>
			</tr>
		</tbody>
	</table>
	<!-- 客戶資訊 -->
	<table width="100%" border="1" cellpadding="15" cellspacing="0" style="border-color: #fff; ">
		<tbody>
			<tr style="background: #eee"><td colspan="6">收件者資料</td></tr>
			<tr><td class="mem_td_lab">姓名</td><td><?php echo $row['name']?></td><td class="mem_td_lab">行動電話</td><td><?php echo $row['phone']?></td><td class="mem_td_lab">市內電話</td><td> <?php echo $row['tel']?></td></tr>
			<tr><td class="mem_td_lab">聯絡地址</td><td colspan="5"><?php echo $row['adds']?></td></tr>
			<tr><td class="mem_td_lab">電子信箱</td><td colspan="5"><?php echo $row['mem_email']?></td></tr>
			<tr><td class="mem_td_lab">發票</td><td colspan="5">二聯式發票</td></tr>
			<tr><td class="mem_td_lab">訂單狀態</td><td colspan="5"><?php echo $row['nowState']?></td></tr>
		</tbody>
	</table>
</body>
</html>