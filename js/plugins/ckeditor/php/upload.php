<?php
 session_start();
 require_once '../../../../core/inc/function.php';
 require_once '../../../../core/inc/security.php';
 $img_type=explode('.', $_FILES['upload']['name']); 
 $img_name='other'.date('YmdHis').rand(0,99).'.'.$img_type[1];
 $save_url='../../../../other_file/'.iconv("utf-8", "big5",$img_name );

$type=preg_replace('/^.*\.([^.]+)$/D', '$1', $_FILES['upload']['name']);

 if ($_FILES['upload']['error']>0) {
 	# code...
 }
 else{

 	if ($type=='php' || $type=='js' || $type=='html' || $type=='PHP' || $type=='JS' || $type=='HTML') {
 		echo "檔案上傳錯誤!!";
 	}else{
 	 move_uploaded_file($_FILES['upload']['tmp_name'], $save_url);
 	}
 }

 echo "<script type='text/javascript'>";


 if (empty($_FILES['upload']['error'])) {

 	$CKEditorFuncNum = isset($_GET['CKEditorFuncNum']) ? $_GET['CKEditorFuncNum'] : 2;
    $img_url='http://'.$_SERVER['HTTP_HOST'].'/newsite/sys/other_file/'.$img_name;
    echo "window.parent.CKEDITOR.tools.callFunction(". $CKEditorFuncNum .",'" . $img_url . "','');";
 }
 else{
 	echo 'alert("'.$_FILES['upload']['error'].'");';
 }

  echo "</script>";
?>