<?php
/* @var $this FormController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Geo Forms',
);

$this->menu=array(
	array('label'=>'Create GeoForm', 'url'=>array('create')),
	array('label'=>'Manage GeoForm', 'url'=>array('admin')),
);
?>

<h1>Geo Forms</h1>

<?php
echo 'test';
$this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>

<?php
