<?php
/* @var $this FormController */
/* @var $model GeoForm */

$this->breadcrumbs=array(
	'Geo Forms'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List GeoForm', 'url'=>array('index')),
	array('label'=>'Create GeoForm', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#geo-form-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Manage Geo Forms</h1>

<p>
You may optionally enter a comparison operator (<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
or <b>=</b>) at the beginning of each of your search values to specify how the comparison should be done.
</p>

<?php echo CHtml::link('Advanced Search','#',array('class'=>'search-button')); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'geo-form-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'type',
		array(
			'class'=>'CButtonColumn',
		),
	),
));

$this->widget('bootstrap.widgets.TbGridView',
    array(
        'type' => 'striped',
        'dataProvider'=>$model->search(),
        'template' => "{items}",
        'columns'=>array(
            'id',
            'type',
            array(
                'class'=>'CButtonColumn',
            ),
        ),
    )
);

?>
