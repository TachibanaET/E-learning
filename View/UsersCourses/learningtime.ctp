<a href = "javascript:history.back()">[戻る]</a>
<?php 
      $theme = $theme['theme'];
      //今日
      if(!$d_data){
        $d_data = 0;
      }else{
        $d_data = $d_data['day'];
      }
      //今週
      if(!$w_data){
        $w_data = 0;
      }else{
        $w_data = $w_data['week'];
      }
      //今月
      if(!$m_data){
        $m_data = 0;
      }else{
        $m_data = $m_data['month'];
      }
      //今まで
      if(!$sumAll){
        $sumAll = 0;
      }else{
        $sumAll = $sumAll['sum'];
      }
?>
<?php
  $this->Html->script('canvasjs.min',array('inline'=>false));
?>
<style>
.time_sum{
  font-size: 20px;
}
.rank{
  font-size: 20px;
  text-align: right;
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
    var theme = '<?php echo "今週の".$theme."時間";?>';
    var chart = new CanvasJS.Chart("chartContainer",{
      title:{
        text: theme
      },
      data:[{
        type: 'line',
        dataPoints: data_list
      }]
    });
    chart.render();
  }

  function makeMonth(){
    var data_list = [];
    var theme = '<?php echo "今月の".$theme."時間";?>';
  <?php foreach($ma_data as $ma): ?>
    var label_d = '<?php echo $ma[0]['date'];?>';
    var y_d = '<?php echo $ma[0]['sum'];?>';
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
      data:[{
        type: 'line',
        dataPoints: data_list
      }]
    });
    chart.render();
  }
  function makeUntilNow(){
    var data_list = [];
    var theme = '<?php echo "今までの".$theme."時間";?>';
  <?php foreach($untilNow as $un): ?>
    var label_d = '<?php echo $un[0]['date'];?>';
    var y_d = '<?php echo $un[0]['sum'];?>';
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
      data:[{
        type: 'line',
        dataPoints: data_list
      }]
    });
    chart.render();
  }

</script>
<div class = "rank">
  <?php echo __($theme."時間 Rank:$rank");?>
</div>
<div class = "time_sum">
  <div class = "time_day"><?php echo __("今日の".$theme."時間は $d_data 分です");?></div>
  <br />
  <div class = "time_week"><?php echo __("今週の".$theme."時間は $w_data 分です");?></div>
  <br />
  <div class = "time_month"><?php echo __("今月の".$theme."時間は $m_data 分です");?></div>
  <br />
  <div class = "time_All"><?php echo __("今までの".$theme."時間は $sumAll 分です");?></div>
</div>
<?php
  if($theme_id){
  echo '<div>';
  echo '<button id = "Week" type = "button">週間の時間グラフ</button>';
  echo '<button id = "Month" type = "button">月間の時間グラフ</button>';
  echo '<button id = "UntilNow" type = "button">今までの時間グラフ</button>';
  echo '</div>';
  echo'<script>';

  echo 'var weekButton = document.getElementById("Week");';
  echo 'if(weekButton != null){';
  echo  'weekButton.addEventListener("click",makeWeek);}';

  echo 'var MonthButton = document.getElementById("Month");';
  echo 'if(MonthButton != null){';
  echo 'MonthButton.addEventListener("click",makeMonth);}';

  echo 'var UntilNowButton = document.getElementById("UntilNow");';
  echo 'if(UntilNowButton != null){';
  echo 'UntilNowButton.addEventListener("click",makeUntilNow);}';

  echo '</script>';
}
?>
<div id = "chartContainer"></div>
