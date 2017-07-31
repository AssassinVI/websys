<?php
include "../../core/inc/config.php"; //載入基本設定
include "../../core/inc/function.php"; //載入基本function
include "../../core/inc/security.php"; //載入安全設定
?>
<?php
$company = pdo_select("SELECT * FROM company_base WHERE webLang='tw'", 'no');
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $company['name'] ?> | ADMIN</title>
     <link rel="shortcut icon" href="/newsite/favicon.ico" />

    <link href="../../css/bootstrap.min.css" rel="stylesheet">
    <link href="../../font-awesome/css/font-awesome.css" rel="stylesheet">

    <link href="../../css/animate.css" rel="stylesheet">
    <link href="../../css/style.css" rel="stylesheet">
     <link href="../../css/msism.css" rel="stylesheet">
     <!-- C3 Chart -->
     <link rel="stylesheet" type="text/css" href="../../css/plugins/c3/c3.min.css">

     <!-- DataTables -->
     <link rel="stylesheet" type="text/css" href="../../css/jquery.dataTables.css">

     <!-- FancyBox -->
     <link rel="stylesheet" type="text/css" href="../../js/plugins/fancyBox/jquery.fancybox.css">

     <style type="text/css">
     	.close_btn{ position: absolute; bottom: 0px; right: 15px; border: 0px; }
        .new_div{ position: absolute; right: 0px; bottom: 0px; }
        .twzipcode{ display: inline-block; }
        .twzipcode input, .twzipcode select ,.adds{ font-size: 14px; padding: 5px; border: 1px solid #d6d6d6; }
        .adds{ width: 300px; }
            #one_img{ width: 150px; border:1px solid #d6d6d6; padding: 3px;}
            #one_del_img,#one_del_file,.one_del_file,#one_del_video{ position: absolute; border: 0px; background-color: #ff243b; color: #fff; box-shadow: 1px 1px 2px rgba(0,0,0,0.5);}
        .img_check{ position: absolute; top: 40px; left: 75px; background: rgba(26,179,148,1); padding: 7px 10px; border-radius: 50px; font-size: 15px; color: #ffffff; display:none; }
        .sort_in{ padding: 3px 5px; width: 40px; border-radius: 3px; border: 1px solid #b6b6b6; }
        #img_div{ float: left; }
        #img_div p, .file_div p ,#video_div p{ margin: 0px; padding: 3px; text-align: center; background: #d6d6d6; }
        .old_img_div{ display: inline-block; text-align: center; border: 1px solid #cfcfcf; padding-bottom: 5px; }
        .old_img_div p{ background-color: #b8b8b8; color: #fff; font-size: 15px; }
        .checkbox{ width: 16px; height: 16px; }
        .file_div{ display: inline-block; overflow: hidden; height: 150px; }

        .page{ font-size: 18px; text-align: center; padding: 10px 0px;}
        .page span{ padding: 2px 8px; margin-left: 3px; background: #009587; color: #fff; }
        .page a{ padding: 2px 8px; color: #009688; margin-left: 3px; border: 1px solid #e1e1e1; }
     </style>