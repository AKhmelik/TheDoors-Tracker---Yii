<?php
$this->pageTitle=UserModule::t("Login");
$this->breadcrumbs=array(
	UserModule::t("Login"),
);
?>

<?php if(Yii::app()->user->hasFlash('loginMessage')): ?>

<div class="success">
	<?php echo Yii::app()->user->getFlash('loginMessage'); ?>
</div>

<?php endif; ?>

<!--<p><?php /*echo UserModule::t("Please fill out the following form with your login credentials:"); */?></p>
-->
<div class="form" style="text-align: center">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'login-form',
		'enableClientValidation'=>true,
		'clientOptions'=>array(
			'validateOnSubmit'=>true,
		),
	)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'username'); ?>
		<?php echo $form->textField($model,'username'); ?>
		<?php echo $form->error($model,'username'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'password'); ?>
		<?php echo $form->passwordField($model,'password'); ?>
		<?php echo $form->error($model,'password'); ?>
	</div>
	<div class="row rememberMe">
		<?php echo $form->checkBox($model,'rememberMe'); ?>
		<?php echo $form->label($model,'rememberMe'); ?>
		<?php echo $form->error($model,'rememberMe'); ?>
	</div>
	<p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?></p>

	<div class="row">
		<p class="hint">
			<?php echo CHtml::link('Terms and contions','http://eqbeat.ru/termsandcontions.html',array('class'=>'')); ?>
		</p>
		<p class="hint">
			<?php echo CHtml::link(UserModule::t("Register"),Yii::app()->getModule('user')->registrationUrl,array('class'=>'registration-action')); ?> | <?php echo CHtml::link(UserModule::t("Lost Password?"),Yii::app()->getModule('user')->recoveryUrl); ?>
		</p>
	</div>


	<div class="row submit">
		<?php echo CHtml::submitButton(UserModule::t("Login"),array('class'=>'btn btn-success','style'=>'margin-top:0;margin-bottom:0')); ?>
		<?php echo CHtml::link(UserModule::t("Login with Facebook"),'/hybridauth/default/login/?provider=Facebook',array('class'=>'btn btn-primary')); ?>
		<?php echo CHtml::link(UserModule::t("Login with Google"),'/hybridauth/default/login/?provider=Google',array('class'=>'btn btn-primary')); ?>
		<?php //$this->widget('application.modules.hybridauth.widgets.renderProviders'); ?>
	</div>

	<?php $this->endWidget(); ?>
    <div class="row">
        <p class="hint">
        </p>
    </div>
</div><!-- form -->


<?php
$form = new CForm(array(
    'elements'=>array(
        'username'=>array(
            'type'=>'text',
            'maxlength'=>32,
        ),
        'password'=>array(
            'type'=>'password',
            'maxlength'=>32,
        ),
        'rememberMe'=>array(
            'type'=>'checkbox',
        )
    ),

    'buttons'=>array(
        'login'=>array(
            'type'=>'submit',
            'label'=>'Login',
        ),
    ),
), $model);
?>