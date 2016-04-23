
<h1>Manage <?php echo $team->name?> Team </h1>
<?php echo CHtml::beginForm();
$this->widget(
    'bootstrap.widgets.TbSelect2',
    array(
        'asDropDownList' => true,
        'name' => 'user_id',
        'data' => $users,
        'options' => array(
            'val' => $users,
            'placeholder' => 'Enter user name',
            'width' => '40%',
            'tokenSeparators' => array(',', ' ')
        )
    )
);
 echo CHtml::submitButton('Invite to team'); ?>
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
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{display}{hide}{delete}',
            'deleteConfirmation'=> false,
            'buttons' => array( // HERE
                'display' => array(
                    'url' => 'Yii::app()->controller->createUrl("userHide", array("id"=>$data["user_id"]))',
                    'label' => 'Click to hide user on map',
                    'icon'=>'ok-sign', //remove-circle,remove
                    'visible'=>'$data->show_in_map == 1',
                    'options'=>array(
                        'class'=>'btn btn-small',
                    ),
                ),

            'hide' => array(
                'url' => 'Yii::app()->controller->createUrl("userDisplay", array("id"=>$data["user_id"]))',
                'label' => 'Click to display user on map',
                'icon'=>'remove-circle', //remove-circle,remove
                'visible'=>'$data->show_in_map == 0',

                'options'=>array(
                    'class'=>'btn btn-small',

                ),
            ),
            'delete' => array(
                'url' => 'Yii::app()->controller->createUrl("userDelete", array("id"=>$data["user_id"]))',
                'label' => 'Click to remove user from team',
                'icon'=>'remove', //remove-circle,remove
                'options'=>array(
                    'class'=>'btn btn-small',
                ),
            ),
            ),
        ),
    ),
)); ?>
