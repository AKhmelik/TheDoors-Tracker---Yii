<?php
/* @var $this FormController */
/* @var $model GeoForm */

$this->breadcrumbs=array(
	'Geo Forms'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List GeoForm', 'url'=>array('index')),
	array('label'=>'Create GeoForm', 'url'=>array('create')),
	array('label'=>'View GeoForm', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage GeoForm', 'url'=>array('admin')),
);
?>

<h1>Update GeoForm <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>