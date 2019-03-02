<?php echo $this->element('admin_menu');?>
<div class="records index">
	<div class="ib-horizontal">
		<?php
			echo $this->Form->create('Soap');
      echo $this->Form->submit('CSV出力',array('name' => 'csv_output'));
      echo $this->Form->end();
		?>	
  </div>
<?php 
  echo $this->Form->create('Soap');
  echo $this->Form->hidden('user_id',array('value' => $post_id));
  echo $this->Form->input('body',		array('label' => __('SOAPはここに')));
  echo $this->Form->submit('Submit',array('name' => 'submit'));
  echo $this->Form->end();
?>
<div class="admin_soap">
  <table>
    <thead>
      <tr>
        <th class = "soap-datetime"><?php echo $this->Paginator->sort('created','作成日時')?></th>
        <th class = "soap-body"><?php echo __('SOAP')?></th>
      </tr>
    </thead>
    <tbody>
  <?php foreach($posts as $post): ?>
      <tr>
        <td><?php echo h($post['Soap']['created']) ;?></td>
        <td><?php echo $post['Soap']['body']; ?></td>
      </tr>
      <?php endforeach; ?>
      <?php unset($post); ?>
    </tbody>
  </table>
  <!--<?php echo $this->element('paging');?>-->
</div>
