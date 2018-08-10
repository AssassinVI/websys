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

      $row=$sql->fetchAll();
      return $row;

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


//----------------GOOGLE recaptcha 驗證程式 --------------------
function GOOGLE_recaptcha($secretKey, $recaptcha_response, $location)
{
    if (!empty($recaptcha_response)) {

    $ReCaptchaResponse = filter_input(INPUT_POST, 'g-recaptcha-response');

    // 建立CURL連線
    $ch = curl_init();
    // 設定擷取的URL網址
    curl_setopt($ch, CURLOPT_URL, 'https://www.google.com/recaptcha/api/siteverify?secret='.$secretKey.'&response=' . trim($ReCaptchaResponse));
    curl_setopt($ch, CURLOPT_HEADER, false);
    //將curl_exec()獲取的訊息以文件流的形式返回，而不是直接輸出。
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // 執行
    $Response = curl_exec($ch);
    // 關閉CURL連線
    curl_close($ch);

    //$Response=file_get_contents();
    $re_code = json_decode($Response, true);

    if ($re_code['success'] != true) {

      location_up($location, '請確定您不是機器人');
      exit();
    }
  } else {

    location_up($location, '請確定您不是機器人');
    exit();
  }
}

  //----------------GOOGLE recaptcha 驗證程式 --------------------*



//--------------------------- 縮圖程式 -------------------------
function ecstart_convert_jpeg($src_file,$dst_file,$text_type,$text_image,$watermark_type,$watermark_image,$pic_width,$pic_height){

//$src_file 來源圖片路徑
//$dst_file 存檔圖片路徑
//$text_type 是否插入文字
//$text_image 插入的文字內容
//$watermark_type 是否插入浮水印
//$watermark_image 圖水印圖片路徑
//$pic_width 存檔圖片寬度
//$pic_height 存檔圖片高度
$image = imagecreatefromjpeg($src_file) ;
$s_width = imagesx($image);
$s_height = imagesy($image);


// 縮圖大小
$thumb = imagecreatetruecolor($pic_width, $pic_height);
// 自動縮圖
imagecopyresized($thumb, $image, 0, 0, 0, 0, $pic_width, $pic_height, $s_width, $s_height);
//imagejpeg($thumb,"/tmp/tmpfile.jpg","100");
//$thumbimage = imagecreatefromjpeg("/tmp/tmpfile.jpg") ;


// 取得寬度
$i_width = imagesx($thumb);
$i_height = imagesy($thumb);

//imagejpeg($image,"/home/www/ecstart.com/public_html/cart/test.jpg","100");
//imagejpeg($image);

// 計算 插入文字出現位置
$ywpos = $i_height - 35 ;
// 設定 插入文字
$textcolor = imagecolorallocate($thumb, 250, 250, 250);

// 插入文字
if($text_type == "Y"){
imagestring($thumb, 5, 25, $ywpos, $text_image, $textcolor);
}
// 載入浮水印圖
$w_image = imagecreatefromjpeg($watermark_image) ;
// 取出浮水印圖 寬 與 高
$w_width = imagesx($w_image);
$w_height = imagesy($w_image);
// 計算 浮水印出現位置
$xpos = $i_width - $w_width -20 ;
$ypos = $i_height - $w_height-20 ;

//結合浮水印
if($watermark_type == "Y"){
imagecopy($thumb,$w_image,$xpos,$ypos,0,0,$w_width,$w_height);
}

imagejpeg($thumb,$dst_file,"100");


imagedestroy($thumb);
imagedestroy($image);
imagedestroy($w_image);
}



//--- 亂碼產生器 ----
function randTXT($num)
{
 
  //$random預設為10，更改此數值可以改變亂數的位數----(程式範例-PHP教學)
$random=empty($num) ? 10:$num ;
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
$randoma=$randoma.$b;
}
//輸出$randoma每次更新網頁你會發現，亂數重新產生了
return $randoma;
}


//-------------------------------- 驗證 input 排除特殊符號 ---------------------------------------------
function test_input($GET)
{
  if(preg_match("/^(?:[^\~|\!|\@|\#|\$|\%|\^|\&|\*|\(|\)|\_|\=|\+|\{|\}|\[|\]|\"|\'|\?|\<|\>]+)$/", $GET)){ //== 排除特殊符號 == 
    return $GET;
  }
  else{
    location_up('back','請勿輸入特殊字元!!');
    exit();
  }
}


//-------------------------------- 驗證 E-mail  ---------------------------------------------
function test_mail($mail)
{
  if (preg_match("/^\w+(?:(?:-\w+)|(?:\.\w+))*\@\w+(?:(?:\.|-)\w+)*\.[A-Za-z]+$/", $mail)) {
    return true;
  }else{
    return false;
  }
}


