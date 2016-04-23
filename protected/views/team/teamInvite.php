<h1>Invites</h1>
<?php echo CHtml::errorSummary($teamUsers); ?>
<?php echo CHtml::beginForm(); ?>
<?php echo CHtml::activeDropDownList($teamUsers,'team_id',CHtml::listData($teamInvitedArray,'team_id','Team.name'), array('empty' => '(Select a team)'));?>
<?php echo CHtml::submitButton('Accept'); ?>
<?php echo CHtml::link('Create Team',array('team/create'),array('class'=>'btn btn-primary')); ?>

<?php echo CHtml::endForm(); ?>
