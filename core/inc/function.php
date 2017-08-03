<?php  

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


  /* ----------------------- PDO 查詢 (多個)--------------------------- */
 function pdo_select_new($sql_query, $where)
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
   return $sql->fetchAll();
   
   $pdo=NULL;
 }


 /* ----------------------- 圖片檔案上傳 --------------------------- */
 function fire_upload($file_id, $file_name)
 {
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../img/'.$file_name);
 }

 /* ----------------------- 其他檔案上傳 --------------------------- */
  function other_fire_upload($file_id, $file_name)
 {
    move_uploaded_file($_FILES[$file_id]['tmp_name'], '../../other_file/'.$file_name);
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

/*------------------------------ 發送Mail --------------------------------*/
function phpMail($set_name, $set_mail, $Subject, $body_data, $name_data, $adds_data)
{
   
   $mail_body="<table>
    <tr>
      <td>姓名:</td>
      <td>".$body_data['name']."</td>
    </tr>
    <tr>
      <td>電子郵件:</td>
      <td>".$body_data['email']."</td>
    </tr>
    <tr>
      <td>電話:</td>
      <td>".$body_data['phone']."</td>
    </tr>
    <tr>
      <td>您的訊息:</td>
      <td>".$body_data['msg']."</td>
    </tr>
  </table>";
send_Mail($set_name, $set_mail, $Subject, $mail_body, $name_data, $adds_data);
}


/*------------------------------ 確認信 --------------------------------*/
function check_Mail($set_name, $set_mail, $Subject, $body_data, $name_data, $adds_data)
{
   
   $mail_body="<h2>福敏企業-會員確認信</h2>
               <a href='http://fmtoto.com.tw/newsite/check_mail.php?Tb_index=".$body_data['Tb_index']."'>點我開通會員</a><br>
               <p>如無法點擊連結，請複製網址: http://fmtoto.com.tw/newsite/check_mail.php?Tb_index=".$body_data['Tb_index']."</p>";
   send_Mail($set_name, $set_mail, $Subject, $mail_body, $name_data, $adds_data);
}




//----------------------------- 每日流量 ---------------------------
function OneDayChart()
{
  if (empty($_SESSION['on_web'])) {
  $where=array('ChartDate'=>date('Y-m-d'));
  $row=pdo_select("SELECT * FROM OneDayChart WHERE ChartDate=:ChartDate", $where);

  if (empty($row['ChartDate'])) {
    $param=array('ChartDate'=>date('Y-m-d'), 'ChartNum'=>'1');
    pdo_insert('OneDayChart', $param);
  }
  else{
    $pdo=pdo_conn();
    $sql=$pdo->prepare("UPDATE OneDayChart SET ChartNum=ChartNum+1 WHERE ChartDate=:ChartDate");
    $sql->execute(array(':ChartDate'=>$row['ChartDate']));
  }
}
  $_SESSION['on_web']='online';
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


//-------------------------------- 亂數產生器(A~z 0~9) --------------------------------------
function rand_txt($num)
{
  $random=$num;
  $randoma='';
//FOR回圈以$random為判斷執行次數
for ($i=1;$i<=$random;$i=$i+1)
{
//亂數$c設定三種亂數資料格式大寫、小寫、數字，隨機產生
$c=rand(1,3);
//在$c==1的情況下，設定$a亂數取值為97-122之間，並用chr()將數值轉變為對應英文，儲存在$b
if($c==1){$a=rand(97,122);$b=chr($a);}
//在$c==2的情況下，設定$a亂數取值為65-90之間，並用chr()將數值轉變為對應英文，儲存在$b
if($c==2){$a=rand(65,90);$b=chr($a);}
//在$c==3的情況下，設定$b亂數取值為0-9之間的數字
if($c==3){$b=rand(0,9);}
//使用$randoma連接$b
$randoma.=$b;
}
//輸出$randoma每次更新網頁你會發現，亂數重新產生了
return $randoma;
}


//-------------------------------- 登入密鑰 -----------------------------------------
function login_key($login_key)
{
  global $aes_key;
  //** 加入登入密鑰 **
        $_SESSION['login_key']=aes_encrypt( $aes_key, $login_key);
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