<?php echo $this->element('admin_menu');?>
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
    var theme = '<?php echo "今週の".$theme_selected."時間一覧";?>';
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
      text: theme
    },
    axisX:{
      labelAutoFit: true,
      //interval: 2,
      //labelFontSize: 20,
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
    var theme = '<?php echo "今月の".$theme_selected."時間一覧";?>';
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
      text: theme
    },
    axisX:{
      labelAutoFit: true, 
      //interval: 2,
      //labelFontSize: 20,
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

  function makeUntilNow(){
    var data_list = [];
    var theme = '<?php echo "今までの".$theme_selected."時間一覧";?>';
  <?php foreach($UntilNowData as $un): ?>
    var label_d = '<?php echo $un['username'];?>';
    var y_d = '<?php echo $un['sum'];?>';
    var y_d = Number(y_d);
    data_list.push({
      label: label_d,
      y:y_d
    });
  <?php endforeach; ?>
  var chart = new CanvasJS.Chart("chartContainer",{
    title:{
      text: theme
    },
    axisX:{
      labelAutoFit: true, 
      //interval: 2,
      //labelFontSize: 20,
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

<div class = "info">
  <?php
    echo $this->Form->create('Theme');
    echo $this->Form->input('theme',array(
      'label' => __('テーマ'),
      'options' => $select_list,
      'empty' => ''
    ));
  ?>
  <div class = "submit">
    <input name = "search" value = "検索" type = "submit">
    <?php echo $this->Form->end(); ?>
  </div>
</div>

<div>
  <button id = "Week" type = "button">週間</button>
  <button id = "Month" type = "button">月間</button>
  <button id = "UntilNow" type = "button">今まで</button>
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
  
  var UntilNowButton = document.getElementById("UntilNow");
  if(UntilNowButton != null){
    UntilNowButton.addEventListener("click",makeUntilNow);    
  }
</script>
<div style = "width:100%; height:500px; overflow-y:scroll">
  <div id = "chartContainer"></div>
</div>
