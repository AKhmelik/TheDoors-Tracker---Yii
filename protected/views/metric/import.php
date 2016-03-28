<div class="row-fluid">
    <div id="map" class="col-xs-12 col-md-10">
        <div class="form">
            <?php echo CHtml::beginForm('', 'post', array('enctype' => 'multipart/form-data')); ?>


            <div class="row">
               <?php echo CHtml::fileField('filename')?>
            </div>

            <div class="row submit">
                <?php echo CHtml::submitButton('submit'); ?>
            </div>

            <?php echo CHtml::endForm(); ?>
        </div>

    </div>
</div>
