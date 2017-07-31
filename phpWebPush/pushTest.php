<?php
 require __DIR__.'/Autoload.php';

use Minishlink\WebPush\WebPush; 
use Minishlink\WebPush\VAPID;
// array of notifications
$notifications = array(
    array(
        'endpoint' => 'https://fcm.googleapis.com/fcm/send/dpNzu3DjiXU:APA91bHaAEu_G26pJBLEHhF1XtNijiYL4_xlQjH-QUtAtQBlhAOXUUPW5yVoIQ8rlZMoXyK8wlQggwto0kAwT6VsLYJZdCFGA6WKFQcu8of8imcIoh65asKWOGEcuxFAGEU-WKJW9KNo', // Firefox 43+
        'payload' => null,
        'userPublicKey' => 'BDVgh_MecKzw2YHqsGAEh1G_7nmA0n0c1YNhmaHnkVtxSI_VFsPVYIsgQGrkzNphFeK6KAYE4Gb2-eJo6mXXXMo=', // base 64 encoded, should be 88 chars
        'userAuthToken' => 'r_PRpi-fYQCScYf3DzdH6Q==', // base 64 encoded, should be 24 chars
    ),
    array(
        'endpoint' => 'https://fcm.googleapis.com/fcm/send/co2b2vTNaTg:APA91bF0CHqIYkNrlv27qyyri3su2h8mt85SxSTtO88t1l-wsyumXsiFrDtTvfAjPsHaHI2GGJwBUd5OM0TyZwKzBFckXVaNQbgWhO0HBlYELynfJLMA78xzFB9Oe2exTl_Bs-8JSI2z', // Firefox 43+
        'payload' => null,
        'userPublicKey' => 'BD0es6ymB-asVe6hu8AHr5WBWxIOidrZEXNYVhk4C_XN0ylcX8LRXdwuaDn9oVo2vNmXGMe-qMf0PjHeWIpVEvA=', // base 64 encoded, should be 88 chars
        'userAuthToken' => 'LHxB535PaXs8SAOMtXr3Ow==', // base 64 encoded, should be 24 chars
    ),
);



if (empty($_COOKIE['publicKey'])) {
   $VAPID=new VAPID();
$key= $VAPID->createVapidKeys();
setcookie('privateKey', $key['privateKey'], time()+360000);
setcookie('publicKey', $key['publicKey'], time()+360000);
}
echo 'publicKey : '.$_COOKIE['publicKey'].'<br> privateKey : '.$_COOKIE['privateKey'];


$auth = array(
    'GCM' => 'MY_GCM_API_KEY', // deprecated and optional, it's here only for compatibility reasons
    'VAPID' => array(
        'subject' => 'mailto: d974252037@gmail.com', // can be a mailto: or your website address
        'publicKey' => $_COOKIE['publicKey'], // (recommended) uncompressed public key P-256 encoded in Base64-URL
        'privateKey' => $_COOKIE['privateKey'], // (recommended) in fact the secret multiplier of the private key encoded in Base64-URL
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


// ----------------發送單個推播---------------------
/*$sendpush=$webPush->sendNotification(
    $notifications[0]['endpoint'],
    $notifications[0]['payload'], // optional (defaults null)
    $notifications[0]['userPublicKey'], // optional (defaults null)
    $notifications[0]['userAuthToken'], // optional (defaults null)
    true // optional (defaults false)
);*/

echo $sendpush;
for ($i=0; $i <count($sendpush) ; $i++) { 
   echo count($sendpush)."<br>";

}
?>

<!DOCTYPE html>
<html lang="zh-tw">
<head>
	<meta charset="UTF-8">
	<title>推播測試</title>
</head>
<body>
	
</body>
</html>