<?php echo $this->element('admin_menu');?>
<?php $this->start('css-embedded'); ?>
<?php $this->Html->script('canvasjs.min',array('inline' => false));?>
<style type='text/css'>
	#RecordFromDateYear,
	#RecordToDateYear
	{
		width		: 100px;
	}
	
	#RecordFromDateMonth,
	#RecordToDateMonth,
	#RecordFromDateDay,
	#RecordToDateDay
	{
		width		: 80px;
	}
	
	#RecordCourseId
	{
		max-width	: 200px;
	}
	
	#RecordGroupId
	{
		max-width	: 150px;
	}
	
	#RecordUserId
	{
		max-width	: 120px;
	}
	
	input[type='text'], textarea,
	.form-control, 
	label
	{
		font-size	: 12px;
		font-weight	: normal;
		height		: 30px;
		padding		: 4px;
	}
	
	.ib-search-buttons
	{
		float		: right;
	}
	
	.ib-search-buttons .btn
	{
		margin-right: 10px;
	}
	
	table tr td
	{
		padding		: 5px;
	}
	
	.ib-row
	{
		width: 100%;
		height: 40px;
	}
</style>
<?php $this->end(); ?>
<?php $this->start('script-embedded'); ?>
<script>
	function openRecord(course_id, user_id)
	{
		window.open(
			'<?php echo Router::url(array('controller' => 'contents', 'action' => 'record')) ?>/'+course_id+'/'+user_id,
			'irohaboard_record',
			'width=1100, height=700, menubar=no, toolbar=no, scrollbars=yes'
		);
	}
	
	function openTestRecord(content_id, record_id)
	{
		window.open(
			'<?php echo Router::url(array('controller' => 'contents_questions', 'action' => 'record')) ?>/'+content_id+'/'+record_id,
			'irohaboard_record',
			'width=1100, height=700, menubar=no, toolbar=no, scrollbars=yes'
		);
	}
	
	function downloadCSV()
	{
		var url = '<?php echo Router::url(array('action' => 'csv')) ?>/' + $('#MembersEventEventId').val() + '/' + $('#MembersEventStatus').val() + '/' + $('#MembersEventUsername').val();
		$("#RecordCmd").val("csv");
		$("#RecordAdminIndexForm").submit();
		$("#RecordCmd").val("");
	}

    function recordGraph(){
      var data_list = [];
      <?php foreach($record_array as $info):?>
        var label_d = '<?php echo $info['username'];?>'
        var y_d = '<?php echo $info['score'];?>'
        var y_d = Number(y_d);
        var title_d = '<?php echo (isset($records[0]['Content']['title']) ? $records[0]['Content']['title'] : "") ;?>'

        data_list.push({
          label: label_d,
          y: y_d    
        });
      <?php endforeach;?>
      var chart = new CanvasJS.Chart("chartContainer",{
        height: 1000,
        axisX:{
          //labelAutoFit: true  
          labelFontSize: 20,
          interval: 2,
        },
        title:{
          text: title_d,
          fontSize: 20,
        },
        data:[{
          type: 'bar',
          dataPoints: data_list    
        }]
      });
      chart.render();
    }
