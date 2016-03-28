<?php
$scriptPath=Yii::app()->AssetManager->publish(Yii::app()->baseUrl.'js/jquery.zclip.js');
$cs = Yii::app()->getClientScript();
$cs->registerScriptFile($scriptPath, CClientScript::POS_END);
?>

<div class="row code-wrapper"><div class="span12">
        <?php
        foreach ($model as $key => $cell): ?>


            <input id="<?php echo 'cell_id' . $cell->id ?>" class="code btn <?php echo ($cell->checked == 0) ? 'btn-success' : 'btn-inverse'; ?>" style="float:left" type="text" value="<?php echo $cell->cell_value; ?>">
    <?php endforeach; ?>
</div>
</div>


<script type="text/javascript">



$('.code').click(function(){

//    $(".code").zclip({
//        path: "/js/ZeroClipboard.swf",
//        copy: function() {
//            return $(this).text();
//        }
//    });
    $(this).removeClass('btn-success');
    $(this).addClass('btn-inverse');

    $.post( "<?php echo $this->createUrl('form/updatecell', array('id'=>$this->id));?>",
        { id: $(this)[0].id})
        .done(function( data ) {}, "json");

})

    function autoUpdated(){
        $.post( "<?php echo $this->createUrl('form/updater', array('id'=>$this->id));?>",
            { id: "<?php echo $geoModel->id;?>"})
            .done(function( data ) {
                $.each(JSON.parse(data), function(index, value) {
                    $('#'+index).removeClass('btn-success');
                    $('#'+index).removeClass('btn-inverse');
                    $('#'+index).addClass(value);
                });
            }, "json");
    }

    setInterval(autoUpdated,1000);


    </script>

