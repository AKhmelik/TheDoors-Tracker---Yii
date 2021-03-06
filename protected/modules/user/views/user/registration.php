<?php $this->pageTitle=UserModule::t("Registration");
$this->breadcrumbs=array(
	UserModule::t("Registration"),
);
?>

<?php if(Yii::app()->user->hasFlash('registration')): ?>
<div class="success">
<?php echo Yii::app()->user->getFlash('registration'); ?>
</div>
<?php else: ?>

<div class="form text-center">
<?php $form=$this->beginWidget('UActiveForm', array(
	'id'=>'registration-form',
	'enableAjaxValidation'=>true,
	'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
	'htmlOptions' => array('enctype'=>'multipart/form-data'),
)); ?>


	<?php echo $form->errorSummary(array($model,$profile)); ?>
	
	<div class="row-fluid">
		<div class="span12">
			<div class="">
				<?php echo $form->labelEx($model,'username'); ?>
				<?php echo $form->textField($model,'username'); ?>
				<?php echo $form->error($model,'username'); ?>
			</div>
			<div class="">
				<?php echo $form->labelEx($model,'email'); ?>
				<?php echo $form->textField($model,'email'); ?>
				<?php echo $form->error($model,'email'); ?>
			</div>
		</div>
		</div>
	<div class="row-fluid">
		<div class="span12">
			<div class="">
				<?php echo $form->labelEx($model,'password'); ?>
				<?php echo $form->passwordField($model,'password'); ?>
				<?php echo $form->error($model,'password'); ?>
			</div>
			<div class="">
				<?php echo $form->labelEx($model,'verifyPassword'); ?>
				<?php echo $form->passwordField($model,'verifyPassword'); ?>
				<?php echo $form->error($model,'verifyPassword'); ?>
			</div>
		</div>
	</div>
	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>
	<div class="row-fluid">
		<div class="span12">
			<?php
			$profileFields=$profile->getFields();
						// fitstname and lastname makes form too heavy. so let it be hidden
			if (false && $profileFields) {
				foreach($profileFields as $field) {
					?>
					<div class="row">
						<?php echo $form->labelEx($profile,$field->varname); ?>
						<?php
						if ($widgetEdit = $field->widgetEdit($profile)) {
							echo $widgetEdit;
						} elseif ($field->range) {
							echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
						} elseif ($field->field_type=="TEXT") {
							echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
						} else {
							echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
						}
						?>
						<?php echo $form->error($profile,$field->varname); ?>
					</div>
					<?php
				}
			}
			?>

			<div class="row submit">
				<?php echo CHtml::submitButton(UserModule::t("Register"),array('class'=>'btn btn-success')); ?>
			</div>
			</div>
	




<?php $this->endWidget(); ?>
</div><!-- form -->
<?php endif; ?>