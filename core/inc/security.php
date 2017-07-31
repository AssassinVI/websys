<?php 
 if (empty($_SESSION['admin_index'])) {
   echo "<script>";
   echo "location.replace('../../login.php');"; //網頁跳轉
   echo "alert('請登入系統');";
   echo "</script>";
   exit();
 }
 elseif ($_SESSION['admin_index']!=unlock_key($_SESSION['sys_login_key'])) {
   echo "<script>";
   echo "location.replace('../../login.php');"; //網頁跳轉
   echo "alert('錯誤登入方式');";
   echo "</script>";
   exit();
 }
?>