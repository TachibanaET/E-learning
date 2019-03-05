<?php echo $this->element('admin_menu');?>
<?php print_r($WeekAllData);?>
<style>
.time_sum{
  font-size: 20px;
}
</style>
<!--
<div class = "time_sum">
  <div class = "time_day"><?php echo __("今日の勉強時間は $d_data 分です");?></div>
  <div class = "time_week"><?php echo __("今週の勉強時間は $w_data 分です");?></div>
  <div class = "time_month"><?php echo __("今月の勉強時間は $m_data 分です");?></div>
</div>
-->
<?php //echo $wd_data[0][0]['week'];?>
<div id = "chartContainner"></div>
<?php
  $this->Html->script('canvasjs.min',array('inline'=>false));
?>
<!--<script type = "text/javascript" src = "canvasjs.min.js"></script>-->
<?php
  //$json_string = json_encode($wd_data);
?>
<script type = "text/javascript" >
  var data_list = [];
  <?php foreach($WeekAllData as $wdata): ?>
    var label_d = '<?php echo $wdata['username'];?>';
    var y_d = '<?php echo $wdata['sum'];?>';
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
