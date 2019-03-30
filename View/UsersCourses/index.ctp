<?php //echo $this->element('menu');?>
<?php $this->start('css-embedded'); ?>
<style>
.btn-rest
{
	float: right;
}

@media only screen and (max-width:800px)
{
	.list-group-item-text span
	{
		display: block;
	}
}

.theme_old{
	display: inline-block;
}

</style>
<?php $this->end(); ?>
<div class="courses index">
	<div class="panel panel-success">
		<div class="panel-heading"><?php echo __('お知らせ'); ?></div>
		<div class="panel-body">
      <div class="panel-body-info">
			<?php if($info!=""){?>
			<div class="well">
				<?php
				$info = $this->Text->autoLinkUrls($info, array( 'target' => '_blank'));
				$info = nl2br($info);
				echo $info;
				?>
			</div>
			<?php }?>
			
			<?php if(count($infos) > 0){?>
			<table cellpadding="0" cellspacing="0">
			<tbody>
			<?php foreach ($infos as $info): ?>
			<tr>
				<td width="100" valign="top"><?php echo h(Utils::getYMD($info['Info']['created'])); ?></td>
				<td><?php echo $this->Html->link($info['Info']['title'], array('controller' => 'infos', 'action' => 'view', $info['Info']['id'])); ?></td>
			</tr>
			<?php endforeach; ?>
			</tbody>
			</table>
			<div class="text-right"><?php echo $this->Html->link(__('一覧を表示'), array('controller' => 'infos', 'action' => 'index')); ?></div>
			<?php }?>
			<?php echo $no_info;?>
      </div>
		</div>
	</div>

  <div class="panel panel-default">
		<div class="panel-heading"><?php echo __('テーマと時間'); ?></div>
    <div class = "panel-body">
			<div class = "theme_old">
				<?php 
					echo $this->Form->create('LearningTime');
					echo $this->Form->hidden('user_id',array('value' => $post_id));
					echo $this->Form->input('theme',array(
						'label' => __('勉強テーマ'),
						'div' => false,
						'class' => 'theme_box1',
						'options' => $select_list,
						'empty' => '',
						'style' => 'display: inline-block;'
					));
					echo $this->Form->input('theme_new',array(
						'label' => __('テーマは選択肢の中にない場合、ここで入力できる'),
						'div' => false,
						'class' => 'theme_box2',
						'style' => 'display: inline-block;'
					));
				?>
			</div>
			<div class = "search-date">
				<?php 
					echo $this->Form->input('today_date',array(
						'type' => 'date',
						'dateFormat' => 'YMD',
						'monthNames' => false,
						'timeFormat' => '24',
						'minYear' => date('Y') - 1,
						'maxYear' => date('Y'),
						'separator' => ' / ',
						'label' => '今日:',
						'class' => 'form-inline',
						'style' => 'display:inline;',
						'value' => $today_date
					));
					echo $this->Form->input('time',array('label' => __('勉強時間（分）')));
				?>
			</div>
		
			<div class = "submit">
				<input name = "submit" value = "提出" type = "submit">
				<input name = "learningtime" value = "学習時間履歴" type = "submit">
				<?php
					echo $this->Form->end();
				?>
			</div>
		</div>
	</div>

	<div class="panel panel-info">
		<div class="panel-heading"><?php echo __('コース一覧'); ?></div>
		<div class="panel-body">
			<ul class="list-group">
			<?php foreach ($courses as $course): ?>
			<?php //debug($course)?>
				<a href="<?php echo Router::url(array('controller' => 'contents', 'action' => 'index', $course['Course']['id']));?>" class="list-group-item">
					<?php if($course[0]['left_cnt']!=0){?>
					<button type="button" class="btn btn-danger btn-rest"><?php echo __('残り')?> <span class="badge"><?php echo h($course[0]['left_cnt']); ?></span></button>
					<?php }?>
					<h4 class="list-group-item-heading"><?php echo h($course['Course']['title']);?></h4>
					<p class="list-group-item-text">
						<span><?php echo __('学習開始日').': '.Utils::getYMD($course['Record']['first_date']); ?></span>
						<span><?php echo __('最終学習日').': '.Utils::getYMD($course['Record']['last_date']); ?></span>
					</p>
				</a>
			<?php endforeach; ?>
			<?php echo $no_record;?>
		</ul>
		</div>
	</div>
</div>
