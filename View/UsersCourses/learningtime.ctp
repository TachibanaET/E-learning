<a href = "javascript:history.back()">[戻る]</a>
<?php 
      if(!$d_data){
        $d_data = 0;
      }else{
        $d_data = $d_data['day'];
      }

      if(!$w_data){
        $w_data = 0;
      }else{
        $w_data = $w_data['week'];
      }

      if(!$m_data){
        $m_data = 0;
      }else{
        $m_data = $m_data['month'];
      }
?>
<?php
  $this->Html->script('canvasjs.min',array('inline'=>false));
?>
<style>
.time_sum{
  font-size: 20px;
}
</style>
<script language = "javascript" type = "text/javascript">
  function makeWeek(){
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
    var chart = new CanvasJS.Chart("chartContainer",{
      title:{
        text: "今週のプログラミング勉強時間"
      },
      data:[{
        type: 'line',
        dataPoints: data_list
      }]
    });
    chart.render();
  }

  function makeMonth(){
    console.log(1);
    var data_list = [];
  <?php foreach($ma_data as $ma): ?>
    var label_d = '<?php echo $ma[0]['date'];?>';
    var y_d = '<?php echo $ma[0]['sum'];?>';
    var y_d = Number(y_d);
    data_list.push({
      label: label_d,
      y:y_d
    });
  <?php endforeach; ?>
  console.log(data_list);
    var chart = new CanvasJS.Chart("chartContainer",{
      title:{
        text: "今月のプログラミング勉強時間"
      },
      data:[{
        type: 'line',
        dataPoints: data_list
      }]
    });
    chart.render();
  }

</script>
<div class = "time_sum">
  <div class = "time_day"><?php echo __("今日のプログラミング勉強時間は $d_data 分です");?></div>
  <div class = "time_week"><?php echo __("今週のプログラミング勉強時間は $w_data 分です");?></div>
  <div class = "time_month"><?php echo __("今月のプログラミング勉強時間は $m_data 分です");?></div>
</div>
<div>
  <button id = "Week" type = "button">Week</button>
  <button id = "Month" type = "button">Month</button>
</div>
<script>
  var weekButton = document.getElementById("Week");
  if(weekButton != null){
    weekButton.addEventListener("click",makeWeek);
  }

  var MonthButton = document.getElementById("Month");
  if(MonthButton != null){
    MonthButton.addEventListener("click",makeMonth);
  }
</script>
<div id = "chartContainer"></div>
