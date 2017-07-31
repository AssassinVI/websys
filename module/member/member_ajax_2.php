<?php
 require_once '../../core/inc/security.php';
require_once '../../core/inc/config.php';

$db=pdo_conn();
$table='appMember';
 
//获取Datatables发送的参数 必要
$draw = $_GET['draw'];//这个值作者会直接返回给前台
 
//排序
$order_column = $_GET['order']['0']['column'];//那一列排序，从0开始
$order_dir = $_GET['order']['0']['dir'];//ase desc 升序或者降序
 
//拼接排序sql
$orderSql = "";
if(isset($order_column)){
    $i = intval($order_column);
    switch($i){
        case 0;$orderSql = " order by name ".$order_dir;break;
        case 1;$orderSql = " order by phone ".$order_dir;break;
        case 2;$orderSql = " order by email ".$order_dir;break;
        default;$orderSql = '';
    }
}
//搜索
$search = $_GET['search']['value'];//获取前台传过来的过滤条件
 
//分页
$start = $_GET['start'];//从多少开始
$length = $_GET['length'];//数据长度
$limitSql = '';
$limitFlag = isset($_GET['start']) && $length != -1 ;
if ($limitFlag ) {
    $limitSql = " LIMIT ".intval($start).", ".intval($length);
}
 
//定义查询数据总记录数sql
$sumSql = "SELECT count(Tb_index) as sum FROM ".$table;
//条件过滤后记录数 必要
$recordsFiltered = 0;
//表的总记录数 必要
$recordsTotal = 0;
$recordsTotalResult = $db->query($sumSql);
while ($row = $recordsTotalResult->fetchArray(SQLITE3_ASSOC)) {
    $recordsTotal =  $row['sum'];
}
//定义过滤条件查询过滤后的记录数sql
$sumSqlWhere =" where name||phone||email LIKE '%".$search."%'";
if(strlen($search)>0){
    $recordsFilteredResult = $db->query($sumSql.$sumSqlWhere);
    while ($row = $recordsFilteredResult->fetchArray(SQLITE3_ASSOC)) {
        $recordsFiltered =  $row['sum'];
    }
}else{
    $recordsFiltered = $recordsTotal;
}
 
//query data
$totalResultSql = "SELECT name, phone, email FROM ".$table;
$infos = array();
if(strlen($search)>0){
    //如果有搜索条件，按条件过滤找出记录
    $dataResult = $db->query($totalResultSql.$sumSqlWhere.$orderSql.$limitSql);
    while ($row = $dataResult->fetchArray(SQLITE3_ASSOC)) {
        $obj = array($row['name'], $row['phone'], $row['email']);
        array_push($infos,$obj);
    }
}else{
    //直接查询所有记录
    $dataResult = $db->query($totalResultSql.$orderSql.$limitSql);
    while ($row = $dataResult->fetchArray(SQLITE3_ASSOC)) {
        $obj = array($row['name'], $row['phone'], $row['email']);
        array_push($infos,$obj);
    }
}
 
/*
 * Output 包含的是必要的
 */
echo json_encode(array(
    "draw" => intval($draw),
    "recordsTotal" => intval($recordsTotal),
    "recordsFiltered" => intval($recordsFiltered),
    "data" => $infos
),JSON_UNESCAPED_UNICODE);
 
 
function fatal($msg)
{
    echo json_encode(array(
        "error" => $msg
    ));
    exit(0);
}