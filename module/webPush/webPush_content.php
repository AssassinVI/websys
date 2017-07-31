<?php
 header("Content-Type:text/html; charset=utf-8");
  require '../../core/inc/config.php';
  require 'webPush_id.php';
  $pdo=pdo_conn();
  $sql=$pdo->prepare("SELECT * FROM appWebPush WHERE Tb_index=:Tb_index");
  $sql->execute(array(":Tb_index"=>$Tb_index));
  $row=$sql->fetch(PDO::FETCH_ASSOC);
  echo json_encode(array('title'=>$row['title'], 'content'=>$row['content'], 'pic'=>$row['pic'], 'url'=>$row['url']));
?>