<?php //This layout autoapplying to controller if request type is ajax and if there is "modal" POST/GET param
$this->beginContent('//layouts/ajax'); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel"><?php echo $this->pageTitle?></h3>
    </div>
    <div class="modal-body">
        <?php echo $content; ?>
    </div>

<?php $this->endContent(); ?>