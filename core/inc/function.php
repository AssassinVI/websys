<?php  require_once 'config.php';

  
 /* ---------------- PDO新增 ----------------- */
 function pdo_insert($tb_name, $array_data )
 {
   $key=array_keys($array_data); //陣列鍵名
   $data_name='';
   $data='';

   for ($i=0; $i < count($array_data) ; $i++) { 
   	if ($i==count($array_data)-1) {
   	  $data_name.=$key[$i];
   	  $data.=':'.$key[$i];
   	}else{
      $data_name.=$key[$i].',';
   	  $data.=':'.$key[$i].',';
   	}
   }

   $sql_query="INSERT INTO ".$tb_name." (".$data_name.") VALUES (".$data.")";

 	$pdo=pdo_conn();
 	$sql=$pdo->prepare($sql_query);
   for ($i=0; $i < count($array_data) ; $i++) { 
   		$sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
   	}	
 	$sql->execute();
 	$pdo=NULL;
 }



 /* ---------------- PDO修改 ----------------- */
 function pdo_update($tb_name, $array_data, $where)
 {
   $key=array_keys($array_data);//陣列鍵名
   $where_key=array_keys($where);
   $data='';

   for ($i=0; $i < count($array_data) ; $i++) { 
   	if ($i==count($array_data)-1) {
   	  $data.=$key[$i].'=:'.$key[$i];
   	}else{
   	  $data.=$key[$i].'=:'.$key[$i].',';
   	}
   }

   $sql_query="UPDATE ".$tb_name." SET ".$data." WHERE ".$where_key[0]."=:".$where_key[0];

    $pdo=pdo_conn();
 	$sql=$pdo->prepare($sql_query);
   for ($i=0; $i < count($array_data) ; $i++) { 
   		$sql->bindparam(':'.$key[$i], $array_data[$key[$i]]);
   	}	
   	$sql->bindparam(':'.$where_key[0], $where[$where_key[0]]);
 	$sql->execute();
 	$pdo=NULL;
 }


 /* ---------------- PDO刪除 ----------------- */
 function pdo_delete($tb_name, $where)
 {
 	$where_key=array_keys($where);//陣列鍵名
    
    $sql_query="DELETE FROM ".$tb_name." WHERE ".$where_key[0]."=:".$where_key[0];

    $pdo=pdo_conn();
 	$sql=$pdo->prepare($sql_query);	
   	$sql->bindparam(':'.$where_key[0], $where[$where_key[0]]);
 	$sql->execute();
 	$pdo=NULL;
 }


 /* ----------------------- PDO 查詢 --------------------------- */
 function pdo_select($sql_query, $where)
 {
   $pdo=pdo_conn();
   $sql=$pdo->prepare($sql_query);

   if ($where!='no') {
      $where_key=array_keys($where);//陣列鍵名
      for ($i=0; $i <count($where) ; $i++) { 
         $sql->bindparam($where_key[$i], $where[$where_key[$i]]);
      }
   }
   $sql->execute();
   if ($sql->rowcount()>1) {

      $array=array();
      while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {
         array_push($array, $row);
      }
      return $array;//失敗

   }else{
      $row=$sql->fetch(PDO::FETCH_ASSOC);
      return $row;
   }
   
   $pdo=NULL;
 }



 /* ----------------------- 圖片檔案上傳 --------------------------- */
 function fire_upload($file_id, $file_name)
 {
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../img/'.$file_name);
 }

  /* ----------------------- 影片檔案上傳 --------------------------- */
 function video_upload($file_id, $file_name)
 {
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../video/'.$file_name);
 }

 /* ----------------------- 其他檔案上傳 --------------------------- */
  function other_fire_upload($file_id, $file_name)
 {
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../other_file/'.$file_name);
 }

  /* ----------------------- 其他檔案上傳(多檔) --------------------------- */
  function more_other_upload($file_id,$i, $file_name)
 {
    move_uploaded_file($_FILES[$file_id]['tmp_name'][$i], '../../other_file/'.$file_name);
 }

  /* ----------------------- 多檔案上傳 --------------------------- */
  function more_fire_upload($file_id, $i, $file_name)
 {
    move_uploaded_file($_FILES[$file_id]['tmp_name'][$i], '../../img/'.$file_name);
 }


/* --------------------------- 判斷檔案是否存在 ---------------------------- */
function is_post_file($tb_name, $Tb_index, $file_id, $session_name)
{
   $where=array('Tb_index'=>$Tb_index);
   $row=pdo_select("SELECT ".$file_id." FROM ".$tb_name." WHERE Tb_index=:Tb_index", $where);
   if (isset($_SESSION[$session_name])) {

      return $_SESSION[$session_name];
   }
   elseif (isset($row[$file_id])){
      return $row[$file_id];
   }
   else{
      return '';
   }
}


/* ------------------------------- 網頁跳轉 ------------------------------------ */

function location_up($location_path,$alert_txt)
{
   echo "<script>";

   if ($location_path=='back') {
     echo "history.back();"; //返回上一頁
   }else{
     echo "location.replace('".$location_path."');"; //網頁跳轉
   }
   
   if (!empty($alert_txt)) {
    echo "alert('" . $alert_txt . "');";
   }
   echo "</script>";
}



//--------------------------------- 資料AES加密 --------------------------------
function aes_encrypt($key, $data)
{
$hash = hash('SHA384', $key, true);
$app_cc_aes_key = substr($hash, 0, 32);
$app_cc_aes_iv = substr($hash, 32, 16);

$padding = 16 - (strlen($data) % 16); 
$data .= str_repeat(chr($padding), $padding); 
$encrypt = mcrypt_encrypt(MCRYPT_RIJNDAEL_128, $app_cc_aes_key, $data, MCRYPT_MODE_CBC, $app_cc_aes_iv); 
$encrypt_text = base64_encode($encrypt);
return $encrypt_text;
}


//-------------------------------- 資料AES解密 --------------------------------------
function aes_decrypt($key, $unlock_data)
{ 
  $hash = hash('SHA384', $key, true);
  $app_cc_aes_key = substr($hash, 0, 32);
  $app_cc_aes_iv = substr($hash, 32, 16);

  $encrypt=base64_decode($unlock_data);
  $data = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $app_cc_aes_key, $encrypt, MCRYPT_MODE_CBC, $app_cc_aes_iv);
  $padding = ord($data[strlen($data) - 1]);
  $decrypt_text = substr($data, 0, -$padding);
  return $decrypt_text;
}


//-------------------------------- 登入密鑰 -----------------------------------------
function login_key($login_key)
{ 
  global $aes_key;
  //** 加入登入密鑰 **
        $_SESSION['sys_login_key']=aes_encrypt( $aes_key, $login_key);
}

//-------------------------------- 登入解密 -----------------------------------------
function unlock_key($login_key)
{ 
  global $aes_key;
  //** 加入解密 **
        $unlock_key=aes_decrypt( $aes_key, $login_key);
        return $unlock_key;
}

//-------------------------------- 驗證 input 排除特殊符號 ---------------------------------------------
function test_input($GET)
{
  if(preg_match("/^([^\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\=|\+|\{|\}|\[|\]|\"|\'|\?|\<|\>]+)$/", $GET)){ //== 排除特殊符號 == 
    return $GET;
  }
  else{
    location_up('back','請勿輸入特殊字元!!');
    exit();
  }
}

?>