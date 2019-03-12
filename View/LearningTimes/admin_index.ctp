<?php echo $this->element('admin_menu');?>
<?php //print_r($WeekAllData);?>
<?php
  $this->Html->script('canvasjs.min',array('inline'=>false));
?>
<style>
.time_sum{
  font-size: 20px;
}
</style>
<script language = "javascript" type = "text/javascript" >
  function makeWeek(){
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
  var chart = new CanvasJS.Chart("chartContainer",{
    title:{
      text: "今週のプログラミング勉強時間一覧"
    },
    axisX:{
      //labelAutoFit: true  
      interval: 2,
    },
    height: 500,
    data:[{
      //type: 'column',
      type: 'bar',
      dataPoints: data_list
    }]
  });
    chart.render();
  }

  function makeMonth(){
    var data_list = [];
  <?php foreach($MonthAllData as $wdata): ?>
    var label_d = '<?php echo $wdata['username'];?>';
    var y_d = '<?php echo $wdata['sum'];?>';
    var y_d = Number(y_d);
    data_list.push({
      label: label_d,
      y:y_d
    });
  <?php endforeach; ?>
  var chart = new CanvasJS.Chart("chartContainer",{
    title:{
      text: "今月のプログラミング勉強時間一覧"
    },
    axisX:{
      //labelAutoFit: true  
      interval: 2,
    },
    height: 500,
    data:[{
      //type: 'column',
      type: 'bar',
      dataPoints: data_list
    }]
  });
    chart.render();
  }
</script>
<div>
  <button id = "Week" type = "button">週間</button>
  <button id = "Month" type = "button">月間</button>
</div>
<script>
  var weekButton = document.getElementById("Week");
  if(weekButton != null){
    weekButton.addEventListener("click",makeWeek);    
  }
  
  var monthButton = document.getElementById("Month");
  if(monthButton != null){
    monthButton.addEventListener("click",makeMonth);    
  }
</script>
<div id = "chartContainer"></div>
