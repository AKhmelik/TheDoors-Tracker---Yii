<?php echo CHtml::scriptFile(Yii::app()->request->baseUrl . "/js/click-handler.js");?>
<div class="content-wrapper">
<h1><?=Yii::t('app', 'Manage')?> <?php echo $team->name.' '?> <?=Yii::t('app', 'Group')?> </h1>
<?php echo CHtml::beginForm();
$this->widget(
    'bootstrap.widgets.TbSelect2',
    array(
        'asDropDownList' => true,
        'name' => 'user_id_new',
        'data' => $users,
        'options' => array(
            'val' => $users,
            'placeholder' => Yii::t('app','Enter user name'),
            'width' => '40%',
            'tokenSeparators' => array(',', ' ')
        )
    )
);?>&nbsp;
 <?php echo CHtml::submitButton(Yii::t('app', 'Invite to group'), ['class' =>'btn btn-primary']); ?>
<a class=" leave-team btn btn-primary btn-danger"  href="#"><i class="icon-plane icon-white"></i><?=Yii::t('app', 'Leave Group')?></a>
<?php echo CHtml::endForm(); ?>


<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'tbl-team-grid',
    'dataProvider'=>$dataProvider,

    'columns'=>array(
        'User.username',
        /*
        'end_point_name',
        'user_host_id',
        */
        array
        (
            'afterDelete'=>'function(link,success,data){
            window.location.href = window.location.href;
            }',
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{display}{hide}{delete}',
            'deleteConfirmation'=> false,
            'buttons' => array( // HERE
                'display' => array(
                    'url' => 'Yii::app()->controller->createUrl("userHide", array("id"=>$data["user_id"]))',
                    'label' => Yii::t('app', 'Click to hide user on map'),
                    'icon'=>'ok-sign', //remove-circle,remove
                    'visible'=>'$data->show_in_map == 1',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),

            'hide' => array(
                'url' => 'Yii::app()->controller->createUrl("userDisplay", array("id"=>$data["user_id"]))',
                'label' => Yii::t('app', 'Click to display user on map'),
                'icon'=>'remove-circle', //remove-circle,remove
                'visible'=>'$data->show_in_map == 0',

                'options'=>array(
                    'class'=>'btn btn-small',

                ),
            ),
            'delete' => array(
                'url' => 'Yii::app()->controller->createUrl("userLeave", array("id"=>$data["user_id"]))',
                'label' => Yii::t('app', 'Click to remove user from group'),
                'icon'=>'remove', //remove-circle,remove
                'options'=>array(
                    'class'=>'btn btn-small',
                ),
            ),
            ),
        ),
    ),
)); ?>
    <h1><?= Yii::t('app', 'Invites')?></h1>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id'=>'tbl-team-grid',
    'dataProvider'=>$dataProviderInvites,
    'columns'=>array(
        'User.username',
        /*
        'end_point_name',
        'user_host_id',
        */
        array
        (
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{delete}',
            'deleteConfirmation'=> false,
            'afterDelete'=>'function(link,success,data){
                
               window.location.href = window.location.href;
            }',
            'buttons' => array(

                'delete' => array(
                    'url' => 'Yii::app()->controller->createUrl("userDelete", array("id"=>$data["user_id"]))',
                    'label' => Yii::t('app','Click to remove invitation to group'),
                    'icon'=>'remove', //remove-circle,remove
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),
            ),
        ),
    ),
)); ?>
</div>