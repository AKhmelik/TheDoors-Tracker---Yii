<div class="content-wrapper">
<h1><?= Yii::t('app', 'Invites')?></h1>
<?php echo CHtml::link(Yii::t('app', 'Create Group'), array('team/create'), array('class' => 'btn btn-primary')); ?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
    'id' => 'tbl-team-grid',
    'dataProvider' => $dataProviderInvites,
    'columns' => array(
        'Team.name',
        array(
            'type'=>'raw',
            'htmlOptions'=>array('width'=>'40px'),
            'value' => function($data){return CHtml::ajaxLink(
                 Yii::t('app', 'Accept'),
                Yii::app()->controller->createUrl("teamAccept", array("team_id"=>$data["team_id"])),
                array("type" => "POST","success"=>"function(){location.reload();}"),
                array("class" => "delete btn-success btn"));},
        ),
        array(
            'type'=>'raw',
            'htmlOptions'=>array('width'=>'40px'),
            'value' => function($data){return CHtml::ajaxLink(
                Yii::t('app', 'Delete'),
                Yii::app()->controller->createUrl("teamDelete", array("team_id"=>$data["team_id"])),
                array("type" => "POST","success"=>"function(){location.reload();}"),
                array("class" => "delete btn btn-danger"));},
        ),
        /*
        'end_point_name',
        'user_host_id',
        */
//        array
//        (
//
//            'class' => 'bootstrap.widgets.TbButtonColumn',
//            'template' => '{accept}{delete}',
//            'deleteConfirmation' => false,
//            'afterDelete' => 'function(link,success,data){
//
//               window.location.href = window.location.href;
//            }',
//
//
//
//            'buttons' => array(
//                'accept' => array(
//                    'url' => 'Yii::app()->controller->createUrl("teamAccept", array("id"=>$data["team_id"]))',
//                    'label' => 'Click to hide user on map',
//                    'icon' => 'ok-sign', //remove-circle,remove
//                    'options' => array(
//                        'class' => 'btn btn-small',
//                    ),
//                ),
//                'delete' => array(
//                    'url' => 'Yii::app()->controller->createUrl("teamDelete", array("id"=>$data["team_id"]))',
//                    'label' => 'Click to remove invitation to team',
//                    'icon' => 'remove', //remove-circle,remove
//                    'options' => array(
//                        'class' => 'btn btn-small',
//                    ),
//                ),
//
//            ),
//        ),
    ),
)); ?>
    </div>