</script>
<?php $this->end(); ?>
<div class="records index">
  <?php echo $this->log($records);?>
	<div class="ib-page-title"><?php echo __('学習履歴一覧'); ?></div>
	<div class="ib-horizontal">
		<?php
			echo $this->Form->create('Record');
			echo '<div class="ib-search-buttons">';
			echo $this->Form->submit(__('検索'),	array('class' => 'btn btn-info', 'div' => false));
			echo $this->Form->hidden('cmd');
			echo '<button type="button" class="btn btn-default" onclick="downloadCSV()">'.__('CSV出力').'</button>';
            ?>
        <button id = "scoreGraph" type = "button">成績グラフ</button>
        </div>
        <script>
          var scoreGraphButton = document.getElementById("scoreGraph");
          if(scoreGraphButton != null){
            scoreGraphButton.addEventListener("click",recordGraph);    
          }
        </script>
        <?php
			echo '<div class="ib-row">';
			echo $this->Form->input('course_id',		array('label' => 'コース :', 'options'=>$courses, 'selected'=>$course_id, 'empty' => '全て', 'required'=>false, 'class'=>'form-control'));
			echo $this->Form->input('content_category',	array('label' => 'コンテンツ種別 :', 'options'=>Configure::read('content_category'), 'selected'=>$content_category, 'empty' => '全て', 'required'=>false, 'class'=>'form-control'));
			//echo $this->Form->input('contenttitle',		array('label' => 'コンテンツ名 :', 'value'=>$contenttitle, 'class'=>'form-control'));
      echo $this->Form->input('contenttitle', array('label' => 'コンテンツ名:', 'options' => $contents, 'selected' => $contenttitle, 'empty' => '全て', 'required' => false, 'class' => 'form-control'));
			echo '</div>';
			
			echo '<div class="ib-row">';
			echo $this->Form->input('group_id',		array('label' => 'グループ :', 'options'=>$groups, 'selected'=>$group_id, 'empty' => '全て', 'required'=>false, 'class'=>'form-control'));
			echo $this->Form->input('user_id',		array('label' => 'ユーザ :', 'options'=>$users, 'selected'=>$user_id, 'empty' => '全て', 'required'=>false, 'class'=>'form-control'));
			echo '</div>';
			
			echo '<div class="ib-search-date-container">';
			echo $this->Form->input('from_date', array(
				'type' => 'date',
				'dateFormat' => 'YMD',
				'monthNames' => false,
				'timeFormat' => '24',
				'minYear' => date('Y') - 5,
				'maxYear' => date('Y'),
				'separator' => ' / ',
				'label'=> '対象日時 : ',
				'class'=>'form-control',
				'style' => 'display: inline;',
				'value' => $from_date
			));
			echo $this->Form->input('to_date', array(
				'type' => 'date',
				'dateFormat' => 'YMD',
				'monthNames' => false,
				'timeFormat' => '24',
				'minYear' => date('Y') - 5,
				'maxYear' => date('Y'),
				'separator' => ' / ',
				'label'=> '～',
				'class'=>'form-control',
				'style' => 'display: inline;',
				'value' => $to_date
			));
			echo '</div>';
			echo $this->Form->end();
		?>
	</div>
	<table cellpadding="0" cellspacing="0">
	<thead>
	<tr>
    <th class = "ib-col-center nowrap">
    <?php 
      echo $this->Paginator->sort('is_check','チェック');
    ?>
    </th>
		<th nowrap><?php echo $this->Paginator->sort('course_id', 'コース'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('content_id', 'コンテンツ'); ?></th>
		<th nowrap><?php echo $this->Paginator->sort('User.name', '氏名'); ?></th>
		<th nowrap class="ib-col-center"><?php echo $this->Paginator->sort('score', '正解数'); ?></th>
		<th class="ib-col-center" nowrap><?php echo $this->Paginator->sort('pass_score', '問題数'); ?></th>
		<th nowrap class="ib-col-center"><?php echo $this->Paginator->sort('is_passed', '結果'); ?></th>
		<th class="ib-col-center" nowrap><?php echo $this->Paginator->sort('understanding', '理解度'); ?></th>
		<th class="ib-col-center"><?php echo $this->Paginator->sort('study_sec', '学習時間'); ?></th>
		<th class="ib-col-datetime"><?php echo $this->Paginator->sort('created', '学習日時'); ?></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($records as $record): ?>
	<tr>
    <td nowrap class="ib-col-center"><?php 
      if($record['Record']['is_check'] == 5){
      echo h(Configure::read('record_understanding.'.$record['Record']['is_check'])); 
      }
      ?>&nbsp;</td>
		
    <td><a href="javascript:openRecord(<?php echo h($record['Course']['id']); ?>, <?php echo h($record['User']['id']); ?>);"><?php echo h($record['Course']['title']); ?></a></td>
		
    <td><?php echo h($record['Content']['title']); ?>&nbsp;</td>
		
    <td><?php echo h($record['User']['name']); ?>&nbsp;</td>
		
    <td class="ib-col-center"><?php echo h($record['Record']['score']); ?>&nbsp;</td>
	  
    <td class="ib-col-center"><?php echo h($record['Record']['full_score']); ?>&nbsp;</td>

		<?php 
      if($record['Record']['full_score']!=0){
        $result = $record['Record']['score'] / $record['Record']['full_score'];
        $result = round($result * 100);
      }
    ?>
    <td nowrap class="ib-col-center"><a href="javascript:openTestRecord(<?php echo h($record['Content']['id']); ?>, <?php echo h($record['Record']['id']); ?>);"><?php 
      //echo Configure::read('record_result.'.$record['Record']['is_passed']); 
      if($record['Record']['full_score']!=0){
        echo "$result %";
      }
?></a></td>
		
    <td nowrap class="ib-col-center"><?php echo h(Configure::read('record_understanding.'.$record['Record']['understanding'])); ?>&nbsp;</td>
		
    <td class="ib-col-center"><?php echo h(Utils::getHNSBySec($record['Record']['study_sec'])); ?>&nbsp;</td>
		
    <td class="ib-col-date"><?php echo h(Utils::getYMDHN($record['Record']['created'])); ?>&nbsp;</td>
	</tr>
	<?php endforeach; ?>
	</tbody>
	</table>
	<?php echo $this->element('paging');?>
</div>
<div style = "width:100%; height:500px; overflow-y:scroll">
  <div id = "chartContainer"></div>
</div>
