<?php include("../../core/page/header01.php");//載入頁面heaer01 ?>
<style type="text/css">
    .flot-chart{ height: 250px; }
    .m-b-xl{ margin: 0px; }
    .text-no{ color: #F44336; }
     .c3 svg{ font-size: 11px; }
    .c3-legend-item{ font-size: 13px; }
</style>
<?php include("../../core/page/header02.php");//載入頁面heaer02?>
<?php 
if ($_GET) {
	
	if ($_GET['type']=='contact_del') { //刪除訊息-聯絡
		$where=array('Tb_index'=>$_GET['Tb_index']);
		pdo_delete('appContacts', $where);
	}
    elseif ($_GET['type']=='con_process') { //訊息處理-聯絡
        $param=array("process"=>$_GET['process']);
        $where=array('Tb_index'=>$_GET['Tb_index']);
        pdo_update('appContacts', $param, $where);
    }
    elseif($_GET['type']=='service_del'){ //刪除訊息-維修
        $where=array('Tb_index'=>$_GET['Tb_index']);
        pdo_delete('appService', $where);
    }
    elseif($_GET['type']=='ser_process'){ //訊息處理-維修
        $param=array("is_deal"=>$_GET['process']);
        $where=array('Tb_index'=>$_GET['Tb_index']);
        pdo_update('appService', $param, $where);
    }
}

?>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="p-w-md m-t-sm">
        <div class="row">
            <div class="col-lg-12">
                <h2> 每日流量</h2>
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="flot-chart m-b-xl">
                            <div class="flot-chart-content" id="date_use"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <h2>新進訊息</h2>
                <div class="ibox">
                    <div class="ibox-content">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                  <th>#</th>  
                                  <th>編號</th>  
                                  <th>E-mail</th> 
                                  <th>姓名</th>  
                                  <th>訊息</th>   
                                </thead>
                                <tbody>
                                <?php 
                                  $pdo=pdo_conn();
                                  $sql=$pdo->prepare("SELECT * FROM appContacts WHERE webLang=:webLang AND process='0' ORDER BY StartDate DESC LIMIT 0,5");
                                  $sql->execute(array(":webLang"=>$weblang));
                                ?>
                                    <?php $i=1; while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {?>
                                    <tr>
                                        <td><?php echo $i?></td>
                                        
                                        <td nowrap><a href="../msg/manager.php?MT_id=<?php echo $row['mt_id'];?>&Tb_index=<?php echo $row['Tb_index'];?>"><?php echo $row['Tb_index']?></a> </td>
                                        <td><a href="mailto:<?php echo $row['UserMail']?>"><?php echo $row['UserMail']?></a></td>
                                        <td nowrap><?php echo $row['UserName']?></td>
                                        <td>
                                          <?php echo $row['UserMsg']?>
                                        </td>
                                        <td nowrap>
                                        <?php if($row['process']=='0'){?>
                                        <a href="index.php?type=con_process&process=1&Tb_index=<?php echo $row['Tb_index']?>" class="text-no"><i class="fa fa-times "></i> 未處理</a> ｜
                                        <?php }else{?>
                                        <a href="index.php?type=con_process&process=0&Tb_index=<?php echo $row['Tb_index']?>" class="text-navy"><i class="fa fa-check-square-o "></i> 已處理</a> ｜
                                        <?php }?>
                                        <a href="index.php?type=contact_del&Tb_index=<?php echo $row['Tb_index']?>" class="text-muted"
                                           onclick="if (!confirm('確定要刪除 [<?php echo $row['UserMsg']?>] ?')) {return false;}">
                                        <i class="fa fa-trash "></i> 刪除</a></td>
                                    </tr>
                                    <?php $i++; } $pdo=NULL;?>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
        
<?php  include("../../core/page/footer01.php");//載入頁面heaer02?>
   <!-- Flot -->
    <script src="../../js/plugins/flot/jquery.flot.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.tooltip.min.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.spline.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.resize.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.pie.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.symbol.js"></script>
    <script src="../../js/plugins/flot/jquery.flot.time.js"></script>



    <!-- Sparkline -->
    <script src="../../js/plugins/sparkline/jquery.sparkline.min.js"></script>



    <script>
        $(document).ready(function() {

          /*  var sparklineCharts = function(){
                $("#sparkline1").sparkline([34, 43, 43, 35, 44, 32, 44, 52], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1ab394',
                    fillColor: "transparent"
                });

                $("#sparkline2").sparkline([32, 11, 25, 37, 41, 32, 34, 42], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1ab394',
                    fillColor: "transparent"
                });

                $("#sparkline3").sparkline([34, 22, 24, 41, 10, 18, 16,8], {
                    type: 'line',
                    width: '100%',
                    height: '50',
                    lineColor: '#1C84C6',
                    fillColor: "transparent"
                });
            };

            var sparkResize;

            $(window).resize(function(e) {
                clearTimeout(sparkResize);
                sparkResize = setTimeout(sparklineCharts, 500);
            });

            sparklineCharts();*/

<?php 
  $pdo=pdo_conn();
  $sql=$pdo->prepare("SELECT * FROM OneDayChart ORDER BY ChartDate DESC LIMIT 0,30");
  $sql->execute();
?>

      //每日使用人數
      c3.generate({
                bindto: '#date_use',
                data:{
                   x:'x',
                   xFormat: '%Y%m%d',
                    columns: [

                    <?php 
                       
                       $date_txt="['x',";
                       $num_txt="['使用人數',";
                       $i=0;
                      while ($row=$sql->fetch(PDO::FETCH_ASSOC)) {

                        $date=explode('-', $row['ChartDate']);
                        $day=$date[0].$date[1].$date[2];

                            $date_txt.="'".$day."',";
                            $num_txt.= $row['ChartNum'].",";
                        $i++;
                      }
                         $date_txt.="],";
                         $num_txt.= "],";
                        echo $date_txt;
                        echo $num_txt;
                      $pdo=NULL;
                      ?>
                       
                    ],
                    colors:{
                        data1: '#1ab394',
                        
                    },
                    type: 'line',
                    labels: true
                },
                axis:{
                   x:{
                     type:'timeseries',
                      tick:{
                          
                          count:4,
                          format: '%m-%d'
                      }
                   }
                }
            });



           /* var data1 = [
                [0,4],[1,8],[2,5],[3,10],[4,4],[5,16],[6,5],[7,11],[8,6],[9,11],[10,20],[11,10],[12,13],[13,4],[14,7],[15,8],[16,12]
            ];
            var data2 = [
                [0,0],[1,2],[2,7],[3,4],[4,11],[5,4],[6,2],[7,5],[8,11],[9,5],[10,4],[11,1],[12,5],[13,2],[14,5],[15,2],[16,0]
            ];
            $("#flot-dashboard5-chart").length && $.plot($("#flot-dashboard5-chart"), [
                        data1
                    ],
                    {
                        series: {
                            lines: {
                                show: false,
                                fill: false
                            },
                            splines: {
                                show: true,
                                tension: 0.4,
                                lineWidth: 1,
                                fill: 0.4
                            },
                            points: {
                                radius: 0,
                                show: true
                            },
                            shadowSize: 2
                        },
                        grid: {
                            hoverable: true,
                            clickable: true,

                            borderWidth: 2,
                            color: 'transparent'
                        },
                        colors: ["#1ab394", "#1C84C6"],
                        xaxis:{
                        },
                        yaxis: {
                        },
                        tooltip: false
                    }
            );*/

        });
    </script>

<?php  include("../../core/page/footer02.php");//載入頁面heaer02?>

