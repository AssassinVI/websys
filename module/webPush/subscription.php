<?php 
require '../../core/inc/config.php';
require '../../core/inc/function.php';
if ($_POST['page']=='webPush_subscription') {
	$subscription=json_decode($_POST['subscription'],true);
	  
	  $param=array(
	  	    'Tb_index'=>'wps'.date('YmdHis').rand(0,99),
	  	    'endpoint'=>$subscription['endpoint'], 
	  	    'userPublicKey'=>$subscription['keys']['p256dh'], 
	  	    'userAuthToken'=>$subscription['keys']['auth']
	  	    );
      pdo_insert('appWebPush_subscription', $param);
}
?>