//----------------------------------- 驗證圖片 ---------------------------------
function test_img($img)
{
  if (preg_match('/^.+\.(jpg|png|gif)$/i', $img)){
    return true;
  }else{
    return false;
  }
}


//----------------------------------- 驗證其他檔案 ---------------------------------
function test_file($file)
{
  if (preg_match('/^.+\.(jpg|png|gif|doc|docx|xls|xlsx|ppt|pptx|pdf)$/i', $file)){
    return true;
  }else{
    return false;
  }
}


//---------------------------------------- 判斷手機 --------------------------------------------
function check_mobile(){
    $regex_match="/(nokia|iphone|android|motorola|^mot\-|softbank|foma|docomo|kddi|up\.browser|up\.link|";
    $regex_match.="htc|dopod|blazer|netfront|helio|hosin|huawei|novarra|CoolPad|webos|techfaith|palmsource|";
    $regex_match.="blackberry|alcatel|amoi|ktouch|nexian|samsung|^sam\-|s[cg]h|^lge|ericsson|philips|sagem|wellcom|bunjalloo|maui|";
    $regex_match.="symbian|smartphone|midp|wap|phone|windows ce|iemobile|^spice|^bird|^zte\-|longcos|pantech|gionee|^sie\-|portalmmm|";   
    $regex_match.="jig\s browser|hiptop|^ucweb|^benq|haier|^lct|opera\s*mobi|opera\*mini|320x320|240x320|176x220";
    $regex_match.=")/i";
    return preg_match($regex_match, strtolower($_SERVER['HTTP_USER_AGENT']));
}


//---------------------------------------- 判斷手機AND平板 --------------------------------------------
function wp_is_mobile() {
  static $is_mobile = null;
 
  if ( isset( $is_mobile ) ) {
    return $is_mobile;
  }
 
  if ( empty($_SERVER['HTTP_USER_AGENT']) ) {
    $is_mobile = false;
  } elseif ( strpos($_SERVER['HTTP_USER_AGENT'], 'Mobile') !== false // many mobile devices (all iPhone, iPad, etc.)
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Silk/') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Kindle') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'BlackBerry') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mini') !== false
    || strpos($_SERVER['HTTP_USER_AGENT'], 'Opera Mobi') !== false ) {
      $is_mobile = true;
  } else {
    $is_mobile = false;
  }
 
  return $is_mobile;
}



// ---------------------------------------------- MENU套後台樣板 ------------------------------------------
function pdo_navbar($Tb_index)
{  

  $pdo=pdo_conn();
  
  // ----------------------- 第一層 ------------------------------
  if(empty($Tb_index)){
      $sql=$pdo->prepare("SELECT * FROM maintable WHERE parent_id='' AND isTopbar='1' AND OnLineOrNot='1' ORDER BY OrderBy DESC, Tb_index ASC");
      $sql->execute();
      while ($row_nav=$sql->fetch(PDO::FETCH_ASSOC)) {

          // -- 資料夾 --
         if ($row_nav['is_data']=='0') {
            // -- 依實際HTML修改 --
            echo '
            <li class="nav-item dropdown">
              <a class="dropdown-toggle" href="javascript:void(0);">'.$row_nav['MT_Name'].' <span class="caret"></span></a>
               <ul class="dropdown-menu">';
            
             pdo_navbar($row_nav['Tb_index']);

            echo '
              </ul>
             </li>';
         }
         // -- 資料 --
         else{
            // -- 依實際HTML修改 --
            echo '
            <li class="nav-item dropdown">
              <a class="dropdown-toggle" href="'.$row_nav['use_web'].'">'.$row_nav['MT_Name'].' </a>
            </li>';
         }
      } 
   }

   // ------------------------- 其他層 -------------------------------
   else{
      $sql=$pdo->prepare("SELECT * FROM maintable WHERE parent_id=:parent_id AND isTopbar='1' AND OnLineOrNot='1' ORDER BY OrderBy DESC, Tb_index ASC");
      $sql->execute(['parent_id'=>$Tb_index]);
      while ($row_nav=$sql->fetch(PDO::FETCH_ASSOC)){

          // -- 資料夾 --
         if ($row_nav['is_data']=='0'){
          // -- 依實際HTML修改 --
          echo '
            <li class="sub-menu"><a href="javascript:void(0);">'.$row_nav['MT_Name'].' <span class="caret"></span></a>
               <ul class="dropdown-menu">';

               pdo_navbar($row_nav['Tb_index']);
           
          echo'
               </ul>
            </li>';

         }
         // -- 資料 --
         else{
           // -- 依實際HTML修改 --
           echo '<li><a href="'.$row_nav['use_web'].'">'.$row_nav['MT_Name'].'</a></li>';
         }
      }
   }
   
   $pdo=NULL;
}
 
?>