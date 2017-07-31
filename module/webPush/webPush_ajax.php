<?php 
require '../../core/inc/config.php';
require '../../core/inc/function.php';
require '../../phpWebPush/Autoload.php';
use Minishlink\WebPush\WebPush;
if ($_POST) {
	 

	 $php_file = fopen('webPush_id.php', "w");
     $php_file_txt= '<?php $Tb_index="'.$_POST['Tb_index'].'";?>';
     fwrite($php_file, $php_file_txt);
     fclose($php_file);

     //================================================ 推播發送 ===========================================
     $notifications=array();

$pdo=pdo_conn();
$sql=$pdo->prepare('SELECT endpoint, userPublicKey, userAuthToken FROM appWebPush_subscription');
$sql->execute();
while ($subscription=$sql->fetch(PDO::FETCH_ASSOC)) {
	array_push($notifications, array(
		'endpoint'=>$subscription['endpoint'], 
		'payload'=>null,
		'userPublicKey'=>$subscription['userPublicKey'], 
		'userAuthToken'=>$subscription['userAuthToken'],
	)); 
}
$pdo=NULL;


$sql_auth=pdo_select("SELECT * FROM appWebPush_key", 'no');
$auth = array(
    'GCM' => 'MY_GCM_API_KEY', // deprecated and optional, it's here only for compatibility reasons
    'VAPID' => array(
        'subject' => 'mailto: d974252037@gmail.com', // can be a mailto: or your website address
        'publicKey' => $sql_auth['publicKey'], // (recommended) uncompressed public key P-256 encoded in Base64-URL
        'privateKey' => $sql_auth['privateKey'], // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
       // 'pemFile' => 'path/to/pem', // if you have a PEM file and can link to it on your filesystem
       // 'pem' => 'pemFileContent', // if you have a PEM file and want to hardcode its content
    ),
);

$webPush = new WebPush($auth);

// ----------------發送多個推播---------------------
foreach ($notifications as $notification) {
    $webPush->sendNotification(
        $notification['endpoint'],
        $notification['payload'], // optional (defaults null)
        $notification['userPublicKey'], // optional (defaults null)
        $notification['userAuthToken'] // optional (defaults null)
    );
}
$webPush->flush();
}
?>