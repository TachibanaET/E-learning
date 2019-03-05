<?php print_r($d_data);?>
<br />
<?php print_r($w_data);?>
<br />
<?php print_r($m_data);?>
<br />
<?php 
  print_r($wd_data);
  $json_string = json_encode($wd_data);
?>
<br />
<?php if(!$d_data){
        $d_data = 0;
        echo "no d_data<br/>";
      }else{
        $d_data = $d_data['day'];
      }

      if(!$w_data){
        $w_data = 0;
        echo "no w_data<br/>";
      }else{
        $w_data = $w_data['week'];
      }

      if(!$m_data){
        $m_data = 0;
        echo "no m_data<br />";
      }else{
        $m_data = $m_data['month'];
      }
?>
<style>
.time_sum{
  font-size: 20px;
}
</style>
<div class = "time_sum">
  <div class = "time_day"><?php echo __("今日の勉強時間は $d_data 分です");?></div>
  <div class = "time_week"><?php echo __("今週の勉強時間は $w_data 分です");?></div>
  <div class = "time_month"><?php echo __("今月の勉強時間は $m_data 分です");?></div>
</div>
<?php //echo $wd_data[0][0]['week'];?>
<div id = "chartContainner"></div>
<?php
  $this->Html->script('canvasjs.min',array('inline'=>false));
?>
<!--<script type = "text/javascript" src = "canvasjs.min.js"></script>-->
<?php
  $json_string = json_encode($wd_data);
?>
<script type = "text/javascript">
  var data_list_2 =[
    {label: "りんご", y : 10},
    {label: "オレンジ", y : 15}
  ];
  console.log(data_list_2);
</script>
<script type = "text/javascript" >
  var data_list = [];
  <?php foreach($wd_data as $wdata): ?>
    var label_d = '<?php echo $wdata[0]['week'];?>';
    var y_d = '<?php echo $wdata[0]['sum'];?>';
    var y_d = Number(y_d);
    data_list.push({
      label: label_d,
      y:y_d
    });
  <?php endforeach; ?>
  console.log(data_list);
</script>
<div id = "chartContainer"></div>
<script>
  var chart = new CanvasJS.Chart("chartContainer",{
    title:{
      text: "今週の勉強時間"
    },
    data:[{
      type: 'line',
      dataPoints: data_list
    }]
  });
  chart.render();
</script>
<?php
  $this->Html->script('/js/graph');
?>
